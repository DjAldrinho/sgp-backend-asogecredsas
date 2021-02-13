<?php

namespace App\Jobs;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StoreTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $account_id, $type_transaction_id, $origin, $supplier_id, $amount, $commentary, $credit_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($account_id, $origin, $amount, $commentary = '', $supplier_id = 3,
                                $type_transaction_id = 5, $credit_id = 0)
    {
        $this->account_id = $account_id;
        $this->type_transaction_id = $type_transaction_id;
        $this->origin = $origin;
        $this->supplier_id = $supplier_id;
        $this->amount = $amount;
        $this->commentary = $commentary;
        $this->credit_id = $credit_id;
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
            'origin' => $this->origin,
            'code' => 'T' . time() . '-' . $count,
            'value' => $this->amount,
            'supplier_id' => $this->supplier_id,
            'account_id' => $this->account_id,
            'type_transaction_id' => $this->type_transaction_id,
            'commentary' => $this->commentary,
            'user_id' => Auth::id(),
            'credit_id' => $this->credit_id
        ]);
    }
}
