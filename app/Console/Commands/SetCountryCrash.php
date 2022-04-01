<?php

namespace App\Console\Commands;

use App\Models\Incident;
use Illuminate\Console\Command;

class SetCountryCrash extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crash:setCountry';

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

        ini_set('memory_limit', '-1');

        $incidents = Incident::all();

        $bar = $this->output->createProgressBar(count($incidents));
        $bar->setFormat('very_verbose');
        $bar->start();

        foreach ($incidents as $i) {
            try {
                if ($i->incident_gps_lat != null && $i->incident_country == null) {
                    $i->setCrashCountry();
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
