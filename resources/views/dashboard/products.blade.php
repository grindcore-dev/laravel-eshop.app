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
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Товары</h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <? dump($exchanges_list)?>
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
                                    @foreach($exchanges_list as $item)

                                        <tr id="">
                                            <div>
                                                <td>{{ $item['data']['Kod'] }}</td>
                                                <td>{{ $item['data']['Name'] }}</td>
                                                <td>{{ $item['data']['StruktEdin'] }}</td>
                                                <td>{{ $item['data']['Kategoria'] }}</td>
                                                <td>{{ $item['data']['Kolichestvo'] }}</td>
                                                <td>{{ $item['data']['Sebest'] }}</td>
                                                <td>{{ $item['data']['Sena'] }}</td>
                                                <td id="in_showcase_{{ $item['id'] }}">
                                                    @if($item['in_showcase'] == 0)
                                                        <a class="btn btn-xs btn-info" href="{{ route('getProductsList', $item['id']) }}">Просмотр</a>
                                                        <a id="product_id_1_{{ $item['id'] }}" href="#"
                                                            data-id="{{ $item['id'] }}"
                                                            {{--data-code="{{ $product['data']['Kod'] }}"--}}
                                                            data-action = "to_showcase"
                                                            data-in_showcase = {{ $item['in_showcase'] }}
                                                            data-content_data="{{ json_encode($item['data'], true) }}"
                                                            onclick="javascript:inShowCase(this)"
                                                            class="btn btn-xs btn-success"
                                                            >На витрину
                                                        </a>
                                                        <a id="product_delete_1_{{ $item['id'] }}" href="#"
                                                            data-id="{{ $item['id'] }}"
                                                            {{--data-code="{{ $product['data']['Kod'] }}"--}}
                                                            data-action = "delete_from_showcase"
                                                            data-in_showcase = {{ $item['in_showcase'] }}
                                                            data-content_data="{{ json_encode($item['data'], true) }}"
                                                            onclick="javascript:inShowCase(this)"
                                                            style="display: none"
                                                            class="btn btn-xs btn-warning"
                                                            >Спрятать
                                                        </a>
                                                    @endif
                                                    @if($item['in_showcase'] == 1)
                                                        <a class="btn btn-xs btn-info" href="{{ route('getProductsList', $item['id']) }}">Просмотр</a>
                                                        <a id="product_id_{{ $item['id'] }}" href="#"
                                                            data-id="{{ $item['id'] }}"
                                                            {{--data-code="{{ $product['data']['Kod'] }}"--}}
                                                            data-action = "to_showcase"
                                                            data-in_showcase = {{ $item['in_showcase'] }}
                                                            data-content_data="{{ json_encode($item['data'], true) }}"
                                                            onclick="javascript:inShowCase(this)"
                                                            style="display: none"
                                                            class="btn btn-xs btn-success"
                                                            >На витрину
                                                        </a>
                                                        <a id="product_delete_{{ $item['id'] }}" href="#"
                                                            data-id="{{ $item['id'] }}"
                                                            {{--data-code="{{ $product['data']['Kod'] }}"--}}
                                                            data-action = "delete_from_showcase"
                                                            data-in_showcase = {{ $item['in_showcase'] }}
                                                                   data-content_data="{{ json_encode($item['data'], true) }}"
                                                            onclick="javascript:inShowCase(this)"
                                                            class="btn btn-xs btn-warning"
                                                            >Спрятать
                                                        </a>
                                                    @endif
                                                </td>
                                            </div>
                                        </tr>


                                    @endforeach
                                    </tbody>
                                </table>
                                <script>
                                    function inShowCase(element) {
                                        var content_data = $(element).data('content_data');
                                        var action = $(element).data('action');
                                        var in_showcase = $(element).data('in_showcase');
                                        var id = $(element).data('id');
                                        $.ajax({
                                            type:'POST',
                                            url:"{{ route('toShowcase') }}",
                                            data:{
                                                id : id,
                                                in_showcase:in_showcase,
                                                content_data : content_data,
                                                action : action,
                                                _token: '{{ csrf_token() }}'
                                            },
                                            dataType: 'json',
                                            success:function(data){
                                                var href = "http://laravel-eshop.app/dashboard/products/" + data.exchange_id;
                                                //var href = window.location.href +"/"+ data.product_id;


                                                console.log(data);
                                                //console.log(href);
                                                if(data.status == 'ok')
                                                {

                                                    if(data.in_showcase == 1)
                                                    {
                                                        //alert(data.in_showcase);

                                                        $('#product_id_1_' + id).attr('style', 'display:none');
                                                        $('#product_delete_1_' + id).attr('style', 'display:block-inline');
                                                       // $('#product_id_' + id).attr('style', 'display:none');

                                                        $('#product_id_' + id).attr('style', 'display:none')
                                                        $('#product_delete_' + id).attr('style', 'display:block-inline');
                                                        //alert(data.in_showcase);

                                                    }
                                                    if(data.in_showcase == 0)
                                                    {
                                                        //alert(data.in_showcase);
                                                        $('#product_id_' + id).attr('style', 'display:block-inline')
                                                        $('#product_delete_' + id).attr('style', 'display:none');

                                                        $('#product_id_1_' + id).attr('style', 'block-inline');
                                                        $('#product_delete_1_' + id).attr('style', 'display:none');
                                                    }
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

@endsection