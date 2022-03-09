<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Models\Incident;

class SetGPSEnd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setGPS:end';

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
        $incidents = Incident::where("id", ">=", 5000)->get();
        $bar = $this->output->createProgressBar(count($incidents));
        $bar->setFormat('very_verbose');

        $bar->start();

        foreach ($incidents as $i) {
            try {
                if ($i->end_gps_lat == null) {
                    $i->setGPSEnd();
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
