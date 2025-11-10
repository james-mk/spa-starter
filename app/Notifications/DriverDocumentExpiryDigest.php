<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DriverDocumentExpiryDigest extends Notification implements ShouldQueue
{
    use Queueable;

    public $expiringDocumentsData;
    public $expiredDocumentsData;
    public $totalCount;

    public function __construct($expiringDocuments, $expiredDocuments)
    {
        // Convert collections to arrays with primitive values
        $this->expiringDocumentsData = $expiringDocuments->map(function ($doc) {
            return [
                'driver_id' => $doc->driver->id ?? null,
                'driver_name' => $doc->driver->name ?? 'Unknown Driver',
                'document_type' => $doc->documentType->name ?? 'Unknown Document',
                'expiry_date' => $doc->expiry_date,
                'days_until_expiry' => now()->diffInDays($doc->expiry_date),
            ];
        })->toArray();

        $this->expiredDocumentsData = $expiredDocuments->map(function ($doc) {
            return [
                'driver_id' => $doc->driver->id ?? null,
                'driver_name' => $doc->driver->name ?? 'Unknown Driver',
                'document_type' => $doc->documentType->name ?? 'Unknown Document',
                'expiry_date' => $doc->expiry_date,
                'days_expired' => now()->diffInDays($doc->expiry_date),
            ];
        })->toArray();

        $this->totalCount = count($this->expiringDocumentsData) + count($this->expiredDocumentsData);
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        // Get notifiable name safely
        $notifiableName = $notifiable->first_name ?? $notifiable->username ?? 'Admin';

        $message = (new MailMessage)
            ->subject("Driver Documents Expiry Digest - {$this->totalCount} Document(s)")
            ->greeting('Hello ' . $notifiableName . '!')
            ->line("You have {$this->totalCount} driver document(s) requiring attention.");

        if (count($this->expiredDocumentsData) > 0) {
            $message->line("**Expired Documents: " . count($this->expiredDocumentsData) . "**");
            $message->line('');

            foreach (array_slice($this->expiredDocumentsData, 0, 5) as $doc) {
                $message->line("• {$doc['driver_name']} - {$doc['document_type']} (Expired {$doc['days_expired']} day(s) ago)");
            }

            if (count($this->expiredDocumentsData) > 5) {
                $remaining = count($this->expiredDocumentsData) - 5;
                $message->line("... and {$remaining} more expired document(s)");
            }

            $message->line('');
        }

        if (count($this->expiringDocumentsData) > 0) {
            $message->line("**Expiring Soon: " . count($this->expiringDocumentsData) . "**");
            $message->line('');

            foreach (array_slice($this->expiringDocumentsData, 0, 5) as $doc) {
                $message->line("• {$doc['driver_name']} - {$doc['document_type']} (Expires in {$doc['days_until_expiry']} day(s))");
            }

            if (count($this->expiringDocumentsData) > 5) {
                $remaining = count($this->expiringDocumentsData) - 5;
                $message->line("... and {$remaining} more expiring document(s)");
            }
        }

        $message->action('View All Drivers', url('/drivers'))
            ->line('Please review and take necessary action.');

        return $message;
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'digest',
            'total_count' => $this->totalCount,
            'expired_count' => count($this->expiredDocumentsData),
            'expiring_count' => count($this->expiringDocumentsData),
            'expired_documents' => $this->expiredDocumentsData,
            'expiring_documents' => $this->expiringDocumentsData,
            'message' => "{$this->totalCount} driver document(s) require attention",
            'url' => url('/drivers'),
        ];
    }
}
