<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithAi;
use App\Models\AiConversation;
use App\Services\GeminiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RuntimeException;

/*
| Chatbot Tanya-Jawab AI (FASE 2). Widget mengambang memanggil endpoint ini.
| Percakapan & pesan disimpan per user (di-scope user_uuid) supaya bisa
| dilanjutkan. System prompt diberi konteks FAQ sekolah (config ai.chat.faq).
*/
class AiChatController extends Controller
{
    use InteractsWithAi;

    public function __construct(private GeminiService $gemini) {}

    /** POST /ai/chat — kirim pesan; buat/lanjutkan percakapan. */
    public function send(Request $request): JsonResponse
    {
        $data = $request->validate([
            'message'         => ['required', 'string', 'max:'.config('ai.max_input_chars')],
            'conversation_id' => ['nullable', 'string', 'size:36'],
        ]);

        $userId  = $request->user()->uuid;
        $message = trim($data['message']);

        if ($limited = $this->aiRateLimited('chat', $userId)) {
            return $limited;
        }

        // Ambil percakapan milik user, atau buat baru dengan judul dari pesan pertama.
        $conversation = null;
        if (!empty($data['conversation_id'])) {
            $conversation = AiConversation::where('uuid', $data['conversation_id'])
                ->where('user_uuid', $userId)
                ->first();
        }
        $conversation ??= AiConversation::create([
            'user_uuid' => $userId,
            'title'     => Str::limit($message, 60, '…'),
        ]);

        // Riwayat konteks (pesan-pesan sebelum yang baru ini), urut kronologis.
        $history = $conversation->messages()
            ->latest('created_at')
            ->take(config('ai.chat.history_limit'))
            ->get()
            ->reverse()
            ->map(fn ($m) => ['role' => $m->role, 'text' => $m->content])
            ->values()
            ->all();

        // Simpan pesan pengguna lebih dulu (tetap tersimpan walau AI gagal).
        $conversation->messages()->create([
            'role'           => 'user',
            'content'        => $message,
            'token_estimate' => (int) ceil(mb_strlen($message) / 4),
        ]);

        try {
            $result = $this->gemini->generate($message, [
                'system'  => config('ai.chat.faq'),
                'history' => $history,
            ]);
        } catch (RuntimeException $e) {
            $this->logAiUsage($userId, 'chat', config('ai.model'), 0, 0, 'error');

            return response()->json([
                'ok'              => false,
                'conversation_id' => $conversation->uuid,
                'message'         => $e->getMessage(),
            ], 502);
        }

        $conversation->messages()->create([
            'role'           => 'assistant',
            'content'        => $result['text'],
            'token_estimate' => $result['completion_tokens'],
        ]);
        $conversation->touch(); // dorong ke atas daftar riwayat

        $this->logAiUsage(
            $userId,
            'chat',
            $result['model'],
            $result['prompt_tokens'],
            $result['completion_tokens'],
            'success',
        );

        return response()->json([
            'ok'              => true,
            'conversation_id' => $conversation->uuid,
            'title'           => $conversation->title,
            'answer'          => $result['text'],
        ]);
    }

    /** GET /ai/chat/history — daftar percakapan user (untuk riwayat). */
    public function history(Request $request): JsonResponse
    {
        $items = AiConversation::where('user_uuid', $request->user()->uuid)
            ->orderByDesc('updated_at')
            ->limit(30)
            ->get(['uuid', 'title', 'updated_at'])
            ->map(fn ($c) => [
                'id'    => $c->uuid,
                'title' => $c->title ?: 'Percakapan',
                'time'  => optional($c->updated_at)->diffForHumans(),
            ]);

        return response()->json(['ok' => true, 'conversations' => $items]);
    }

    /** GET /ai/chat/{conversation} — muat pesan satu percakapan (untuk dilanjutkan). */
    public function show(Request $request, string $conversation): JsonResponse
    {
        $conv = AiConversation::where('uuid', $conversation)
            ->where('user_uuid', $request->user()->uuid)
            ->firstOrFail();

        $messages = $conv->messages()
            ->orderBy('created_at')
            ->get(['role', 'content'])
            ->map(fn ($m) => ['role' => $m->role, 'content' => $m->content]);

        return response()->json([
            'ok'       => true,
            'id'       => $conv->uuid,
            'title'    => $conv->title,
            'messages' => $messages,
        ]);
    }

    /** DELETE /ai/chat/{conversation} — hapus percakapan (beserta pesannya). */
    public function destroy(Request $request, string $conversation): JsonResponse
    {
        AiConversation::where('uuid', $conversation)
            ->where('user_uuid', $request->user()->uuid)
            ->firstOrFail()
            ->delete();

        return response()->json(['ok' => true]);
    }
}
