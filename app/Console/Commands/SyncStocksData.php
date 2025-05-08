<?php

namespace App\Console\Commands;

use App\Actions\StockAction;
use App\Services\WbApiService;
use Illuminate\Console\Command;

class SyncStocksData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wb:sync-stocks
                            {--dateFrom= : Start date (Y-m-d)}
                            {--limit= : Record limit}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync stocks data from WB API to the database';

    /**
     * Execute the console command.
     */
    public function handle(WbApiService $wbApiService): void
    {
        $dateFrom = $this->option('dateFrom') ?? now()->format('Y-m-d');
        $limit = $this->option('limit');

        $this->info("Syncing stocks data...");

        $stocks = $wbApiService->getAllStocks($dateFrom, $limit);

        if (empty($stocks)) {
            $this->error('No stocks data received from API');
            return;
        }

        $this->info("Processing ".count($stocks)." stock records...");

        try {
            $action = app(StockAction::class);
            $processedCount = $action->executeBatch($stocks);

            $this->info("Successfully processed $processedCount stock records.");
        } catch (\Exception $e) {
            $this->error('Error syncing stocks: '.$e->getMessage());
        }
    }
}
