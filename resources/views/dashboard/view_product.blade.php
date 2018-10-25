@extends('dashboard.layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-12 col-xs-12">
            @if (count($errors) > 0)
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @else
                {{--<ul class="alert alert-success">

                        <li>Data updated success</li>

                </ul>--}}
            @endif

        </div>
    </div>



    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ $exchange_data['Name'] }}</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="row">
                            @if(isset($product_images['images']) and !empty($product_images['images']))
                                <? //dump($product_images)?>
                                <div class="product-image">
                                    <img src="{{ asset('storage/' . $product_images['images'][0]) }}" alt="..." />
                                </div>
                                <div class="product_gallery">
                                    @foreach($product_images['images'] as $image)

                                        <a href="">
                                            <img src="{{ asset('storage/' . $image) }}" alt="..." />
                                        </a>
                                    @endforeach

                                </div>

                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                <form method="post" action="{{ route('updateProductCustomData') }}" enctype="multipart/form-data">
                                    @csrf
                                    <input name="files[]" id="files" type="file" multiple/>
                                    <input name="product_id" value="{{ $product_data['id'] }}" type="text" />
                                    <input name="action" value="add_images" type="text" />

                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <button type="submit" class="btn btn-success">Сохранить</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4 col-xs-12" style="border:0px solid #e5e5e5;">
                        <? //dump($exchange_data) ?>
                        <h4> Код товара: {{ $exchange_data['Kod'] }}</h4>
                        <h4> Расположение: {{ $exchange_data['StruktEdin'] }}</h4>
                        <h4> Категория: {{ $exchange_data['Kategoria'] }}</h4>
                        <h4> Количество: {{ $exchange_data['Kolichestvo'] }} шт.</h4>
                        {{--<h4> Цена: {{ $exchange_data['Sena'] }} KGS</h4>--}}
                        <div class="">
                            <div class="product_price">
                                <h1 class="price">{{ $exchange_data['Sena'] }} KGS</h1>
                                <span class="price-tax">Себестоимость: {{ $exchange_data['Sebest'] }} KGS</span>
                                <br>
                            </div>
                        </div>
                        <h4>Дополнительные свойства:</h4>
                            <? dump(json_decode($product_data['custom_data'], true))
                                //$custom_data = json_decode($product_data['custom_data'], true);
                            ?>
                            @if(count(json_decode($product_data['custom_data'], true)) > 1)

                                @foreach(json_decode($product_data['custom_data'], true) as $key => $value)
                                    @if($key != 'images')
                                        <div class="row">
                                            <div class="">
                                                <h4>{{ $key }}:</h4>
                                                <input type="text" id="key" name="key" value="{{ $value }}" required="required" class="form-control ">
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                               {{-- {{ 1 }}--}}

                            @endif
                            <div class="row">
                                <a class="btn" data-toggle="modal" data-target=".bs-example-modal-lg">
                                    <span class="fa fa-plus-square" style="font-size: 32px"></span>
                                </a>
                            </div>
                            <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                            </button>
                                            <h4>Новое свойство:</h4>
                                        </div>
                                        <div class="modal-body">

                                            <form id="myform" method="post" action="{{ route('updateProductCustomData') }}" class="form-horizontal form-label-left">
                                                @csrf
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="key">Свойство: <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" id="key" name="key" required="required" class="form-control col-md-7 col-xs-12">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="value">Значение: <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" id="value" name="value" required="required" class="form-control col-md-7 col-xs-12">
                                                        <input type="text" name="action" value="add_custom_data" required="required" class="form-control col-md-7 col-xs-12">
                                                        <input type="text" name="product_id" value="{{ $product_data['id'] }}" required="required" class="form-control col-md-7 col-xs-12">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <a  class="btn btn-default" data-dismiss="modal">Отмена</a>
                                                    <button id="saveprop" class="btn btn-primary" type="submit" >Сохранить</button>
                                                </div>
                                            </form>
                                        </div>
                                        {{--<script>
                                           function sendData() {
                                              var data = $('#myform').serializeArray();
                                              console.log(data.key);
                                           }
                                        </script>--}}


                                    </div>
                                </div>
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
