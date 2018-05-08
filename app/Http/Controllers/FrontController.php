<?php

namespace App\Http\Controllers;

use App\Jobs\ParseLinksJob;
use App\Model\ParseSites;
use App\Model\SiteLinks;
use Illuminate\Http\Request;
use Yangqi\Htmldom\Htmldom;

class FrontController extends Controller
{
    public function index()
    {
        return view('front');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'url' => 'required|url|min:10|max:255',
        ]);

        $site_url  = $request->input('url');
        $site_host = parse_url($site_url)['host'];

        $site_model = ParseSites::firstOrCreate(['url' => $site_url]);
        $site_id    = $site_model->toArray()['id'];

        $links_model  = SiteLinks::notProcessed($site_id);
        $links_object = $links_model->get(['url']);
        $links_array  = [];

        if ($links_object->count()) {
            $links_array = array_map(function ($item) {
                return $item['url'];
            }, $links_object->toArray());
        };

        $html = new Htmldom($site_url);

        $html_links = [];

        foreach ($html->find('a') as $link) {

            if ( ! in_array($link->href, $links_array)) {
                $html_links[] = $link->href;
            }

        }

        $html_links = $this->filterUnique($html_links);
        $html_links = $this->filterExcludeExternalLinks($html_links, $site_host);

        $links_db = [];

        foreach ($html_links as $link) {

            $parse_link = parse_url($link);

            //$link_host = $parse_link['host'] ?? false;

            //$is_external = $site_host !== $link_host;

            $links_db[] = [
                'url'        => $link,
                'path'       => $parse_link['path'] ?? '/',
                'site_id'    => $site_id,
                //'external'   => $is_external,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        //dump($links_db);

        \DB::table('site_links')->insert($links_db);

        /*foreach (SiteLinks::notProcessed($site_id)->get(['url']) as $item) {
            dispatch(new ParseLinksJob($item->url, $site_id))->onQueue('parse');
        }*/

        return redirect()->back();

    }

    public function filterUnique(array $arr): array
    {
        return array_unique($arr);
    }

    public function filterOnlyExternalLinks(array $arr, string $site_host): array
    {
        return array_filter($arr, function ($value) use ($site_host) {

            $link_host = parse_url($value)['host'] ?? false;

            return $link_host !== $site_host;

        }, ARRAY_FILTER_USE_BOTH);
    }

    public function filterExcludeExternalLinks(array $arr, string $site_host): array
    {
        return array_filter($arr, function ($value) use ($site_host) {

            $link_host = parse_url($value)['host'] ?? false;

            return $link_host === $site_host;

        }, ARRAY_FILTER_USE_BOTH);
    }
}
