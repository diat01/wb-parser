<?php

namespace App\Actions;

use App\Jobs\ProcessIncomesBatch;
use App\Models\Income;
use Exception;
use Illuminate\Support\Facades\Log;

class IncomeAction
{
    /**
     * Dispatch income data in chunks to queue using the ProcessIncomesBatch job
     *
     * @param  array  $incomeData
     * @return int Number of dispatched records
     */
    public function executeBatch(array $incomeData): int
    {
        $chunks = array_chunk($incomeData, 100);
        $totalDispatched = 0;

        foreach ($chunks as $chunk) {
            try {
                ProcessIncomesBatch::dispatch($chunk);
                $totalDispatched += count($chunk);
            } catch (Exception $e) {
                Log::error('Failed to dispatch ProcessIncomesBatch job', [
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

        foreach ($chunk as $income) {
            $this->execute($income);
            $processed++;
        }

        return $processed;
    }

    /**
     * Create or update an income record
     *
     * @param  array  $incomeData
     * @return Income
     * @throws Exception
     */
    public function execute(array $incomeData): Income
    {
        try {
            return Income::updateOrCreate(
                [
                    'income_id' => $incomeData['income_id'],
                    'barcode' => $incomeData['barcode'],
                ],
                $incomeData
            );
        } catch (Exception $e) {
            Log::error('IncomeAction failed', [
                'error' => $e->getMessage(),
                'incomeData' => $incomeData
            ]);
            throw $e;
        }
    }
}
