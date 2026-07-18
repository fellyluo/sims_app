<?php

namespace App\Services;

use App\Models\Lead;
use App\Notifications\LeadReceived;
use Illuminate\Support\Facades\Notification;

class LeadNotifier
{
    public function send(Lead $lead): void
    {
        Notification::route('mail', config('marketing.leads.notification_email'))
            ->notify(new LeadReceived($lead));
    }
}
