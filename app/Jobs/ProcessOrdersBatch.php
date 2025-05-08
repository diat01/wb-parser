<?php

namespace App\Jobs;

use App\Actions\OrderAction;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessOrdersBatch implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public array $backoff = [10, 30, 60];

    /**
     * Create a new job instance.
     */
    public function __construct(protected array $orderData)
    {
        //
    }

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle(OrderAction $orderAction): void
    {
        if ($this->batch() && $this->batch()->cancelled()) {
            return;
        }

        try {
            $processed = $orderAction->processChunk($this->orderData);

            Log::info("ProcessOrdersBatch completed", [
                'processed' => $processed,
                'total' => count($this->orderData)
            ]);
        } catch (Exception $e) {
            Log::error("ProcessOrdersBatch failed", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Handle job failure after all retries.
     */
    public function failed(Throwable $exception): void
    {
        Log::critical("ProcessOrdersBatch job failed after retries", [
            'error' => $exception->getMessage(),
            'data_sample' => array_slice($this->orderData, 0, 2)
        ]);
    }
}
