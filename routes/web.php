<?php
use Illuminate\Support\Facades\Request;
use App\Pages;
//use Illuminate\Support\Facades\Session;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
$pages = Pages::select('url')->get();
foreach ($pages as $page)
{
    $page = $page['url'];
    Route::get("/$page", "Frontend\FrontendController@index");
}

Route::get('/', 'Frontend\FrontendController@index')->name('index');

Route::match(['get', 'post'], '/exchange', 'DashboardController@exchange')->name('exchange');
Route::match(['get', 'post'], '/1c_exchange.php', 'ExchangeController@exchange');
//Route::match(['get', 'post'], '/test', 'ExchangeController@test');



Route::match(['get', 'post'], '/test', 'ExchangeController@test');

Auth::routes();

Route::get('/dashboard', 'DashboardController@dashboard')->name('dashboard');

Route::match(['get', 'post'], '/dashboard/site-options', 'DashboardController@siteOptions')->name('siteOptions');

Route::match(['get', 'post'], '/dashboard/site-pages/{action?}/{id?}', 'DashboardController@sitePages')->name('sitePages');

Route::match(['get', 'post'], '/dashboard/site-blocks/{action?}/{id?}', 'DashboardController@siteBlocks')->name('siteBlocks');

Route::match(['get', 'post'], '/dashboard/site-menu/{action?}/{id?}', 'DashboardController@siteMenu')->name('siteMenu');

Route::get(/*['get', 'post'],*/ '/dashboard/1c-goods-json', 'ExchangeController@initJsonExchange')->name('initJsonExchange');
Route::post(/*['get', 'post'],*/ '/dashboard/1c-goods-json', 'ExchangeController@get1CGoodsJson')->name('get1CGoodsJson');

Route::match(['get', 'post'], '/dashboard/import-1c-goods-json', 'ExchangeController@import1CGoodsJson')->name('import1CGoodsJson');
Route::match(['get', 'post'], '/dashboard/update-1c-goods-json', 'ExchangeController@update1CGoodsJson')->name('update1CGoodsJson');

Route::get('/dashboard/products/{id?}', 'ProductController@getProductsList')->name('getProductsList');
Route::post('/dashboard/products/upd/', 'ProductController@updateProductCustomData')->name('updateProductCustomData');
//Route::match(['get', 'post'], '/dashboard/products/{id?}', 'ProductController@getProductsList')->name('getProductsList');
Route::post('/dashboard/to-showcase', 'ProductController@toShowcase')->name('toShowcase');

//Route::match(['get', 'post'], '/dashboard/products/{id}', 'ProductController@getProduct')->name('getProduct');




//Route::get('/home', 'HomeController@index')->name('home');
