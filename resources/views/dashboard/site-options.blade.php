@extends('dashboard.layouts.main')
{{--{{ dd($options) }}--}}
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
        {{--<div class="col-md-12 col-xs-12">--}}
            <div class="x_panel">
                <div class="x_title">
                    <h2>Основные настройки сайта</h2><br><br>
                    {{--<p><b>Created at: </b>{{ Auth::user()->created_at }}</p>
                    <p><b>Last updated at: </b>{{ Auth::user()->updated_at }}</p>--}}
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form class="form-horizontal form-label-left input_mask" method="post" action="{{ route('siteOptions') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="site_title">Название сайта: <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input name="site_title" value="{{ $options['site_title'] }}" type="text" id="site_title" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="meta_charset">Кодировка: <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input name="meta_charset" value="{{ $options['meta_charset'] }}" type="text" id="meta_charset" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="meta_description">Мета описание сайта: <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <textarea name="meta_description" type="text" id="meta_charset" required="required" class="form-control col-md-7 col-xs-12">{{ $options['meta_description'] }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="meta_keywords">Мета ключивые слова: <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input name="meta_keywords" value="{{ $options['meta_keywords'] }}" type="text" id="meta_keywords" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="site_icon">Эконка сайта: <span class="required">*</span>
                            </label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <input name="site_icon" type="file" id="site_icon" class="form-control col-md-7 col-xs-12">
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <img class="img avatar-view" src="{{ asset('storage/' . $options['site_icon']) }}" alt="site_icon" title="Change the site icon" style="width: 32px"><br/>
                            </div>
                        </div>


                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                <button type="submit" class="btn btn-success">Сохранить</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        {{--</div>--}}
    </div>
@endsection