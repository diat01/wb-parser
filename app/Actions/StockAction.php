<?php

namespace App\Actions;

use App\Jobs\ProcessStocksBatch;
use App\Models\Stock;
use Exception;
use Illuminate\Support\Facades\Log;

class StockAction
{
    /**
     * Dispatch stock data in chunks to queue using ProcessStocksBatch job
     *
     * @param  array  $stockData
     * @return int Number of dispatched records
     */
    public function executeBatch(array $stockData): int
    {
        $chunks = array_chunk($stockData, 100);
        $totalDispatched = 0;

        foreach ($chunks as $chunk) {
            try {
                ProcessStocksBatch::dispatch($chunk);
                $totalDispatched += count($chunk);
            } catch (Exception $e) {
                Log::error('Failed to dispatch ProcessStocksBatch job', [
                    'error' => $e->getMessage(),
                    'chunk_sample' => array_slice($chunk, 0, 2)
                ]);
            }
        }

        return $totalDispatched;
    }

    /**
     * Process a single chunk and save to DB
     *
     * @param  array  $chunk
     * @return int Number of processed items
     * @throws Exception
     */
    public function processChunk(array $chunk): int
    {
        $processed = 0;

        foreach ($chunk as $stock) {
            $this->execute($stock);
            $processed++;
        }

        return $processed;
    }

    /**
     * Create or update a stock record
     *
     * @param  array  $stockData
     * @return Stock
     * @throws Exception
     */
    public function execute(array $stockData): Stock
    {
        try {
            return Stock::updateOrCreate(
                ['nm_id' => $stockData['nm_id']],
                $stockData
            );
        } catch (Exception $e) {
            Log::error('StockAction failed', [
                'error' => $e->getMessage(),
                'stockData' => $stockData
            ]);
            throw $e;
        }
    }
}
