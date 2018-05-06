<?php

namespace App\Http\Controllers;

use App\Model\ParseSites;
use Carbon\Carbon;
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
            'url' => 'required|url|min:7|max:255',
        ]);

        $site_url = $request->input('url');
        $site_host = parse_url($site_url)['host'];

        dump(ParseSites::firstOrCreate(['url' => $site_url]));

        $site_id = ParseSites::where('url', $site_url)->first()->toArray()['id'];

        $html = new Htmldom($site_url);

        $links = [];

        foreach ($html->find('a') as $link) {

            if (!in_array($link->href, $links, true)) {

                //dump(trim($link->innertext));
                //dump(trim($link->plaintext));

                $links[] = $link->href;
            }
        }

        $links_db = [];

        foreach ($links as $link) {

            $link_host = isset(parse_url($link)['host']) ? parse_url($link)['host'] : false;

            $is_external = $site_host !== $link_host;

            $links_db[] = [
                'url' => $link,
                'site_id' => $site_id,
                'external' => $is_external,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        dump($links_db);

        \DB::table('site_links')->insert($links_db);

        //dump($request->all());
        //dump($html);

        dd($links);

        return redirect()->back();

    }
}
