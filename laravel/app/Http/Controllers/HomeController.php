<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function showHome()
    {
        return view('home', ['name' => 'Young']);
    }

    public function login()
    {
        return view('login', ['name' => 'Young']);
    }

    public function page($pageName, Request $request)
    {
        $params = $request->all();
        return view($pageName, ['params' => $params]);
    }
}
