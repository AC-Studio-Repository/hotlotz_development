@extends('appshell::layouts.default')

@section('styles')
@stop

@section('title')
    {{ __('Create new post') }}
@stop

@section('content')

    {!! Form::open(['route' => 'blog_article.blog_articles.store', 'autocomplete' => 'off', 'enctype'=>'multipart/form-data', 'class' => 'row', 'data-parsley-validate'=>'true']) !!}

        <div class="col-12 col-lg-12 col-xl-12">
            <div class="card card-accent-secondary">
                <div class="card-header">
                    {{ __('Press Coverage Details') }}
                </div>
                <div class="card-block">
                    @include('blog_article::_form')
                </div>
                <div class="card-footer">
                    <button class="btn btn-success">{{ __('Create') }}</button>
                    <a href="{{ route('blog_article.blog_articles.index')}}" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 col-xl-3">

        </div>

    {!! Form::close() !!}

@stop

@section('scripts')
<!-- Parsley JS -->
<link rel="stylesheet" href="{{asset('plugins/Parsley.js-2.9.1/src/parsley.css')}}">
<script src="{{asset('plugins/Parsley.js-2.9.1/dist/parsley.min.js')}}"></script>

<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
    new Vue({
        el: '#app',
        data: {
            articleType: '{{ old('type') ?: $blog_article->type }}',
        }
    });

    $(function() {
        $( "#datepicker" ).datepicker({
            dateFormat: "yy-mm-dd"
        });
    });
</script>

@include('blog_article::blogarticle_js')

@stop