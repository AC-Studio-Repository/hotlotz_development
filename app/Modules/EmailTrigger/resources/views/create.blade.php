@extends('appshell::layouts.default')

@section('styles')
@stop

@section('title')
    {{ __('Create new category') }}
@stop

@section('content')

    {!! Form::open(['route' => 'category.categories.store', 'autocomplete' => 'off', 'enctype'=>'multipart/form-data', 'class' => 'row']) !!}

        <div class="col-12 col-lg-12 col-xl-12">
            <div class="card card-accent-secondary">
                <div class="card-header">
                    {{ __('Category Details') }}
                </div>
                <div class="card-block">
                    @include('category::_form')
                </div>
                <div class="card-footer">
                    <button class="btn btn-success">{{ __('Create category') }}</button>
                    <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 col-xl-3">

        </div>

    {!! Form::close() !!}

@stop

@section('scripts')
<!-- ### Additional CSS ### -->
<link href="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.css')}}" rel="stylesheet" />
<link href="{{asset('plugins/jquery-tag-it-v2.0/css/jquery.tagit.css')}}" rel="stylesheet" />

<!-- ### Additional JS ### -->
<script src="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.js')}}"></script>
<script src="{{asset('plugins/jquery-tag-it-v2.0/js/tag-it.min.js')}}"></script>

<script type="text/javascript">
    $(function() {
        $("#subcategory").tagit({
            allowSpaces: true
        });
        // $("#sub_subcategory").tagit({
        //     allowSpaces: true
        // });
    });
</script>
@stop