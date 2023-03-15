{!! Form::model($category, ['route'  => ['category.categories.update', $category], 'method' => 'PUT', 'data-parsley-validate'=>'true']) !!}

<div class="card-block">
    <input type="hidden" name="category_id" id="category_id" value="{{$category->id}}">
    @include('category::_form')
</div>

<div class="card-footer">
    <button class="btn btn-primary">{{ __('Update Category') }}</button>
    <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
</div>

{!! Form::close() !!}