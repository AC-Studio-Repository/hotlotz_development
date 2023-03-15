<script id="email_template" type="text/x-handlebars-template">
	<div class="row divEmail">
		<input type="hidden" name="admin_email_id" class="admin_email_id" value="@{{id}}" disabled >

	    <div class="form-group col-4 col-md-4 col-xl-4">
            <input type="text" name="email[@{{type}}][]" value="@{{email}}" class="form-control" required @{{#if_not_eq id 0}} disabled @{{/if_not_eq}} >
	    </div>

	    <div class="form-group col-4 col-md-4 col-xl-4">
		    <button type="button" class="btn btn-default 
		    @{{#if_eq type 'swu'}} removeSellWithUsButton @{{/if_eq}} 
		    @{{#if_eq type 'bau'}} removeBankAccUpdateButton @{{/if_eq}}
		    @{{#if_eq type 'profile'}} removeProfileUpdateButton @{{/if_eq}}
		    @{{#if_eq type 'mp_sold_items'}} removeMpSoldItemsButton @{{/if_eq}}
		    @{{#if_eq type 'items_moved_to_storage'}} removeItemsMovedToStorageButton @{{/if_eq}}
		    @{{#if_eq type 'sales_contract'}} removeSalesContractButton @{{/if_eq}}
		    @{{#if_eq type 'bank_paynow_checkout'}} removeBankPaynowCheckoutButton @{{/if_eq}}
		    @{{#if_eq type 'kyc'}} removeKycUpdateButton @{{/if_eq}}
		    ">
		    <i class="fas fa-times"></i></button>
	    </div>
	</div>
</script>