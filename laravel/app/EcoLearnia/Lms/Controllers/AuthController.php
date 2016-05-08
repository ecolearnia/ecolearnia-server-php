<?php
namespace App\EcoLearnia\Lms\Controllers;

use Illuminate\Http\Request;

use App\Ecofy\Support\EcoCriteriaBuilder;
use App\EcoLearnia\Modules\Content\ContentService;

use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    private $contentService;
    public function getContentService()
    {
        if ( $this->contentService == null) {
            $this->contentService = new ContentService();
        }
        return $this->contentService;
    }

    /**
     * Show the public landing page.
     *
     * @param  int  $id
     * @return Response
     */
    public function signin()
    {
        return view('signin');
    }

    public function signup()
    {
        return view('signup');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function signout(Request $request)
    {
        $redirUrl = !empty($request['redir_url']) ? $request['redir_url'] : '/portal' ;

        $minutes = -1;
        $secure = false;
        $httpOnly = false;
        $path = '/';
        $domain = null;
        return redirect($redirUrl)
            ->withCookie('ecofy_token', null
                , $minutes , $path, $domain, $secure, $httpOnly);
    }

    public function profile($pageName)
    {
        return view($pageName, ['name' => 'Young']);
    }
}
