@extends('dashboard.layouts.main')

@section('content')
    <? //dd($name)?>
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
    @if(isset($goods_for_import))
        <? //dump($goods_for_import)?>
        <div class="row">
            <div class="col-md-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Товары для импорта </h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row">

                                <div class="table-responsive">
                                    <table class="table table-striped jambo_table">
                                        <thead>
                                            <tr class="headings">
                                                <th class="column-title">#Код товара</th>
                                                <th class="column-title">Наиманование товара</th>
                                                <th class="column-title">Склад</th>
                                                <th class="column-title">Категория товара </th>
                                                <th class="column-title">Количество</th>
                                                <th class="column-title">Себестоимость</th>
                                                <th class="column-title">Цена</th>
                                                <th class="column-title no-link last"><span class="nobr">Действие</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($goods_for_import as $item)
                                                <? //dump($item)?>
                                                <tr id="code_{{ $item['Kod'] }}">
                                                    <div>
                                                    <td>{{ $item['Kod'] }}</td>
                                                    <td>{{ $item['Name'] }}</td>
                                                    <td>{{ $item['StruktEdin'] }}</td>
                                                    <td>{{ $item['Kategoria'] }}</td>
                                                    <td>{{ $item['Kolichestvo'] }}</td>
                                                    <td>{{ $item['Sebest'] }}</td>
                                                    <td>{{ $item['Sena'] }}</td>
                                                        @if(isset($item['to_update']) and $item['to_update'] == 1)
                                                            <? //dump($item['id'])?>
                                                            <td>
                                                                <a href="#"
                                                                   {{--data-id="{{ $item['id'] }}"--}}
                                                                   data-code="{{ $item['Kod'] }}"
                                                                   data-content="{{json_encode($item, true)}}"
                                                                   onclick="javascript:updateElement(this)">Обновить
                                                                </a>
                                                            </td>
                                                        @else
                                                            <td><a href="#"
                                                                   data-code="{{ $item['Kod'] }}"
                                                                   data-content="{{json_encode($item, true)}}"
                                                                   onclick="javascript:importElement(this)">Импортировать
                                                                </a>
                                                            </td>
                                                        @endif

                                                    </div>
                                                </tr>

                                            @endforeach

                                        </tbody>
                                    </table>
                                    <script>
                                        function importElement(element) {
                                            var data = $(element).data('content');
                                            //alert(data);
                                            var code = $(element).data('code');
                                            $.ajax({
                                                type:'POST',
                                                url:"{{ route('import1CGoodsJson') }}",
                                                data:{
                                                    data : data,
                                                    _token: '{{ csrf_token() }}'
                                                },
                                                dataType: 'json',
                                                success:function(data){
                                                    console.log(data);
                                                    if(data.status == 'ok')
                                                    {
                                                        $('#code_' + code).hide(1000, function() {  $(this).remove()});
                                                    }
                                                }

                                            });
                                        }
                                        function updateElement(element) {
                                           /* var id = $(element).data('id');*/
                                            var data = $(element).data('content');
                                            var code = $(element).data('code');
                                            $.ajax({
                                                type:'POST',
                                                url:"{{ route('update1CGoodsJson') }}",
                                                data:{
                                                    /*id : id,*/
                                                    data : data,
                                                    _token: '{{ csrf_token() }}'
                                                },
                                                dataType: 'json',
                                                success:function(data){
                                                    console.log(data);
                                                    if(data.status == 'ok')
                                                    {
                                                        $('#code_' + code).hide(1000, function() {  $(this).remove()});
                                                    }
                                                }

                                            });
                                        }
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Инициализация в 1С</h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <form class="form-horizontal form-label-left input_mask" method="post" action="{{ route('get1CGoodsJson') }}">
                                @csrf
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="url">Адрес сайта: <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input name="url" type="text" id="url" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="login">Логин: <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input name="login" type="text" id="login" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Пароль: <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input name="password" type="password" id="password" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                </div>



                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                        <button type="submit" class="btn btn-success">Соединиться</button>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection