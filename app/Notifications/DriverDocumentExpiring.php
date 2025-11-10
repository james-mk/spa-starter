<?php

namespace App\Notifications;

use App\Models\Driver;
use App\Models\DriverDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DriverDocumentExpiring extends Notification implements ShouldQueue
{
    use Queueable;

    public $driverId;
    public $driverName;
    public $documentId;
    public $documentType;
    public $expiryDate;
    public $daysUntilExpiry;

    public function __construct(Driver $driver, DriverDocument $document, $daysUntilExpiry)
    {
        // Store primitive values instead of model instances to avoid serialization issues
        $this->driverId = $driver->id;
        $this->driverName = $driver->name;
        $this->documentId = $document->id;
        $this->documentType = $document->documentType ? $document->documentType->name : 'Unknown Document';
        $this->expiryDate = $document->expiry_date;
        $this->daysUntilExpiry = $daysUntilExpiry;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $isExpired = $this->daysUntilExpiry < 0;
        $absoluteDays = abs($this->daysUntilExpiry);

        $subject = $isExpired
            ? "Driver Document Expired - {$this->driverName}"
            : "Driver Document Expiring Soon - {$this->driverName}";

        // Get notifiable name safely
        $notifiableName = $notifiable->first_name ?? $notifiable->username ?? 'Admin';

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . $notifiableName . '!')
            ->line("Driver: {$this->driverName}")
            ->line("Document Type: {$this->documentType}");

        if ($isExpired) {
            $message->error()
                ->line("⚠️ This document expired {$absoluteDays} day(s) ago.")
                ->line("Expiry Date: " . $this->expiryDate->format('d M, Y'));
        } else {
            $message->warning()
                ->line("⚠️ This document will expire in {$absoluteDays} day(s).")
                ->line("Expiry Date: " . $this->expiryDate->format('d M, Y'));
        }

        $message->action('View Driver Details', url('/drivers/' . $this->driverId))
            ->line('Please take necessary action to renew this document.');

        return $message;
    }

    public function toArray($notifiable)
    {
        $isExpired = $this->daysUntilExpiry < 0;
        $absoluteDays = abs($this->daysUntilExpiry);

        return [
            'driver_id' => $this->driverId,
            'driver_name' => $this->driverName,
            'document_id' => $this->documentId,
            'document_type' => $this->documentType,
            'expiry_date' => $this->expiryDate->format('Y-m-d'),
            'days_until_expiry' => $this->daysUntilExpiry,
            'is_expired' => $isExpired,
            'status' => $isExpired ? 'expired' : 'expiring',
            'message' => $isExpired
                ? "Document expired {$absoluteDays} day(s) ago"
                : "Document expiring in {$absoluteDays} day(s)",
            'url' => url('/drivers/' . $this->driverId),
        ];
    }
}
