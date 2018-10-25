<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Options;
use App\Pages;
use App\Showcase;
use App\Menus;



use App\Http\Controllers\Traits\Main;



class DashboardController extends Controller
{
    public $error_messages = [
                                'unique' => ':attribute'
                            ];

    public $options = array();// Массив для передачи массива настроек сайта из таблицы options
    public $pages = array();// Массив для передачи массива страниц сайта из таблицы pages
    public $menus = array();// Массив для передачи  массива меню сайта из таблицы menus


    use Main;


    public function __construct()
    {
        $this->middleware('auth');
        $this->options = Main::getOptions();
        $this->pages = Main::getPages();
        $this->menus = Main::getMenus();
    }

    /**
     * Отображение "Главной" страницы админ панели
     *
     */
    public function dashboard()
    {
        return view('dashboard.dashboard')->with([
                                                        //'options' => $this->getOptions()
                                                        'options' => $this->options
                                                       ]);
    }

    /**
     * Отображение страницы "Настройки сайта"
     * и обновление настроек в таблице options
     *
     */
    protected function siteOptions (Request $request)
    {
        /**
         * GET
         */

        if($request->isMethod('get'))
        {
            return view('dashboard.site-options')->with([
                                                                //'options' => $this->getOptions(),
                                                                'options' => $this->options,
                                                            ]);
        }
        /**
         * POST
         */

        if($request->isMethod('post'))
        {
            $data = $request->except('_token');
            $messages = array(); // Массив кастомных сообщений

            $dataValidation = Validator::make($request->all(),

                [
                    'site_title' => 'required|string|max:100',
                    'meta_charset' => 'required|string|max:25',
                    'meta_description' => 'required|string|max:255',
                    'meta_keywords' => 'required|string|max:255',
                    'site_icon' => 'image|mimes:jpeg,png,jpg,gif,svg|max:512',
                ],
                $messages
            );
            if (!$dataValidation->fails()) {
                foreach ($data as $option_name => $option_value) {

                    if ($option_name == 'site_icon') {
                        $options = Options::where('option_name', 'site_icon')->first();
                        $file_name = $request->file('site_icon')->getClientOriginalName();
                        $path = $request->file('site_icon')->storeAs('/site', $file_name);
                        $options->option_value = $path;
                        $options->save();

                    } else {
                        $options = Options::where('option_name', $option_name)->first();
                        $options->option_value = $option_value;
                        $options->save();
                    }

                }

                return redirect()->route('siteOptions');
            }
        }


    }

    /**
     * Отображение страницы "Меню"
     * Добавление, удаление, обновление пунктов меню на сайте
     *
     */
    protected function siteMenu(Request $request, $action = '', $id = '')
    {
        /**
         * GET
         */
        if($request->isMethod('get'))
        {
            switch ($action) {
                case "list":
                    //dd($action);
                    if($id)
                    {
                        $menu = Main::getMenu($id);
                        //dd($menu);
                        return view('dashboard.site-menu')->with([
                                                                        //'options' => $this->getOptions(),
                                                                        'options' => $this->options,
                                                                        'pages' => $this->pages,
                                                                        'menus' => $this->menus,
                                                                        'selected_menu' => $menu,
                                                                        'action' => $action,
                                                                    ]);
                    }
                    return view('dashboard.site-menu')->with([
                                                                    //'options' => $this->getOptions(),
                                                                    'options' => $this->options,
                                                                    'pages' => $this->pages,
                                                                    'menus' => $this->menus,
                                                                    'action' => $action,
                                                                    ]);
                case "add":
                    //dd($action);
                    return view('dashboard.site-menu')->with([
                                                                    //'options' => $this->getOptions(),
                                                                    'options' => $this->options,
                                                                    'menus' => $this->menus,
                                                                    'action' => $action,
                                                                    ]);
            }
        }

        /**
         * POST
         */
        if($request->isMethod('post'))
        {
            $action = $request->action;

            if($action == md5('@add@'))
            {
                $data = $request->except('_token');
                $messages = array();
                $dataValidation = Validator::make($data,

                    [
                        'name' => 'required|string|max:100',
                    ],
                    $messages
                );
                if (!$dataValidation->fails())
                {
                    $menu = new Menus();
                    $menu->name = $data['name'];
                    $menu->save();
                    return redirect()->route('siteMenu', ['list']);
                }
                return redirect()->back()->withErrors($dataValidation)->withInput();
            }
            if($action == md5('@edit@'))
            {
                $data = $request->except('_token');
                //dd($data);
                $messages = array();
                $dataValidation = Validator::make($data,

                    [
                        'menu_id' => 'required|integer|max:11',
                    ],
                    $messages
                );
                if (!$dataValidation->fails())
                {
                    $menu = Menus::find($data['menu_id']);
                    if($menu)
                    {
                        return redirect()->route('siteMenu', ['list', $menu->id])/*->with(['selected_menu' => $menu])*/;
                    }

                    return redirect()->back()->withErrors($dataValidation)->withInput();

                }
                return redirect()->back()->withErrors($dataValidation)->withInput();
            }
        }

    }


