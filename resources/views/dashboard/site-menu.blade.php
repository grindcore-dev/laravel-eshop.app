@extends('dashboard.layouts.main')
{{--@if(isset($selected_menu)) {{'asd'}}@endif--}}
<?//?>
@section('content')
    <div class="row">
        <div class="col-md-12 col-xs-12">
            @if (count($errors) > 0)
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @if(isset($action) and $action == 'list')
            <? dump($action)?>
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Меню сайта</h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12">
                                <a class="btn btn-primary btn-md" href="{{ route('siteMenu', 'add') }}">Добавить новое меню</a>
                            </div>
                        </div>
                    </div>
                    @if(isset($menus) and count($menus) >= 1)
                        <div class="row">
                            <? /*dump($menus)*/?>
                            <form class="form-horizontal form-label-left input_mask" method="post" action="{{ route('siteMenu') }}">
                            @csrf
                                <div class="col-sm-4">
                                    <label class="control-label" for="select_menu">{{-- <span class="required">*</span>--}}
                                        <small> Выберите меню для редактирования или просмотра:</small>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <select class="form-control" name="menu_id" id="select_menu">
                                            @foreach($menus as $menu)
                                                <option value="{{ $menu['id'] }}">{{ $menu['name'] . '(' . $menu['role'] . ')' }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="action" value="{{ md5('@edit@') }}" class="form-control">
                                        <span class="input-group-btn">
                                              <button type="submit" class="btn btn-primary">Выбрать</button>
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <p>Доступные элементы:</p>
                                <? dump($pages)?>
                            </div>
                            <div class="col-sm-8">
                                <p>Текущие элементы:</p>
                                @if(isset($selected_menu))
                                    <? /*dump($selected_menu)*/?>
                                    @foreach($selected_menu as $item)
                                        {{ $item }}
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endif
            @if(isset($action) and $action == 'add')
                    <? dump($action)?>
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Добавление меню</h2>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row">
                                <form class="form-horizontal form-label-left input_mask" method="post" action="{{ route('siteMenu'/*, 'add'*/) }}">
                                    @csrf
                                    <div class="form-group">
                                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="title">Название меню: <span class="required">*</span>
                                        </label>
                                        <div class="col-md-10 col-sm-10 col-xs-12">
                                            <input name="name" value="" type="text" id="title" required="required" class="form-control col-md-7 col-xs-12">
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
        </div>
    </div>

@endsection