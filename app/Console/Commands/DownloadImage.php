<?php

namespace App\Console\Commands;

use App\Models\Incident;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;



class DownloadImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:download';

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

        foreach ($incidents as $incident) {
            foreach ($incident->images as $img) {
                if (!$img->local_url) {
                    try {
                        $url = $img->url;

                        $data = file_get_contents($url);
                        $path = "/planes/" . $incident->id . "/" . $img->id . ".jpg";
                        Storage::put("public" . $path, $data);
                        $img->local_url = "/storage" . $path;
                        $img->update();
                    } catch (Exception $e) {
                    }
                }
            }
            $bar->advance();
        }
        $bar->finish();

        return 0;
    }
}
