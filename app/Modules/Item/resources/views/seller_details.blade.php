<!-- 'route'  => ['item.items.seller_update', $item] -->
{!! Form::model($item, ['id'=>'sellerDetailForm', 'data-parsley-validate'=>'true', 'method' => 'POST']) !!}

<div class="card-block">
    <div class="form-row">
	    <div class="form-group col-12 col-md-12 col-xl-12">
	        <label class="form-control-label">{{ __('Email') }} <span style="color:red">*</span></label>
	        {{ Form::select('customer_id', [], old('customer_id', isset($item->customer_id)?'selected':null), [
	        		'class'=>'select2 form-control' . ($errors->has('customer_id') ? ' is-invalid' : ''),
		        	'id'=>'customer_id',
		        	'required'=>'true',
		        	'data-parsley-error-message'=>"This value is required."
	        	])
	        }}

	        @if ($errors->has('customer_id'))
	            <input hidden class="form-control is-invalid">
	            <div class="invalid-feedback">{{ $errors->first('customer_id') }}</div>
	        @endif
	    </div>
	</div>
    <div class="form-row">
	    <div class="form-group col-12 col-md-12 col-xl-12">
	        <label class="form-control-label">{{ __('First Name') }}</label>
	        {{ Form::text('first_name', null, [
	                'class' => 'form-control' . ($errors->has('first_name') ? ' is-invalid' : ''),
	                'id'=>'first_name',
	                'placeholder' => __('First Name'),
	                'disabled'=>'true'
	            ])
	        }}

	        @if ($errors->has('first_name'))
	            <input hidden class="form-control is-invalid">
	            <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
	        @endif
	    </div>
	</div>
    <div class="form-row">
	    <div class="form-group col-12 col-md-12 col-xl-12">
	        <label class="form-control-label">{{ __('Last Name') }}</label>
	        {{ Form::text('last_name', null, [
	                'class' => 'form-control' . ($errors->has('last_name') ? ' is-invalid' : ''),
	                'id'=>'last_name',
	                'placeholder' => __('Last Name'),
	                'disabled'=>'true'
	            ])
	        }}

	        @if ($errors->has('last_name'))
	            <input hidden class="form-control is-invalid">
	            <div class="invalid-feedback">{{ $errors->first('last_name') }}</div>
	        @endif
	    </div>
	</div>
	<div id="divSellerDetailsExtra">
	    <div class="form-row">
		    <div class="form-group col-12 col-md-12 col-xl-12">
		        <label class="form-control-label">{{ __('Telephone') }}</label>
		        {{ Form::text('customer_phone', null, [
		                'class' => 'form-control' . ($errors->has('customer_phone') ? ' is-invalid' : ''),
		                'id'=>'customer_phone',
		                'placeholder' => __('Telephone'),
		                'disabled'=>'true'
		            ])
		        }}

		        @if ($errors->has('customer_phone'))
		            <input hidden class="form-control is-invalid">
		            <div class="invalid-feedback">{{ $errors->first('customer_phone') }}</div>
		        @endif
		    </div>
		</div>
	    <div class="form-row">
		    <div class="form-group col-12 col-md-12 col-xl-12">
		        <label class="form-control-label">{{ __('Address') }}</label>
		        {{ Form::text('address', null, [
		                'class' => 'form-control' . ($errors->has('address') ? ' is-invalid' : ''),
		                'id'=>'address',
		                'placeholder' => __('Address'),
		                'disabled'=>'true'
		            ])
		        }}

		        @if ($errors->has('address'))
		            <input hidden class="form-control is-invalid">
		            <div class="invalid-feedback">{{ $errors->first('address') }}</div>
		        @endif
		    </div>
		</div>
	    <div class="form-row">
		    <div class="form-group col-12 col-md-12 col-xl-12">
		        <label class="form-control-label">{{ __('Company') }}</label>
		        {{ Form::text('company_name', null, [
		                'class' => 'form-control' . ($errors->has('company_name') ? ' is-invalid' : ''),
		                'id'=>'company_name',
		                'placeholder' => __('Company'),
		                'disabled'=>'true'
		            ])
		        }}

		        @if ($errors->has('company_name'))
		            <input hidden class="form-control is-invalid">
		            <div class="invalid-feedback">{{ $errors->first('company_name') }}</div>
		        @endif
		    </div>
		</div>
	</div>
</div>

<div class="card-footer">
    <button type="button" class="btn btn-outline-success" id="btnSellerDetail">{{ __('Save') }}</button>
    <a href="#" onclick="history.back();" class="btn btn-outline-danger">{{ __('Cancel') }}</a>
</div>

{!! Form::close() !!}