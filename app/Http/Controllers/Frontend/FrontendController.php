<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController as Dashboard;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class FrontendController extends Controller
{

    protected $url;
    protected $options = array();
    protected $blocks = array();
    protected $home_page = array();
    protected $home_page_blocks = array();


    public function __construct()
    {
        //$this->urls = Dashboard::getPageUrls();
        $this->options = Dashboard::getOptions();
        $this->blocks = Dashboard::getBlocks();
        $this->home_page = Dashboard::getHomePage();
        $this->home_page_blocks = Dashboard::getHomePageBlocks();


    }

    public function index(Request $request)
    {

        //dump($request->get('page'));
        $url = $request->path();
        if($url != '/')
        {
            $page = Dashboard::getPageByUrl($url);
            $page_blocks = Dashboard::getPageBlocks($page->id);
            //dd($page->id);
            return view('frontend.index')->with
                                                    ([
                                                        'page' => $page,
                                                        'options' => $this->options,
                                                        'page_blocks' => $page_blocks,
                                                    ]);
        }

        return view('frontend.index')->with
                                                ([
                                                    'options' => $this->options,
                                                    'page' => $this->home_page,
                                                    'page_blocks' => $this->home_page_blocks
                                                ]);

    }

    public function pages(Request $request)
    {
        dump($request);
    }
}
