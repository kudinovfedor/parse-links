<?php

namespace App\Http\Controllers;

use App\Model\ParseSites;
use App\Model\SiteLinks;
use Illuminate\Http\Request;

class ParseSitesController extends Controller
{
    public function index() {
        $sites = ParseSites::all();

        return view('sites.index', compact('sites'));
    }

    public function show($id) {
        $site = ParseSites::find($id);
        $links = SiteLinks::where('site_id', $id)->get();

        return view('sites.show', compact('site', 'links'));
    }

    public function destroy($id) {
        //dd($id, 'test');

        $site = ParseSites::findOrFail($id);
        $site->delete();

        return redirect()->back();
    }
}
