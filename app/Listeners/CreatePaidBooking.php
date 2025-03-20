<?php

namespace App\Listeners;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\TransactionPaidProcessed;
use App\Repositories\Booking\BookingRepository;
use App\Repositories\Booking\BookingDetailRepository;

class CreatePaidBooking
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TransactionPaidProcessed $event): void
    {
        try {

            Log::info("Create Paid Booking");
            $transaction = $event->transaction;

            // Save transaction data into TransactionPaid model
            $booking = BookingRepository::create([
                'transaction_id' => $transaction->id,
                'transaction_number' => $transaction->number,
                'user_id' => $transaction->user_id,
            ]);

            foreach($event->transaction->transactionDetails as $detail)
            {
                BookingDetailRepository::create([
                    'booking_id' => $booking->id,
                    'transaction_detail_id' => $detail->id,
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Create Paid Booking error: ' . $e->getMessage());
        }
    }
}
