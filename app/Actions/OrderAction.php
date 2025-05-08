<?php

namespace App\Actions;

use App\Jobs\ProcessOrdersBatch;
use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Log;

class OrderAction
{
    /**
     * Dispatch order data in chunks to queue using the ProcessOrdersBatch job
     *
     * @param array $orderData
     * @return int Number of dispatched records
     */
    public function executeBatch(array $orderData): int
    {
        $chunks = array_chunk($orderData, 100);
        $totalDispatched = 0;

        foreach ($chunks as $chunk) {
            try {
                ProcessOrdersBatch::dispatch($chunk);
                $totalDispatched += count($chunk);
            } catch (Exception $e) {
                Log::error('Failed to dispatch ProcessOrdersBatch job', [
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
     * @param array $chunk
     * @return int Number of processed items
     * @throws Exception
     */
    public function processChunk(array $chunk): int
    {
        $processed = 0;

        foreach ($chunk as $order) {
            $this->execute($order);
            $processed++;
        }

        return $processed;
    }

    /**
     * Create or update an order record
     *
     * @param array $orderData
     * @return Order
     * @throws Exception
     */
    public function execute(array $orderData): Order
    {
        try {
            return Order::updateOrCreate(
                ['g_number' => $orderData['g_number']],
                $orderData
            );
        } catch (Exception $e) {
            Log::error('OrderAction failed', [
                'error' => $e->getMessage(),
                'orderData' => $orderData
            ]);
            throw $e;
        }
    }
}
