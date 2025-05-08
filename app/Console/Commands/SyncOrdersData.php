<?php

namespace App\Console\Commands;

use App\Actions\OrderAction;
use App\Services\WbApiService;
use Illuminate\Console\Command;

class SyncOrdersData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wb:sync-orders
                            {--dateFrom= : Start date (Y-m-d)}
                            {--dateTo= : End date (Y-m-d)}
                            {--limit= : Record limit}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync orders data from WB API to the database';

    /**
     * Execute the console command.
     */
    public function handle(WbApiService $wbApiService): void
    {
        $dateFrom = $this->option('dateFrom') ?? now()->subDay()->format('Y-m-d');
        $dateTo = $this->option('dateTo') ?? now()->format('Y-m-d');
        $limit = $this->option('limit');

        $this->info("Syncing orders data from $dateFrom to $dateTo...");

        $response = $wbApiService->getOrders($dateFrom, $dateTo, 1, $limit);

        if (empty($response['data'])) {
            $this->error('No orders data received from API');
            return;
        }

        $orders = $response['data'];

        $this->info("Processing ".count($orders)." orders records...");

        try {
            $action = app(OrderAction::class);
            $processedCount = $action->executeBatch($orders);

            $this->info("Successfully processed $processedCount orders records.");
        } catch (\Exception $e) {
            $this->error('Error syncing orders: '.$e->getMessage());
        }
    }
}
