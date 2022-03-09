<?php

namespace App\Console\Commands;

use App\Models\Incident;
use Illuminate\Console\Command;

class SetImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:setAll';

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
        $bar->setFormat('very_verbose');

        $bar->start();

        foreach ($incidents as $i) {
            try {
                if (count($i->images) == 0)
                    $i->setImage();
            } catch (\Exception $e) {
                //echo $e;
            }
            $bar->advance();
        }
        $bar->finish();
        return 0;
    }
}
