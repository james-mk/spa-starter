<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\DriverDocumentExpiring;
use App\Models\Driver;
use App\Models\DriverDocument;
use Illuminate\Console\Command;

class TestNotification extends Command
{
    protected $signature = 'test:notification {user_id=1}';
    protected $description = 'Send a test notification';

    public function handle()
    {
        $userId = $this->argument('user_id');

        // Check if user exists
        $user = User::find($userId);
        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return 1;
        }

        // Check total drivers count
        $driversCount = Driver::count();
        $this->info("Total drivers in database: {$driversCount}");

        if ($driversCount === 0) {
            $this->error('No drivers found in database');
            return 1;
        }

        // Get a driver
        $driver = Driver::first();
        $this->info("Found driver: {$driver->full_name} (ID: {$driver->id})");

        // Check documents count
        $documentsCount = DriverDocument::where('driver_id', $driver->id)->count();
        $this->info("Documents for this driver: {$documentsCount}");

        if ($documentsCount === 0) {
            $this->error('No documents found for this driver');
            return 1;
        }

        // Get a document
        $document = DriverDocument::where('driver_id', $driver->id)->first();
        $this->info("Found document: {$document->document_type} (ID: {$document->id})");

        // Send notification
        $user->notify(new DriverDocumentExpiring($driver, $document, 30));

        $this->info("Test notification sent successfully to {$user->name}");

        return 0;
    }
}
