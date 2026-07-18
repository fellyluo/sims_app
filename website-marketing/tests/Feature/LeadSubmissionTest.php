<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Notifications\LeadReceived;
use App\Services\LeadNotifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use RuntimeException;
use Tests\TestCase;

class LeadSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_contact_is_stored_and_admin_is_notified(): void
    {
        Notification::fake();
        config()->set('marketing.leads.notification_email', 'admin@sims.test');

        $response = $this->post('/kontak', $this->validPayload());

        $response->assertRedirect()->assertSessionHas('success');

        $lead = Lead::query()->sole();

        $this->assertNotEmpty($lead->uuid);
        $this->assertSame('Sekolah Harapan', $lead->sekolah);
        $this->assertSame('pro', $lead->tier_diminati);
        $this->assertSame('kontak', $lead->sumber);

        Notification::assertSentOnDemand(
            LeadReceived::class,
            fn (LeadReceived $notification, array $channels, object $notifiable) =>
                $notifiable->routes['mail'] === 'admin@sims.test'
                && $notification->lead->is($lead)
        );
    }

    public function test_invalid_contact_is_rejected_without_creating_a_lead(): void
    {
        Notification::fake();

        $this->post('/kontak', [
            'nama' => '',
            'sekolah' => '',
            'email' => 'bukan-email',
            'sumber' => 'tidak-valid',
        ])->assertSessionHasErrors(['nama', 'sekolah', 'email', 'sumber']);

        $this->assertDatabaseCount('leads', 0);
        Notification::assertNothingSent();
    }

    public function test_honeypot_blocks_spam_submission(): void
    {
        Notification::fake();

        $this->post('/kontak', [
            ...$this->validPayload(),
            'website' => 'https://spam.example',
        ])->assertSessionHasErrors('website');

        $this->assertDatabaseCount('leads', 0);
        Notification::assertNothingSent();
    }

    public function test_contact_endpoint_is_limited_to_five_requests_per_minute(): void
    {
        Notification::fake();

        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $this->post('/kontak', [
                ...$this->validPayload(),
                'email' => "user{$attempt}@example.test",
            ])->assertRedirect();
        }

        $this->post('/kontak', [
            ...$this->validPayload(),
            'email' => 'keenam@example.test',
        ])->assertTooManyRequests();

        $this->assertDatabaseCount('leads', 5);
    }

    public function test_lead_is_kept_when_notification_fails(): void
    {
        $this->mock(LeadNotifier::class, function ($mock): void {
            $mock->shouldReceive('send')
                ->once()
                ->andThrow(new RuntimeException('SMTP unavailable'));
        });

        $this->post('/kontak', $this->validPayload())
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseCount('leads', 1);
        $this->assertSame('Sekolah Harapan', Lead::query()->value('sekolah'));
    }

    private function validPayload(): array
    {
        return [
            'nama' => 'Felly Luo',
            'sekolah' => 'Sekolah Harapan',
            'jabatan' => 'Kepala Sekolah',
            'email' => 'felly@example.test',
            'no_hp' => '0812 3456 7890',
            'perkiraan_siswa' => 500,
            'tier_diminati' => 'pro',
            'pesan' => 'Ingin melihat alur akademik dan keuangan.',
            'sumber' => 'kontak',
            'website' => '',
        ];
    }
}
