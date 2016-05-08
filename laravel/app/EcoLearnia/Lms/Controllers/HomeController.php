<?php
namespace App\EcoLearnia\Lms\Controllers;

use Illuminate\Http\Request;

use App\Ecofy\Support\EcoCriteriaBuilder;
use App\EcoLearnia\Modules\Content\ContentService;

use App\Http\Controllers\Controller;

class HomeController extends Controller
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
    public function portal()
    {
        // Query for content with: publishStatus > 8 AND assignable == true
        $criteria = EcoCriteriaBuilder::conj([
            EcoCriteriaBuilder::comparison('publishStatus', '>', 8),
            EcoCriteriaBuilder::equals('assignable', true)
        ]);
        $contentList = $this->getContentService()->query($criteria);
        return view('portal', ['assignments' => $contentList]);
    }

    public function dashboard()
    {
        return view('dashboard', ['name' => 'Young']);
    }

    public function assignment(Request $request)
    {
        $breadcrumb = [

        ];
        $params = [
            'outsetNode' => $request['outsetNode']
        ];
        return view('assignment', ['params' => $params]);
    }

    public function page($pageName)
    {
        return view($pageName, ['name' => 'Young']);
    }
}
