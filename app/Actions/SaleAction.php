<?php

namespace App\Actions;

use App\Jobs\ProcessSalesBatch;
use App\Models\Sale;
use Exception;
use Illuminate\Support\Facades\Log;

class SaleAction
{
    /**
     * Dispatch sales data in chunks to queue using ProcessSalesBatch job
     *
     * @param  array  $salesData
     * @return int Number of dispatched records
     */
    public function executeBatch(array $salesData): int
    {
        $chunks = array_chunk($salesData, 100);
        $totalDispatched = 0;

        foreach ($chunks as $chunk) {
            try {
                ProcessSalesBatch::dispatch($chunk);
                $totalDispatched += count($chunk);
            } catch (Exception $e) {
                Log::error('Failed to dispatch ProcessSalesBatch job', [
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

        foreach ($chunk as $sale) {
            $this->execute($sale);
            $processed++;
        }

        return $processed;
    }

    /**
     * Create or update a sale record
     *
     * @param  array  $saleData
     * @return Sale
     * @throws Exception
     */
    public function execute(array $saleData): Sale
    {
        try {
            return Sale::updateOrCreate(
                ['g_number' => $saleData['g_number']],
                $saleData
            );
        } catch (Exception $e) {
            Log::error('SalesAction failed', [
                'error' => $e->getMessage(),
                'saleData' => $saleData
            ]);
            throw $e;
        }
    }
}
