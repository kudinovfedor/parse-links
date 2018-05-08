<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Yangqi\Htmldom\Htmldom;

class ParseLinksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $url;
    public $site_id;

    /**
     * Create a new job instance.
     *
     * @param $url
     * @param $site_id
     */
    public function __construct($url, $site_id)
    {
        $this->url = $url;
        $this->site_id = $site_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $site_url  = $this->url;
        $site_host = parse_url($site_url)['host'];

        $html = new Htmldom($site_url);

        $links = [];

        foreach ($html->find('a') as $link) {

            if ( ! in_array($link->href, $site_links_array)) {
                $links[] = $link->href;
            }

        }
    }
}
