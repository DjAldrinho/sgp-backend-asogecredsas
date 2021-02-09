<?php

namespace App\Jobs;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class StoreTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $account_id, $type_transaction_id, $origin, $supplier_id, $amount;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($account_id, $origin, $amount, $supplier_id = 3, $type_transaction_id = 5)
    {
        $this->account_id = $account_id;
        $this->type_transaction_id = $type_transaction_id;
        $this->origin = $origin;
        $this->supplier_id = $supplier_id;
        $this->amount = $amount;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Saving transactions!');

        $count = Transaction::count();

        $count = $count + 1;

        Transaction::create([
            'account_id' => $this->account_id,
            'type_transaction_id' => $this->type_transaction_id,
            'origin' => $this->origin,
            'code' => 'T' . time() . '-' . $count,
            'supplier_id' => $this->supplier_id,
            'value' => $this->amount
        ]);
    }
}
