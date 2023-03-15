{!! Form::model($category, ['route'  => ['category.categories.category_property_update', $category], 'method' => 'POST', 'data-parsley-validate'=>'true']) !!}

<div class="card-block">
    <div class="form-row">
        <div class="form-group col-12 col-md-12 col-xl-12 select-box">
        	<input type="hidden" name="category_id" id="category_id" value="{{$category->id}}">
            <button type="button" class="btn btn-success" id='addButton'><i class="zmdi zmdi-plus"></i> Add Property</button>
        </div>
    </div>
    
	<div id="categoryproperty">
		
	</div>
</div>

<div class="card-footer">
    <button class="btn btn-primary">{{ __('Save') }}</button>
</div>

{!! Form::close() !!}