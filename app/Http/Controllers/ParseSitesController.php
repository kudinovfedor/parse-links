<?php

namespace App\Http\Controllers;

use App\Model\ParseSites;
use App\Model\SiteLinks;
use Illuminate\Http\Request;

class ParseSitesController extends Controller
{
    public function index()
    {
        $sites = ParseSites::all();

        return view('sites.index', compact('sites'));
    }

    public function show($id)
    {

        $site = ParseSites::findOrFail($id);

        return view('sites.show', compact('site'));
    }

    public function destroy($id)
    {

        ParseSites::findOrFail($id)->delete();

        return redirect()->back();
    }
}
