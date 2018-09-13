<?php

namespace App\Http\Controllers;

use App\Jobs\ParseLinksJob;
use App\Logic\Parse\ParseHtml;
use App\Model\Sites;
use App\Model\Links;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        $data = [];
        $page = 1;
        $chunk_count = 50;
        $max_results = 2048;

        $time_start = microtime(true);

        Links::where('site_id', 1)
            ->with([
                'childs' => function ($query) {
                    /** @var \Illuminate\Database\Eloquent\Builder $query */
                    $query->select([
                        'child_id',
                    ]);
                },
            ])
            ->select([
                'id',
                'url',
            ])
            ->chunk($chunk_count, function ($links) use (&$data, &$page, $chunk_count, $max_results) {
                /** @var Links $item */
                foreach ($links as $item) {
                    $data[] = [
                        'id' => $item->id,
                        'url' => $item->url,
                        'childs' => array_column($item->childs->toArray(), 'child_id'),
                    ];
                }

                $page += $chunk_count;

                if ($page > $max_results) {
                    return false;
                }

            });

        $time_end = microtime(true);

        $time = $time_end - $time_start;

        //echo 'Execution time : ' . round($time, 2) . ' seconds' . PHP_EOL;

        //echo 'Memory Usage: ' . round((memory_get_usage() / 1048576), 2) . ' MB ' . PHP_EOL;

        //dump($data);

        return view('front', compact('data'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'url' => 'required|url|min:10|max:255',
        ]);

        $site_url = rtrim($request->input('url'), '/');
        $parse_url = parse_url($site_url);

        $site = new Sites;

        if (!$site::where('url', '=', $site_url)->first()) {

            $site_model = $site::firstOrCreate(['url' => $site_url, 'domain' => $parse_url['host']]);

            $site_id = $site_model->id;

            $parse = new ParseHtml($site_url);

            $site_links = $parse->links();

            $this->saveSiteLinks($site_links, $site_id);

            foreach ($site_links as $link) {
                ParseLinksJob::dispatch($link, $site_id);
            }

        }

        return redirect()->back();

    }

    public function processing($id)
    {
        foreach (Links::notProcessed($id)->get(['url'])->toArray() as $item) {
            ParseLinksJob::dispatch($item['url'], $id);
        }

        return redirect()->back();
    }

    public function saveSiteLinks(array $links, int $site_id = 0)
    {
        $links_db = [];

        foreach ($links as $link) {

            $parse_link = parse_url($link);

            $links_db[] = [
                'url' => $link,
                'path' => $parse_link['path'] ?? '/',
                'site_id' => $site_id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        \DB::table('links')->insert($links_db);
    }
}
