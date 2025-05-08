<?php

namespace App\Console\Commands;

use App\Actions\SaleAction;
use App\Services\WbApiService;
use Illuminate\Console\Command;

class SyncSalesData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wb:sync-sales
                            {--dateFrom= : Start date (Y-m-d)}
                            {--dateTo= : End date (Y-m-d)}
                            {--limit= : Record limit}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync sales data from WB API to the database';

    /**
     * Execute the console command.
     */
    public function handle(WbApiService $wbApiService): void
    {
        $dateFrom = $this->option('dateFrom') ?? now()->subDay()->format('Y-m-d');
        $dateTo = $this->option('dateTo') ?? now()->format('Y-m-d');
        $limit = $this->option('limit');

        $this->info("Syncing sales data from $dateFrom to $dateTo...");

        $sales = $wbApiService->getAllSales($dateFrom, $dateTo, $limit);

        if (empty($sales)) {
            $this->error('No sales data received from API');
            return;
        }

        $this->info("Processing ".count($sales)." sales records...");

        try {
            $action = app(SaleAction::class);
            $processedCount = $action->executeBatch($sales);

            $this->info("Successfully processed $processedCount sales records.");
        } catch (\Exception $e) {
            $this->error('Error syncing sales: '.$e->getMessage());
        }
    }
}
