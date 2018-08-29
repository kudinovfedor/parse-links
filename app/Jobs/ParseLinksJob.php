<?php

namespace App\Jobs;

use App\Logic\Parse\ParseHtml;
use App\Model\SiteLinks;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ParseLinksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    public $url;

    /**
     * @var int
     */
    public $site_id;

    /**
     * @var int
     */
    public $tries = 1;

    /**
     * @var int
     */
    public $timeout = 30;

    /**
     * @var array
     */
    public static $processed = [];

    /**
     * @var array
     */
    public static $not_processed = [];

    /**
     * Create a new job instance.
     * @param string $url
     * @param int $site_id
     */
    public function __construct(string $url, int $site_id)
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
        //info($this->url);

        $parse = new ParseHtml($this->url);
        $new_links = $parse->links();

        //$not_processed = SiteLinks::notProcessed($this->site_id)->get(['url']);
        $not_processed = SiteLinks::all(['url']);
        $current_links = [];

        foreach ($not_processed as $item) {
            $current_links[] = $item['url'];
        }

        $links = array_diff(
            $new_links,
            $current_links
        );

        if(count($links) > 0) {
            $links_db = [];

            foreach ($links as $link) {

                var_dump($link);

                $parse_link = parse_url($link);

                $links_db[] = [
                    'url' => $link,
                    'path' => $parse_link['path'] ?? '/',
                    'site_id' => $this->site_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            \DB::table('site_links')->insert($links_db);

        }

        SiteLinks::where('site_id', $this->site_id)->where('url', $this->url)->update(['processed' => true]);

    }
}