    /**
     * Отображение страницы "Страницы"
     * Добавление, удаление, обновление страниц на сайте
     *
     */
    protected function sitePages (Request $request, $action = '', $id = '')
    {
        /**
         * GET
         */
        if($request->isMethod('get'))
        {
            switch ($action)
            {
                case "list":
                    //dd($action);
                    return view('dashboard.site-pages')->with([
                                                                        //'options' => $this->getOptions(),
                                                                        'options' => $this->options,
                                                                        'pages' => $this->pages,
                                                                        'action' => $action,
                                                                    ]);
                case "add":
                    //dd($action);
                    return view('dashboard.site-pages')->with([
                                                                        //'options' => $this->getOptions(),
                                                                        'options' => $this->options,
                                                                        'action' => $action,
                                                                    ]);
                case "edit":
                    //dd($action);
                    $page = Main::getPage($id);
                    $roles = Main::getRoles();
                    $blocks = Main::getBlocks();
                    $page_blocks = Main::getPageBlocks($id);
                    return view('dashboard.site-pages')->with([
                                                                        //'options' => $this->getOptions(),
                                                                        'options' => $this->options,
                                                                        'action' => $action,
                                                                        'page' => $page,
                                                                        'roles' => $roles,
                                                                        'blocks' => $blocks,
                                                                        'page_blocks' => $page_blocks,
                                                                    ]);
                case "draft":
                    //dd($action);
                    $page = Main::getPage($id);
                    $page->status = 'draft';
                    $page->delete();
                    $page->save();
                    return redirect()->back()->with([
                                                        //'options' => $this->getOptions(),
                                                        'options' => $this->options,
                                                        'pages' => $this->pages,
                                                    ]);
                case "restore":
                    //dd($action);
                    $page = Main::getPage($id);
                    $page->restore();
                    return redirect()->back()->with([
                                                        //'options' => $this->getOptions(),
                                                        'options' => $this->options,
                                                        'pages' => $this->pages,
                                                    ]);
                case  "delete":

                    $page = Main::getPage($id);
                    $blocks = json_decode($page->blocks, true);
                    $key = array_search($request->block, $blocks);
                    unset($blocks[$key]);
                    $page->blocks = json_encode($blocks, true);
                    $page->save();
                    return redirect()->back()->with([
                                                        //'options' => $this->getOptions(),
                                                        'options' => $this->options,
                                                        'pages' => $this->pages,
                                                    ]);

            }
            return view('dashboard.site-pages')->with([
                                                            //'options' => $this->getOptions(),
                                                            'options' => $this->options,
                                                            'pages' => $this->pages,
            ]);
        }
        /**
         * POST
         */
        if($request->isMethod('post'))
        {
            $action = $request->action;
            //dd($action);
            if($action == md5('@add@'))
            {
                $data = $request->except('_token');
                //dd($data);

                $dataValidation = Validator::make($data,

                    [
                        'title' => 'required|string|max:100',
                        'url' => 'required|string|max:50|unique:pages,url',
                    ],
                    $this->error_messages
                );
                if (!$dataValidation->fails())
                {
                    $page = new Pages();
                    $page->title = $data['title'];
                    $page->url = $data['url'];
                    $page->page_roles = 'page';
                    $page->status = 'draft';
                    $page->blocks = json_encode(["hero"]);
                    $page->deleted_at = date('Y-m-d H:m:s');
                    $page->save();
                    //dd($page);
                    return redirect()->route('sitePages', ['edit', $page->id]);
                }
                return redirect()->back()->withErrors($dataValidation)->withInput();
            }

            if($action == md5('@publish@'))
            {
                $data = $request->except('_token');
                //dd($data);
                $messages = array(); // Массив кастомных сообщений

                $dataValidation = Validator::make($data,

                    [
                        'title' => 'required|string|max:100',
                        'url' => 'required|string|max:50',
                                 Rule::unique('pages')->ignore($data['page_id']),
                        'page_roles' => 'required|string|max:50',
                        'page_id' => 'required|integer|max:11',
                    ],
                    $messages
                );
                if (!$dataValidation->fails())
                {
                    $page = Main::getPage($data['page_id']);
                    $curr_page_role = $page->page_roles;
                    if($curr_page_role != $data['page_roles'])
                    {
                        $old_page = Pages::where('page_roles', $data['page_roles'])->first();
                        if($old_page)
                        {
                            $old_page->page_roles = 'page';
                            $old_page->save();
                        }

                        $page->page_roles = $data['page_roles'];
                    }

                    $page->title = $data['title'];
                    $page->url = $data['url'];
                    $page->status = 'publish';
                    $page->restore();
                    $page->save();
                    return redirect()->back();

                }
                return redirect()->back()->withErrors($dataValidation)->withInput();
            }

            if($action == md5('@save_block@'))
            {
                $data = $request->except('_token');
                //dd($data);
                $page = Main::getPage($data['page_id']);
                $blocks = json_decode($page->blocks, true);
                //dump(count($blocks));
                array_push($blocks,  $data['blocks']);
                $page->blocks = json_encode($blocks, true);
                $page->save();
                return redirect()->back();
            }
            if($action == md5('@delete_block@'))
            {
                $data = $request->except('_token');
                dd($data);
                $page = Main::getPage($data['page_id']);
                $blocks = json_decode($page->blocks, true);
                //dump(count($blocks));
                array_push($blocks,  $data['blocks']);
                $page->blocks = json_encode($blocks, true);
                $page->save();
                return redirect()->back();
            }
        }

    }

    protected function siteBlocks (Request $request, $actions = '', $id = '')
    {
        return 'siteBlocks';
    }



}