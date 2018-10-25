<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ExchangeJson;
use App\Products;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\DashboardController as Dashboard;

class ExchangeController extends Controller
{
    public $cookieName;
    public $cookieID;
    public $csrf;
    public $date;

    //public $options = array();// Массив для передачи настроек сайта из таблицы options

    /*public function __construct()
    {
        $this->options = Dashboard::getOptions();
    }*/

    /**МЕТОД №1
     * Метод обработывает выгрузку xml файлов обмена
     * через 1С Предприятие
     * */
    public function exchange(Request $request)
    {


        $cookieName = config('session.cookie');
        $cookieID = Session::getId();
        //$cookieCsrf = 'csrf-token';
        $csrf = csrf_token();
        $date = date('Y-m-d H:m:s');

        if($request->get('type') == 'catalog')
        {
            switch ($request->mode)
            {
                case 'checkauth':
                    $user = $_SERVER['PHP_AUTH_USER'];
                    $pass = $_SERVER['PHP_AUTH_PW'];
                    if(Auth::attempt(['email' => $user, 'password' => $pass]))
                    {

                        return response("success\n$cookieName\n$cookieID\n$csrf\n$date")
                            ->header("Content-Type" ,"text/plane; charset=UTF-8")
                            ->header("csrf-token" , csrf_token());


                    };
                case 'init':

                    return response("(no\nfile_limit=100000000000\nsessid=$cookieID\n$csrf\nversion=3.1")
                        ->header("Content-Type" ,"text/plain; charset=UTF-8")
                        ->header("csrf-token" , csrf_token());

                case 'file':

                    //print "progress\n";
                    $file_name = $request->filename;
                    //return $file_name;
                    $file_content = file_get_contents('php://input');
                    Storage::put(date('Y-m-d') . '/' . $file_name, $file_content);
                    return("success\n");
                    //return redirect()->route('exchangePost');
                    //return $request->getContent();
                case 'import':

                    return "success\n";

            }
        }
        /*if($request->isMethod('post'))
        {
            return $request->getContent();
        }*/

        //return view('');
    }

    /**МЕТОД №2
     * Метод инициализации соединения с серером 1С
     * Обмен происходит с помощь JSON
     *
     */
    public function initJsonExchange(Request $request, Dashboard $dashboardController)
    {
        return view('dashboard.exchange1CJson')->with
        ([

            'options' => Dashboard::getOptions(),
        ]);
    }

    /**
     * Авторизация и получение данных для импорта
     * товаров из 1С
    */
    public function get1CGoodsJson (Request $request/*, Dashboard $dashboardController*/)
    {
        //$url = asset('1c-json.txt');
        $url = $request->url;
        //$url = 'http://192.168.0.101/1C/hs/1C/list/';
        $login = $request->login;
        $password = $request->password;

        $opts = array('http' =>
        array(
            'method'    => 'GET',
            'header'    => array ('Content-type: application/json', 'Authorization: Basic '.base64_encode("$login:$password")),

            )
        );

        $context = stream_context_create($opts);
        $json = file_get_contents($url, false, $context);

        $goods_for_import = json_decode($json, true);
        $db_goods = ExchangeJson::all();
        $i = 0;
        foreach ($db_goods as $good)
        {
            $arrDB = json_decode($good['data'], true);
            // Сравнение принятых данных с данными из базы данных
            // В случае совпадения данных удаляем массив из обрабодки
            $diff = array_diff_assoc($arrDB, $goods_for_import[$i]);
            if(!$diff)
            {
                unset($goods_for_import[$i]);
            }
            else
            {
                //В случае расхождения данных формируем массив для
                //обновления товара
                $goods_for_import[$i]['to_update'] = 1;
                $goods_for_import[$i]['id'] = $good['id'];
            }

            $i++;
        }
        //dd($goods_for_import);

        return view('dashboard.exchange1CJson')->with
        ([
            //'options' => $dashboardController->getOptions(),
            'options' => Dashboard::getOptions(),
            'goods_for_import' => $goods_for_import
        ]);
    }
    /**
     * Метод выполняет заполнение таблицы базы данных
     * новыми товарами из 1С
    */
    public function import1CGoodsJson (Request $request)
    {
        $new_goods = new ExchangeJson();
        $new_goods->in_showcase = false;
        $new_goods->data = json_encode($request->data, true);
        $new_goods->save();

        $product = new Products();
        $product->exchange_id = $new_goods->id;
        $product->custom_data = json_encode(array('images'=>array()), true);
        $product->save();

        return response()->json(array('status'=>'ok'), 200);
    }
    /**
     * Метод выполняет обновление товаров из 1С
     * новыми товарами из 1С
     */
    public function update1CGoodsJson (Request $request)
    {
       //return $request->all();
        $data = $request->data;

        $id =  $data['id'];
        unset($data['to_update']);
        unset($data['id']);

        $good_to_update = ExchangeJson::find($id);
        $good_to_update->data = json_encode($data, true);
        $good_to_update->save();

        return response()->json(array('status'=>'ok'), 200);

    }
}