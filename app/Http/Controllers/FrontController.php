<?php

namespace App\Http\Controllers;

use App\Jobs\ParseLinksJob;
use App\Model\ParseSites;
use App\Model\SiteLinks;
use function foo\func;
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

        $site_id          = ParseSites::firstOrCreate(['url' => $site_url])->toArray()['id'];
        $site_links       = SiteLinks::where('site_id', $site_id)->get(['url']);
        $site_links_array = [];

        if ($site_links->count()) {
            $site_links_array = array_map(function ($item) {
                return $item['url'];
            }, $site_links->toArray());
        };

        $html = new Htmldom($site_url);

        $links = [];

        foreach ($html->find('a') as $link) {

            /*$parse_link = parse_url($link->href);

            $link_host = isset($parse_link['host']) ? $parse_link['host'] : false;

            if ($link_host && $site_host === $link_host && isset($parse_link['path']) && !in_array($link->href, $links, true)) {

                //dump(trim($link->innertext));
                //dump(trim($link->plaintext));

                //$links[] = $link->href;
                $links[] = $link->href;
            }*/

            if ( ! in_array($link->href, $site_links_array)) {
                $links[] = $link->href;
            }


        }

        //dump($links);
        //dd($site_links->toArray());


        $links = $this->filterUnique($links);
        $links = $this->filterExcludeExternalLinks($links, $site_host);

        //dd($links);

        $links_db = [];

        foreach ($links as $link) {

            $parse_link = parse_url($link);

            $link_host = $parse_link['host'] ?? false;

            /*if($link_host) {
                dump($parse_link);
            }*/

            $is_external = $site_host !== $link_host;

            $links_db[] = [
                'url'        => $link,
                'path'       => $parse_link['path'] ?? '/',
                'site_id'    => $site_id,
                'external'   => $is_external,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        //dump($links_db);

        \DB::table('site_links')->insert($links_db);

        //dump($request->all());
        //dump($html);

        //dd($links);

        //dispatch(new ParseLinksJob())->onQueue('parse');

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
