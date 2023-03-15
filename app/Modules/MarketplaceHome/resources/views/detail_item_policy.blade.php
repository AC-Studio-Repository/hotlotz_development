@extends('appshell::layouts.default')

@section('title')
    {{ __('Collection & Shipping, One Tree Planted, Sale Policy') }}
@stop

@section('content')
<div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')
        </div>

        {!! Form::model($policy, ['route' => ['marketplace_home.marketplace_homes.updatePolicyContent'], 'method' => 'POST']) !!}
        <div class="card-block">
            <div class="row">
                    <div class="col-md-4">
                        <label>{{ __('Collection & Shipping Title Header') }}</label>
                        <div class="form-group">
                            {{ Form::text('collection_Shipping_header', (!$policy_data->isEmpty()) ? $policy->blog_header_1 : null, [
                                    'class' => 'form-control form-control-md' . ($errors->has('collection_Shipping_header') ? ' is-invalid' : ''),
                                    'id' => 'collection_Shipping_header',
                                    'placeholder' => __('Collection & Shipping Title Header')
                                ])
                            }}

                            @if ($errors->has('collection_Shipping_header'))
                                <div class="invalid-feedback">{{ $errors->first('collection_Shipping_header') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label>{{ __('Collection & Shipping Blog') }}</label>
                        @if(!$policy_data->isEmpty())
                            <textarea id="collection_Shipping_blog" name="collection_Shipping_blog" class="summernote">{!! $policy->collection_Shipping_blog !!}</textarea>
                        @else
                            <textarea id="collection_Shipping_blog" name="collection_Shipping_blog" class="summernote"></textarea>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-4">
                        <label>{{ __('One Tree Planted Title Header') }}</label>
                        <div class="form-group">
                            {{ Form::text('one_tree_planted_header', (!$policy_data->isEmpty()) ? $policy->one_tree_planted_header : null, [
                                    'class' => 'form-control form-control-md' . ($errors->has('one_tree_planted_header') ? ' is-invalid' : ''),
                                    'id' => 'one_tree_planted_header',
                                    'placeholder' => __('One Tree Planted Title Header')
                                ])
                            }}

                            @if ($errors->has('one_tree_planted_header'))
                                <div class="invalid-feedback">{{ $errors->first('one_tree_planted_header') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label>{{ __('One Tree Planted Blog') }}</label>
                        @if(!$policy_data->isEmpty())
                            <textarea id="one_tree_planted_blog" name="one_tree_planted_blog" class="summernote">{!! $policy->one_tree_planted_blog !!}</textarea>
                        @else
                            <textarea id="one_tree_planted_blog" name="one_tree_planted_blog" class="summernote"></textarea>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-md-4">
                        <label>{{ __('Sale Policy Title Header') }}</label>
                        <div class="form-group">
                            {{ Form::text('sale_policy_header', (!$policy_data->isEmpty()) ? $policy->sale_policy_header : null, [
                                    'class' => 'form-control form-control-md' . ($errors->has('sale_policy_header') ? ' is-invalid' : ''),
                                    'id' => 'sale_policy_header',
                                    'placeholder' => __('Sale Policy Title Header')
                                ])
                            }}

                            @if ($errors->has('sale_policy_header'))
                                <div class="invalid-feedback">{{ $errors->first('sale_policy_header') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label>{{ __('Sale Policy Blog') }}</label>
                        @if(!$policy_data->isEmpty())
                            <textarea id="sale_policy_blog" name="sale_policy_blog" class="summernote">{!! $policy->sale_policy_blog !!}</textarea>
                        @else
                            <textarea id="sale_policy_blog" name="sale_policy_blog" class="summernote"></textarea>
                        @endif
                    </div>
                </div>
                <hr>

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">{{ __('Update') }}</button>
                <a href="{{ route("marketplace_home.marketplace_homes.index") }}"  class="btn btn-link text-muted">{{ __('Cancel') }}</a>
            </div>
        </div>
    {!! Form::close() !!}    
@stop

@section('scripts')

@include('content_management::summernote')

<style type="text/css">
   .mt-50{margin-top: 50px;}
</style>
<script>
 var _token = $('input[name="_token"]').val();

    $(document).ready(function() {

        $('#collection_Shipping_blog').summernote({
                height: 250,
                focus: true,
                width: 950,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['view', ['fullscreen']]
            ],
            callbacks: {
                onPaste: function (e) {
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                    e.preventDefault();
                    document.execCommand('insertText', false, bufferText);
                }
            }
        });

        $('#one_tree_planted_blog').summernote({
                height: 250,
                focus: true,
                width: 950,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['view', ['fullscreen']]
            ],
            callbacks: {
                onPaste: function (e) {
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                    e.preventDefault();
                    document.execCommand('insertText', false, bufferText);
                }
            }
        });

        $('#sale_policy_blog').summernote({
                height: 250,
                focus: true,
                width: 950,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['view', ['fullscreen']]
            ],
            callbacks: {
                onPaste: function (e) {
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                    e.preventDefault();
                    document.execCommand('insertText', false, bufferText);
                }
            }
        });
    });
</script>
@stop
