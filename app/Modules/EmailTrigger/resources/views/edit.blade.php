@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }} {{ $category->name }}
@stop

@section('styles')
@stop

@section('content')
<div class="row">

    <div class="col-12 col-lg-12 col-xl-12">
        {!! Form::model($category, ['route'  => ['category.categories.update', $category], 'method' => 'PUT'])
        !!}
        <div class="card card-accent-secondary">
            <div class="card-header">
                <nav>
                  <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-category nav-link active" id="category_edit-tab" data-toggle="tab" href="#category_edit" role="tab" aria-controls="category_edit" aria-selected="true">{{ __('Category Details') }}</a>
                        <a class="nav-category nav-link" id="category_lifecycle-tab" data-toggle="tab" href="#category_lifecycle" role="tab" aria-controls="category_lifecycle" aria-selected="false">Category Properties</a>
                  </div>
                </nav>
            </div>
            <div>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="category_edit" role="tabpanel" aria-labelledby="category_edit-tab">
                        @include('category::category_details')
                    </div>
                    <div class="tab-pane fade" id="category_lifecycle" role="tabpanel" aria-labelledby="category_lifecycle-tab">
                        @include('category::category_property')
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>

    <div class="col-12 col-lg-4 col-xl-3">
    </div>

</div>
@stop

@section('scripts')

<!-- ### Additional CSS ### -->
<link href="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.css')}}" rel="stylesheet" />
<link href="{{asset('plugins/jquery-tag-it-v2.0/css/jquery.tagit.css')}}" rel="stylesheet" />

<!-- ### Additional JS ### -->
<script src="{{asset('custom/js/handlebars-v4.7.3.min.js')}}"></script>
<script src="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.js')}}"></script>
<script src="{{asset('plugins/jquery-tag-it-v2.0/js/tag-it.min.js')}}"></script>

<script type="text/javascript">
    $(function() {
        $("#subcategory").tagit({
            allowSpaces: true
        });
    });
</script>

@include('category::category_property_template')
@include('category::category_propertyjs')

@stop