<?php

namespace App\Jobs;

use App\Logic\Parse\ParseHtml;
use App\Model\Childs;
use App\Model\Links;
use Exception;
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
     *
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

        $new_links = [];

        echo 'Line: ' . __LINE__ . '; URL: ' . $this->url . PHP_EOL;

        try {

            $parse = new ParseHtml($this->url);
            $new_links = $parse->links();

        } catch (Exception $e) {

            echo 'URL: ' . $this->url . PHP_EOL;

            $error = sprintf('Line: ' . __LINE__ . '; [Exception] (code: %d) : %s as %s:%d',
                $e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine()
            );
            echo $error . PHP_EOL;

        }

        echo 'Line: ' . __LINE__ . '; New Links: ' . count($new_links) . PHP_EOL;

        $childs_ids = [];

        foreach ($new_links as $link) {
            $child_db = Childs::where('url', $link)->firstOrCreate(['url' => $link]);
            $childs_ids[] = $child_db->id;
        }

        echo 'Line: ' . __LINE__ . '; Childs Ids: ' . count($childs_ids) . PHP_EOL;

        Links::where('url', $this->url)
            ->first()
            ->childs()
            ->attach($childs_ids);

        echo 'Line: ' . __LINE__ . '; Attach childs ids;' . PHP_EOL;

        $current_links = [];

        //$not_processed = Links::where('site_id', $this->site_id)->get(['url']);
        $not_processed = Links::where('site_id', $this->site_id)->chunk(1000, function ($links) use (&$current_links) {
            foreach ($links as $link) {
                $current_links[] = $link->url;
            }
        });

        echo 'Line: ' . __LINE__ . '; Not Processed: ' . $not_processed . PHP_EOL;

        /*
        $current_links = [];

        foreach ($not_processed as $item) {
            $current_links[] = $item['url'];
        }*/

        echo 'Line: ' . __LINE__ . '; Current Links (not processed): ' . count($current_links) . PHP_EOL;

        $time = microtime(true);

        $links = array_diff(
            $new_links,
            $current_links
        );

        $time = microtime(true) - $time;

        echo 'Line: ' . __LINE__ . '; array_diff() took ' . number_format($time,
                3) . ' seconds and returned ' . count($links) . ' entries' . PHP_EOL;

        echo 'Line: ' . __LINE__ . '; Links Diff: ' . count($links) . PHP_EOL;

        if (count($links) > 0) {
            $links_db = [];

            foreach ($links as $link) {

                var_dump($link);

                $parse_link = parse_url($link);

                $links_db[] = [
                    'url' => $link,
                    'path' => $parse_link['path'] ?? '/',
                    'qlt_links' => count($new_links),
                    'site_id' => $this->site_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            \DB::table('links')->insert($links_db);

        }

        Links::where('site_id', $this->site_id)
            ->where('url', $this->url)
            ->update(['processed' => true, 'qlt_links' => count($new_links)]);

    }

    /**
     * The job failed to process.
     *
     * @param Exception $e
     * @return void
     */
    public function failed(Exception $e)
    {
        echo 'URL: ' . $this->url . PHP_EOL;

        $error = sprintf('Line: ' . __LINE__ . '; [Exception] (code: %d) : %s as %s:%d',
            $e->getCode(),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        );
        echo $error . PHP_EOL;
    }
}
