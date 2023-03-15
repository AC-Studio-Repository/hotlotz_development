@extends('appshell::layouts.default')

@section('title')
    {{ __('Category Details') }}
@stop

@section('content')
    <div class="card">
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
                    <div class="form-row">
                        <div class="form-group col-12 col-md-12 col-xl-12">
                            <label class="form-control-label">{{ __('Category Name*') }}</label>
                            {{ Form::text('name', $category->name, ['class' => 'form-control','disabled'])}}
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="category_lifecycle" role="tabpanel" aria-labelledby="category_lifecycle-tab">
                    <div class="card-block">
                        @foreach($category->categoryproperties as $categoryproperty)
                            <div class="" style="border: 2px solid #ddd; padding: 20px; margin-bottom: 5px;">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label">{{ __('Properties') }}</label>
                                        <input type="text" class="form-control" name="key" value="{{$categoryproperty->key}}" placeholder="Properties" disabled="disabled" />
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-control-label">{{ __('Property Values') }}</label>
                                        <input type="text" class="form-control" id="property_value" name="value" value="{{$categoryproperty->value}}" placeholder="Property Values" disabled="disabled" />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label">{{ __('Field Type') }}</label>
                                        {{ Form::select('field_type', $field_types, $categoryproperty->field_type, array('class'=>'form-control', 'disabled'))}}
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label">{{ __('Required/optional') }}</label>
                                        {{ Form::text('is_required', $categoryproperty->is_required, ['class' => 'form-control','disabled'])}}
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="form-control-label">{{ __('Filter') }}</label>
                                        {{ Form::text('is_filter', ($categoryproperty->is_filter == 'Y')?'Yes':'No', ['class' => 'form-control','disabled'])}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('category.categories.edit', $category) }}" class="btn btn-outline-primary">{{ __('Edit Category') }}</a>
                </div>
            </div>
        </div>
    </div>

@stop
