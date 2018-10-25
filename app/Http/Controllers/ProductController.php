<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\DashboardController as Dashboard;
use App\ExchangeJson;
use App\Products;




class ProductController extends Controller
{
    //Массив настроек из таблицы Options
    public $options = array();

    /*public function __construct(Dashboard $dashboard)
    {
        $this->options = $dashboard->getOptions();
    }*/
    /**
     * Метод выбирает из таблицы Exchange_json все или один товар по идентификатору
     * и из таблицы Products характиристики отдельного товара
    */
    public function getProductsList (Request $request, $id = "")
    {
        /**
         * GET
         */
        // Если присутсвует идентификатор товара
        // происходит выборка по двум таблицам Exchange_json и Products
        if($request->isMethod('get'))
        {
            if(isset($id) and $id != '')
            {
                $exchange_data = ExchangeJson::find($id);
                if($exchange_data)
                {
                    $product_data = Products::where('exchange_id', $id)->first();
                    if($product_data)
                    {
                        return view('dashboard.view_product')->with([
                            //'options' => $this->options,
                            'options' => Dashboard::getOptions(),
                            'exchange_data' => json_decode($exchange_data['data'], true),
                            'product_data' => $product_data,
                            'product_images' => json_decode($product_data['custom_data'], true),
                        ]);
                    }
                }
                else
                {
                    return redirect()->back();
                }

            }
            else
            {
                $exchanges = ExchangeJson::all();
                $exchanges_list = array();
                foreach($exchanges as $exchange_arr)
                {
                    $product = Products::find($exchange_arr['id']);
                    if($product->in_showcase != 1)
                    {
                        $exchange_arr['data'] = json_decode($exchange_arr['data'], true);
                        array_push($exchanges_list, $exchange_arr);
                    }

                }

                return view('dashboard.products')->with([
                    //'options' => $this->options,
                    'options' => Dashboard::getOptions(),
                    'exchanges_list' => $exchanges_list,
                ]);
            }


        }

    }
    /**
     * Метод изменяет значение столбца in_showcase в таблице Exchange_json
    */
    public function toShowcase(Request $request)
    {
        if($request->action == 'to_showcase')
        {
            $id = $request->id;
            $exchange = ExchangeJson::where('id', $id)->first();
            $exchange->in_showcase = 1;
            $exchange->save();
            return response()->json(array('status'=>'ok', 'exchange_id' => $exchange->id, 'in_showcase' => $exchange->in_showcase), 200);
        }
        if($request->action == 'delete_from_showcase')
        {
            $id = $request->id;
            $exchange = ExchangeJson::where('id', $id)->first();
            $exchange->in_showcase = 0;
            $exchange->save();
            return response()->json(array('status'=>'ok', 'exchange_id' => $exchange->id, 'in_showcase' => $exchange->in_showcase), 200);
        }
    }
    /**
     * Метод выполняет обновление данных в столбце custom_data таблицы Products
     */
    public function updateProductCustomData(Request $request)
    {
        $action = $request->action;
        $product_id = $request->product_id;

        //Обновление парамемтра images в массиве данных
        if($action == 'add_images')
        {
            if($request->hasFile('files'))
            {
                $files = $request->file('files');

                $messages = array(); // Массив кастомных сообщений

                $dataValidation = Validator::make($request->all(),

                    [
                        'files' => 'array',
                        'files.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:512',
                    ],
                    $messages
                );
                if (!$dataValidation->fails())
                {
                    $product = Products::find($product_id);
                    $images = json_decode($product['custom_data'], true);
                    //dd($images['images']);
                    $i = 0;
                    foreach ($files as $file )
                    {
                        $file_name = $file->getClientOriginalName();
                        $path = $files[$i]->storeAs('/site/products/' . $product_id, $file_name);
                        //dump($path);
                        array_push($images['images'], $path);
                        $i ++;
                    }
                    $product->custom_data = json_encode($images, true);
                    //dd($product->custom_data);
                    $product->save();
                    return redirect()->back();
                }
                return redirect()->route('updateProductCustomData')->withErrors($dataValidation)->withInput();
            }
        }
        //Добавление новых параметров массив данных
        if($action == 'add_custom_data')
        {
            $product = Products::find($product_id);
            $product_custom_data = json_decode($product['custom_data'], true);
            $product_custom_data[$request->key] = $request->value;
            $product_custom_data = json_encode($product_custom_data, true);
            $product->custom_data = $product_custom_data;
            $product->save();
            return redirect()->back();
        }
    }
}
