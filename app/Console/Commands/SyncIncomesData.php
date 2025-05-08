<?php

namespace App\Console\Commands;

use App\Actions\IncomeAction;
use App\Services\WbApiService;
use Illuminate\Console\Command;

class SyncIncomesData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wb:sync-incomes
                            {--dateFrom= : Start date (Y-m-d)}
                            {--dateTo= : End date (Y-m-d)}
                            {--limit= : Record limit}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync incomes data from WB API to the database';

    /**
     * Execute the console command.
     */
    public function handle(WbApiService $wbApiService): void
    {
        $dateFrom = $this->option('dateFrom') ?? now()->subDay()->format('Y-m-d');
        $dateTo = $this->option('dateTo') ?? now()->format('Y-m-d');
        $limit = $this->option('limit');

        $this->info("Syncing incomes data from $dateFrom to $dateTo...");

        $response = $wbApiService->getIncomes($dateFrom, $dateTo, 1, $limit);

        if (empty($response['data'])) {
            $this->error('No incomes data received from API');
            return;
        }

        $incomes = $response['data'];

        $this->info("Processing ".count($incomes)." incomes records...");

        try {
            $action = app(IncomeAction::class);
            $processedCount = $action->executeBatch($incomes);

            $this->info("Successfully processed $processedCount incomes records.");
        } catch (\Exception $e) {
            $this->error('Error syncing incomes: '.$e->getMessage());
        }
    }
}
