<?php

namespace App\Jobs;

use App\Actions\StockAction;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessStocksBatch implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [10, 30, 60];

    /**
     * Create a new job instance.
     */
    public function __construct(protected array $stockData)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @throws Exception
     */
    public function handle(StockAction $stockAction): void
    {
        if ($this->batch() && $this->batch()->cancelled()) {
            return;
        }

        try {
            $processed = $stockAction->processChunk($this->stockData);

            Log::info('ProcessStocksBatch completed', [
                'processed' => $processed,
                'total' => count($this->stockData),
            ]);
        } catch (Exception $e) {
            Log::error('ProcessStocksBatch failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Handle job failure after all retries.
     */
    public function failed(Throwable $exception): void
    {
        Log::critical('ProcessStocksBatch job failed after retries', [
            'error' => $exception->getMessage(),
            'data_sample' => array_slice($this->stockData, 0, 2),
        ]);
    }
}
