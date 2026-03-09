<?php

namespace App\Observers;

use App\Models\Customer;
use App\Models\Event; // Import the Event model
use App\Http\Enums\EventType; // Import the EventType Enum
use Illuminate\Support\Facades\Auth; // To get the authenticated user

class CustomerObserver
{
    /**
     * Handle the Customer "created" event.
     */
    public function created(Customer $customer): void
    {
        Event::create([
            'merchant_id' => $customer->merchant_id,
            'user_id' => Auth::id(),
            'event_type' => EventType::CustomerCreated,
            'payload' => $customer->toArray(),
        ]);
    }

    /**
     * Handle the Customer "updated" event.
     */
    public function updated(Customer $customer): void
    {
        if ($customer->isDirty()) { // Only create event if something actually changed
            Event::create([
                'merchant_id' => $customer->merchant_id,
                'user_id' => Auth::id(),
                'event_type' => EventType::CustomerUpdated,
                'payload' => array_merge($customer->getChanges(), ['original' => $customer->getOriginal()]), // Log changes
            ]);
        }
    }

    /**
     * Handle the Customer "deleted" event.
     */
    public function deleted(Customer $customer): void
    {
        Event::create([
            'merchant_id' => $customer->merchant_id,
            'user_id' => Auth::id(),
            'event_type' => EventType::CustomerDeleted,
            'payload' => $customer->toArray(),
        ]);
    }

    /**
     * Handle the Customer "restored" event.
     */
    public function restored(Customer $customer): void
    {
        // Not adding an event for restored for now, but could be added if needed
    }

    /**
     * Handle the Customer "force deleted" event.
     */
    public function forceDeleted(Customer $customer): void
    {
        // Not adding an event for force deleted for now, but could be added if needed
    }
}