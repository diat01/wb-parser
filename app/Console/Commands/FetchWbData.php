<?php

namespace App\Console\Commands;

use App\Models\Income;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Stock;
use App\Services\WbApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class FetchWbData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wb:fetch
                            {--dateFrom= : Start date (Y-m-d)}
                            {--dateTo= : End date (Y-m-d)}
                            {--limit= : Record limit}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull WB API data and save it to the database';

    /**
     * Execute the console command.
     */
    public function handle(WbApiService $wbApiService): void
    {
        $dateFrom = $this->option('dateFrom') ?? Carbon::now()->format('Y-m-d');
        $dateTo = $this->option('dateTo') ?? Carbon::now()->format('Y-m-d');
        $limit = $this->option('limit');

        $this->info("Pulling data ($dateFrom - $dateTo)...");

        // Sales
        $this->processData(
            'sales',
            $wbApiService->getSales($dateFrom, $dateTo, 1, $limit),
            Sale::class,
            'g_number',
        );

        // Orders
        $this->processData(
            'orders',
            $wbApiService->getOrders($dateFrom, $dateTo, 1, $limit),
            Order::class,
            'g_number',
        );

        // Stocks
        $this->processData(
            'stocks',
            $wbApiService->getStocks($dateFrom, 1, $limit),
            Stock::class,
            'nm_id',
        );

        // Incomes
        $this->processData(
            'incomes',
            $wbApiService->getIncomes($dateFrom, $dateTo, 1, $limit),
            Income::class,
            'income_id',
        );

        $this->info('Data pull is complete!');
    }

    protected function processData(string $type, ?array $data, string $modelClass, string $uniqueKey): void
    {
        if (empty($data['data'])) {
            $this->error("Failed to get $type data!");
            return;
        }

        $this->info(sprintf('%d pieces of %s data are being processed...', count($data['data']), $type));

        $bar = $this->output->createProgressBar(count($data['data']));
        $bar->start();

        foreach ($data['data'] as $item) {
            $modelClass::updateOrCreate(
                [$uniqueKey => $item[$uniqueKey]],
                $item
            );

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }
}
