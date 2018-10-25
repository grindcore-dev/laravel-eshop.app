<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 16.10.2018
 * Time: 11:54
 */

namespace App\Http\Controllers\Traits;


use App\Options;
use App\PageRoles;
use App\Pages;
use App\Blocks;
use App\Menus;


trait Main
{
    /**
     * Получаем настройки сайта из таблицы Options
     * Возвращает массив настроек сайта $options
     *
     */
    public static function getOptions ()
    {
        $data = Options::all();
        $options = array();

        foreach ($data as $option => $value) {

            $options += array("$value[option_name]" => "$value[option_value]");
        }
        return $options;
    }
    /**
     * Получаем массив из таблицы Pages
     * Возвращает массив  $pages
     */
    public static function getPageUrls()
    {
        $urls = Pages::select(['url'])->get();
        return $urls;
    }
    public static function getPages()
    {
        $pages = Pages::withTrashed()->get();
        return $pages;
    }
    public static function getPageByUrl($url)
    {
        $page = Pages::where('url', $url)->first();
        return $page;
    }

    public static function getPage($id)
    {
        $page = Pages::withTrashed()->find($id);
        //$page = Pages::where('id', $id)->withTrashed()->get();
        return $page;
    }

    public static function getHomePage()
    {
        $home_page = Pages::where('page_roles', 'home')->first();
        return $home_page;
    }
    public static function getHomePageBlocks()
    {
        $blocks = self::getHomePage()->blocks;
        return json_decode($blocks,true);
    }


    public static function getPageRole($page_id)
    {
        $page = self::getPage($page_id);
        $role_id = $page->page_roles;
        $role = self::getRole($role_id);
        return $role;
    }
    public static function getRoles()
    {
        $roles = PageRoles::all();
        return $roles;
    }
    public static function getRole($id)
    {
        $role = PageRoles::find($id);
        return $role;
    }

    public static function getBlocks()
    {
        $blocks = Blocks::all();
        return $blocks;
    }
    public static function getPageBlocks($id)
    {
        $blocks = self::getPage($id)->blocks;
        return json_decode($blocks,true);
    }
    public static function getMenus()
    {
        $menus = Menus::all();
        return $menus;
    }
    public static function getMenu($id)
    {
        $menu = Menus::find($id);
        return json_decode($menu->content,true);
    }
    public static function getName()
    {
        return "Vasia";
    }
}