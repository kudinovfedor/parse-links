<?php

namespace App\Http\Controllers;

use App\Jobs\ParseLinksJob;
use App\Logic\Parse\ParseHtml;
use App\Model\Sites;
use App\Model\Links;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function filterExcludeQuery(array $arr): array
    {
        return array_filter($arr, function ($value) {

            $query = parse_url($value['url'], PHP_URL_QUERY);

            if($query) {
                 dump($query);
                 dump($query === null);
                 //dd(0);
             }

            return $query === null;

        }, ARRAY_FILTER_USE_BOTH);
    }

    public function index()
    {
        dd($this->filterExcludeQuery(Links::all()->toArray()));
        return view('front');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'url' => 'required|url|min:10|max:255',
        ]);

        $site_url = $request->input('url');
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
