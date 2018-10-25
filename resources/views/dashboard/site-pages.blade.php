@extends('dashboard.layouts.main')
@section('content')
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <? //dump($errors->all())?>
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
            @if(isset($action) and $action == 'list')
                <? dump($action)?>
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Страницы</h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12">
                                <a class="btn btn-primary btn-md" href="{{ route('sitePages', 'add') }}">Добавить новую страницу</a>
                            </div>
                        </div>
                        @if(isset($pages) and count($pages) >= 1)
                            <div class="row">
                                <? dump($pages)?>
                                <div class="table-responsive">
                                    <table class="table table-striped jambo_table bulk_action">
                                        <thead>
                                        <tr class="headings">

                                            <th class="column-title">#Id </th>
                                            <th class="column-title">Заголовок страницы</th>
                                            <th class="column-title">Url</th>
                                            <th class="column-title">Статус</th>
                                            <th class="column-title">Дата публикации</th>
                                            <th class="column-title">Последнее обновление</th>
                                            <th class="column-title no-link last"><span class="nobr">Action</span></th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pages as $page)
                                                <? //dump($page)?>
                                                <tr>
                                                    <td>{{ $page['id'] }}</td>
                                                    <td>{{ $page['title'] }}
                                                        @switch($page['page_roles'])
                                                            @case ("home")
                                                                <span style="color: red">&nbsp;  -- Главная страница сайта</span>
                                                            @break
                                                            @case ("blog")
                                                                <span style="color: forestgreen">&nbsp;  -- Страница записей</span>
                                                            @break
                                                            @case ("checkout")
                                                                <span>&nbsp;  -- Страница оформления заказа</span>
                                                            @break
                                                        @endswitch
                                                    </td>
                                                    <td>{{ $page['url'] }}</td>
                                                    <td>{{ $page['status'] }}</td>
                                                    <td>{{ $page['updated_at'] }}</td>
                                                    <td>{{ $page['created_at'] }}</td>
                                                    <td>
                                                        <a class="btn btn-xs btn-info" href="{{ route('sitePages', [ 'edit', $page['id'] ]) }}">
                                                            Просмотр
                                                        </a>
                                                        @if(!$page['deleted_at'])
                                                        <a class="btn btn-xs btn-warning" href="{{ route('sitePages', [ 'draft', $page['id'] ]) }}">
                                                            В корзину
                                                        </a>
                                                        @else
                                                            <a class="btn btn-xs btn-success" href="{{ route('sitePages', [ 'restore', $page['id'] ]) }}">
                                                                Опубликовать
                                                            </a>
                                                        @endif

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        @endif
                    </div>
                </div>
            @endif
            @if(isset($action) and $action == 'add')
                <? dump($action)?>
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Добавление новой страницы </h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <form class="form-horizontal form-label-left input_mask" method="post" action="{{ route('sitePages'/*, 'add'*/) }}">
                                @csrf
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="title">Название страницы: <span class="required">*</span>
                                    </label>
                                    <div class="col-md-10 col-sm-10 col-xs-12">
                                        <input name="title" value="" type="text" id="title" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" @if(errorsCheck('url', $errors->all()) ) style="color: red" @endif>Адрес страницы:<span class="required">*</span></label>

                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                  <button type="button" class="btn btn-default">{{ env('APP_URL') }}/</button>
                                            </span>
                                            <input name="url" value="" type="text" id="url" required="required" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-10">
                                        <input id="action" name="action" type="hidden" value="{{ md5('@add@') }}">
                                        <button type="submit" class="btn btn-primary">Сохранить</button>

                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>

            @endif
            @if(isset($action) and $action == 'edit')
                <? dump($action)?>
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Вы редактируете страницу:</h2>
                        <div class="nav navbar-right panel_toolbox">
                            @if($page['status'] == 'publish')
                                Опубликованно
                            @else
                                В корзине
                            @endif
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <form class="form-horizontal form-label-left input_mask" method="post" action="{{ route('sitePages') }}">
                                @csrf
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="title">Название страницы: <span class="required">*</span>
                                    </label>
                                    <div class="col-md-10 col-sm-10 col-xs-12">
                                        <input name="title" value="{{ $page['title'] }}" type="text" id="title" required="required" class="form-control col-md-7 col-xs-12">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Адрес страницы:<span class="required">*</span></label>

                                    <div class="col-sm-10">
                                        <div class="input-group">
                                        <span class="input-group-btn">
                                              <button type="button" class="btn btn-default">{{ env('APP_URL') }}/</button>
                                        </span>
                                            <input name="url" value="{{ $page['url'] }}" type="text" id="url" required="required" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?//dump($roles)?>
                                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Роль страницы:</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select class="select2_single form-control" name="page_roles" tabindex="-1">

                                            @foreach($roles as $role)
                                                <option value="{{ $role['alias'] }}" @if($role['alias'] == $page['page_roles']) selected @endif>{{ $role['alias'] }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-9">
                                        <input id="action" name="action" type="hidden" value="{{ md5('@publish@') }}">
                                        <input name="page_id" type="hidden" value="{{ $page['id'] }}">
                                        <a href="{{ route('sitePages', [ 'draft', $page['id'] ]) }}" class="btn btn-warning" type="button" onclick="">Сохранить</a>
                                        <button type="submit" class="btn btn-primary" onclick="">Опубликовать</button>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <div class="well col-md-6 col-sm-6 col-xs-12" style="overflow: auto">
                                <form class="form-horizontal form-label-left input_mask" method="post" action="{{ route('sitePages') }}">
                                 @csrf
                                    <div class="form-group">
                                        <div class="">
                                            <p>Доступные блоки:</p>
                                             <? //dump( $page_blocks[0])?>
                                            <select class="form-control" name="blocks" id="blocks">
                                                @foreach($blocks as $block)
                                                    @if(!in_array($block['name'], $page_blocks))
                                                        <option id="blocks_{{ $block['name'] }}">{{ $block['name'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <br>
                                            <button type="submit" class="btn btn-primary">Добавить</button>
                                            <input id="action" name="action" type="hidden" value="{{ md5('@save_block@') }}">
                                            <input name="page_id" type="hidden" value="{{ $page['id'] }}">

                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="well col-md-6 col-sm-6 col-xs-12" style="overflow: auto">
                                    <div class="form-group">
                                        <p>Текущие блоки:</p>
                                        <? //dump( $page_blocks[0])?>
                                            @foreach($page_blocks as $key => $block)
                                                <div class="col-md-9 col-sm-9 col-xs-12">
                                                    <input type="text" class="form-control" readonly="readonly" placeholder="{{ $block }}">
                                                </div>
                                                <div class="col-md-3 col-sm-3 col-xs-12">
                                                    {{--<a href=""  class="btn btn-danger">Удалить блок</a>--}}
                                                    <a class="btn btn-danger" href="{{ route('sitePages', ['delete', $page['id']]) . '?block=' . $block }}"
                                                       >Удалить блок
                                                    </a>
                                                </div>
                                                <br>
                                                <br>
                                            @endforeach
                                            {{--<form id="delete-block-form" action="{{ route('sitePages') }}" method="POST" style="display: none;">
                                                @csrf
                                                <input id="action" name="action" type="hidden" value="{{ md5('@delete_block@') }}">
                                                <input name="page_id" type="hidden" value="{{ $page['id'] }}">
                                                <input id="block_name" name="block" type="hidden" value="{{ $page['id'] }}">
                                            </form>--}}
                                    </div>

                            </div>
                        </div>
                    </div>
                </div>

            @endif
        </div>
    </div>
<?
    function errorsCheck ($val, $errors_arr)
    {
        foreach ($errors_arr as $error)
        {
            if($error == $val)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
?>


@endsection

