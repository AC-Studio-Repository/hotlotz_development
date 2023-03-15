<div class="card-block">
    <div class="form-row">
	    <div class="form-group col-12 col-md-12 col-xl-12">
	        <label class="form-control-label">{{ __('Title') }} <span style="color:red">*</span></label>
	        {{ Form::text('title', null, [
	                'class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : ''),
	                'id'=>'title',
	                'required'=>'true',
	                'data-parsley-required-message'=>"This value is required.",
	                'placeholder' => __('Title')
	            ])
	        }}

	        @if ($errors->has('title'))
	            <input hidden class="form-control is-invalid">
	            <div class="invalid-feedback">{{ $errors->first('title') }}</div>
	        @endif
	    </div>
	</div>
	<div class="form-row">
	    <div class="form-group col-12 col-md-12 col-xl-12">
	        <label class="form-control-label">{{ __('Category') }} <span style="color:red">*</span></label>
	        {{ Form::select('category_id', $categories, null, [
		        	'class'=>'form-control' . ($errors->has('category_id') ? ' is-invalid' : ''),
		        	'id'=>'category_id',
		        	'required'=>'true',
		            'data-parsley-required-message'=>"This value is required."
	            ])
	        }}

	        @if ($errors->has('category_id'))
	            <input hidden class="form-control is-invalid">
	            <div class="invalid-feedback">{{ $errors->first('category_id') }}</div>
	        @endif
	    </div>
	</div>
	<div class="form-row">
	    <div class="form-group col-12 col-md-12 col-xl-12">
	        <label class="form-control-label">{{ __('Location') }} <span style="color:red">*</span></label>
	        {{ Form::select('country_id', $countries, 702, [
	        		'class'=>'form-control' . ($errors->has('country_id') ? ' is-invalid' : ''),
	        		'id'=>'country_id',
	        		'required'=>'true',
		            'data-parsley-required-message'=>"This value is required."
	        	])
	        }}

	        @if ($errors->has('country_id'))
	            <input hidden class="form-control is-invalid">
	            <div class="invalid-feedback">{{ $errors->first('country_id') }}</div>
	        @endif
	    </div>
	</div>
	<div class="form-row">
	    <div class="form-group col-12 col-md-12 col-xl-12">
			<label class="form-control-label">{{ __('Currently in Hotlotz Warehouse') }}</label>
			<label class="switch switch-icon switch-pill switch-primary">
				{{ Form::checkbox('currently_in_hotlotz_warehouse', 1, old('currently_in_hotlotz_warehouse', $item['currently_in_hotlotz_warehouse']), ['class' => 'switch-input', 'id' => 'currently_in_hotlotz_warehouse']) }}
				<span class="switch-label" data-on="Yes" data-off="No"></span>
				<span class="switch-handle"></span>
			</label>
		</div>
	</div>
	<div class="form-row">
	    <div class="form-group col-12 col-md-12 col-xl-12">
	        <label class="form-control-label">{{ __('Description') }} <span style="color:red">*</span></label>
	        {{ Form::textarea('long_description', old('long_description', isset($item->long_description)?$item->long_description:null),
	            [
	                'class' => 'form-control' . ($errors->has('long_description') ? ' is-invalid' : ''),
	                'placeholder' => __('Type or copy/paste Description here'),
	                'rows' => 10,
	                'id'=>'long_description',
	                'required'=>'true',
		            'data-parsley-required-message'=>"This value is required."
	            ]
	        ) }}

	        @if ($errors->has('long_description'))
	            <div class="invalid-feedback">{{ $errors->first('long_description') }}</div>
	        @endif
	    </div>
	</div>
</div>