<?php

namespace App\Http\Controllers;

use App\Model\Sites;
use Illuminate\Http\Request;

class SitesController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // TODO: Select what selection is used when many sites displayed?
        $sites = Sites::all()->sortBy('domain');
        //$sites = Sites::with('links')->get()->sortBy('domain');

        return view('sites.index', compact('sites'));
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {

        $site = Sites::find($id);

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

        Sites::find($id)->delete();

        return redirect()->back();
    }
}
