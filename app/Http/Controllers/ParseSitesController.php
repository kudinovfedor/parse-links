<?php

namespace App\Http\Controllers;

use App\Model\ParseSites;
use Illuminate\Http\Request;

class ParseSitesController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $sites = ParseSites::all()->sortBy('domain');

        return view('sites.index', compact('sites'));
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {

        $site = ParseSites::find($id);

        return view('sites.show', compact('site'));
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {

        ParseSites::find($id)->delete();

        return redirect()->back();
    }
}
