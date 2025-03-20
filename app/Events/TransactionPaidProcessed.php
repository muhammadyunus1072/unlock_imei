<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use App\Models\Transaction\Transaction;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;

class TransactionPaidProcessed
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('transaction-paid'),
        ];
    }
}
