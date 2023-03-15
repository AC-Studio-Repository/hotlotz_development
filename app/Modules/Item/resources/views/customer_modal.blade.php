<!-- Add New Seller Modal -->
<div class="modal fade" id="addNewSellerModal" tabindex="-1" role="dialog" aria-labelledby="addNewSellerModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="addNewSellerModalLabel">New Customer</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
        	  		<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form id="frmCustomer" data-parsley-validate="true">
        			<div class="row">
	        	  		<div class="form-group col-md-6">
	        	  		  	<label for="salutation" class="col-form-label">Title <span style="color:red">*</span></label>
	        	  		  	{{ Form::select('salutation', $salutations, null, [
				                    'class' => 'form-control form-control-md', 'id'=>'salutation',
				                    'required',
				                ])
				            }}
	        	  		</div>
	        	  		<div class="form-group col-md-6">
	        	  		  	<label for="email" class="col-form-label">Email <span style="color:red">*</span></label>
	        	  		  	<input type="email" name="email" class="form-control" id="email" required >
				            <div id="error_email" style="color: #B94A48;"></div>
	        	  		</div>
	        	  	</div>
	        	  	<div class="row">
	        	  		<div class="form-group col-md-6">
	        	  		  	<label for="firstname" class="col-form-label">First Name <span style="color:red">*</span></label>
	        	  		  	<input type="text" name="firstname" class="form-control" id="firstname" required >
	        	  		</div>
	        	  		<div class="form-group col-md-6">
	        	  		  	<label for="lastname" class="col-form-label">Last Name <span style="color:red">*</span></label>
	        	  		  	<input type="text" name="lastname" class="form-control" id="lastname" required >
	        	  		</div>
	        	  	</div>
	        	  	<div class="row">
					    <div class="form-group col-md-2">
					        <label class="col-form-label">{{ __('Country Code') }}</label>
				            {{ Form::select('dialling_code', $country_codes, null, [
				                    'class' => 'form-control form-control-md',
				                ])
				            }}
					    </div>
	        	  		<div class="form-group col-md-4">
	        	  		  	<label for="phone" class="col-form-label">Phone <span style="color:red">*</span></label>
	        	  		  	<input type="text" name="phone" class="form-control" id="phone" required >
	        	  		</div>
	        	  		<div class="form-group col-md-6">
	        	  		  	<label for="phone" class="col-form-label">Country of Residence <span style="color:red">*</span></label>
	        	  		  	{{ Form::select('country_of_residence', $countries, null, [
				                    'class' => 'form-control form-control-md',
				                    'required'
				                ])
				            }}
	        	  		</div>
        	  		</div>
       			</form>
      		</div>
      		<div class="modal-footer">
      		  	<button type="button" class="btn btn-secondary" id="addNewSellerModalClose" data-dismiss="modal">Close</button	>
      		  	<button type="button" class="btn btn-primary btnCreateCustomer">Create Customer</button>
      		</div>
    	</div>
  	</div>
</div>