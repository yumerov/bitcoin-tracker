<?php

namespace App\Console\Commands;

use App\Services\PriceSynchronizer;
use Illuminate\Console\Command;

class PriceSync extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'price:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    public function __construct(
        private readonly PriceSynchronizer $synchronizer
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('The synchronization begins.');
        $this->synchronizer->synchronize();
        $this->info('The synchronization have ended');
    }
}
