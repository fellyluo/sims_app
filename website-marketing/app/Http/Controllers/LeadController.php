<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadRequest;
use App\Models\Lead;
use App\Services\LeadNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class LeadController extends Controller
{
    public function __construct(private LeadNotifier $notifier)
    {
    }

    public function store(StoreLeadRequest $request): RedirectResponse
    {
        $lead = DB::transaction(fn () => Lead::create($request->safe()->except('website')));

        try {
            $this->notifier->send($lead);
        } catch (Throwable $exception) {
            Log::error('Gagal mengirim notifikasi lead demo.', [
                'lead_uuid' => $lead->uuid,
                'message' => $exception->getMessage(),
            ]);
            report($exception);
        }

        return back()->with(
            'success',
            'Terima kasih. Permintaan demo Anda sudah kami terima dan tim kami akan segera menghubungi Anda.'
        );
    }
}
