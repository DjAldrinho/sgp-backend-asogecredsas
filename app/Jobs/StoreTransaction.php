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

    protected $account_id, $type_transaction_id, $origin, $supplier_id, $amount, $commentary, $credit_id,
        $process_id, $transaction_date;
    /**
     * @var string
     */

    /**
     * Create a new job instance.
     *
     * @param $account_id
     * @param $origin
     * @param $amount
     * @param string $commentary
     * @param int $supplier_id
     * @param int $type_transaction_id
     * @param null $credit_id
     * @param null $process_id
     * @param string $transaction_date
     */
    public function __construct($account_id, $origin, $amount, $commentary = '', $supplier_id = 3,
                                $type_transaction_id = 5, $credit_id = null, $process_id = null, $transaction_date = '')
    {
        $this->account_id = $account_id;
        $this->type_transaction_id = $type_transaction_id;
        $this->origin = $origin;
        $this->supplier_id = $supplier_id;
        $this->amount = $amount;
        $this->commentary = $commentary;
        $this->credit_id = $credit_id;
        $this->process_id = $process_id;
        $this->transaction_date = $transaction_date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
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
                'credit_id' => $this->credit_id,
                'process_id' => $this->process_id,
                'transaction_date' => $this->transaction_date
            ]);

        } catch (\Exception $exception) {
            Log::error('Store transaction: ' . $exception->getMessage());
        }
    }
}
