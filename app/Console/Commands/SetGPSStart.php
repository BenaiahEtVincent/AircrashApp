<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Incident;


class SetGPSStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setGPS:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $incidents = Incident::all();
        $bar = $this->output->createProgressBar(count($incidents));
        $bar->start();

        foreach ($incidents as $i) {
            try {
                if ($i->depart_gps_lat == null) {
                    $i->setGPSStart();
                }
            } catch (\Exception $e) {
                //echo $e;
            }
            $bar->advance();
        }
        $bar->finish();
        return 0;
    }
}
