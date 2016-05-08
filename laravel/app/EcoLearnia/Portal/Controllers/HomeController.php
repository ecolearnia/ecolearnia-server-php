<?php
namespace App\EcoLearnia\Modules\Lms\Controllers;

use App\EcoLearnia\Modules\Content\ContentService;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{

    /**
     * Show the public landing page.
     *
     * @param  int  $id
     * @return Response
     */
    public function public()
    {
        $contentList =
        return view('public', $data);
    }

    public function login()
    {
        return view('login', ['name' => 'Young']);
    }

    public function page($pageName)
    {
        return view($pageName, ['name' => 'Young']);
    }
}
