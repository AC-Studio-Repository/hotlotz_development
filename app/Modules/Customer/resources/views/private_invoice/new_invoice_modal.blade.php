<!-- Add New Invoice Modal -->
<div class="modal fade" id="newInvoiceModalPrivate" tabindex="-1" role="dialog" aria-labelledby="newInvoiceModalPrivateLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="newInvoiceModalPrivateLabel">New Invoice</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
        	  		<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form id="frmNewInvoicePrivate" data-parsley-validate="true">
                    <div class="card-block">
                        <div class="form-row">
                            <div class="form-group col-12 col-md-12 col-xl-12 select-box">
                                <button type="button" class="btn btn-success" id="addNewItemButtonPrivate"><i class="zmdi zmdi-plus"></i> {{ __('Add New Item') }}</button>
                            </div>
                        </div>

                        <div id="customerprivateitem">
                            <div class="divXeroItem" style="border: 2px solid #ddd; padding: 20px; margin-bottom: 5px;">
                                <div class="row">
                                    <input type="hidden" name="customer_item_id[]" id="customer_item_id" value="0">

                                    <div class="form-group col-md-5">
                                        <label class="form-control-label">{{ __('Item') }}</label>
                                        {{ Form::select('item_id[]', $private_items, null, [
                                                'class'=>'form-control select2' . ($errors->has('item_id') ? ' is-invalid' : ''),
                                                'id'=>'item_id',
                                                "required",
                                            ])
                                        }}
                                    </div>

                                    <div class="form-group col-md-5">
                                        <label class="form-control-label">{{ __('Price') }}</label>
                                        <input type="text" class="form-control price" id="price" name="price[]" value="" data-parsley-type="number" required />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-5">
                                        <label class="form-control-label">{{ __('Buyer Premium (%)') }}</label>
                                        <input type="text" class="form-control buyer_premiun" id="buyer_premiun" name="buyer_premiun[]" value="" data-parsley-type="number" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
       			</form>
      		</div>
      		<div class="modal-footer">
      		  	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button	>
      		  	<input type="button" class="btn btn-primary btnCreateInvoicePrivate" onclick="this.disabled=true;this.value='Processing...';" value="Create Invoice">
      		</div>
    	</div>
  	</div>
</div>