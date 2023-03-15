<!-- Add New Invoice Modal -->
<div class="modal fade" id="newInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="newInvoiceModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="newInvoiceModalLabel">New Invoice</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
        	  		<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<form id="frmNewInvoice" data-parsley-validate="true">
                    <input type="hidden" name="order_id" value="{{ app('request')->input('order_id') }}">
                    <div class="card-block">
                        <div class="form-row">
                            <div class="form-group col-12 col-md-12 col-xl-12 select-box">
                                <button type="button" class="btn btn-success" id="addNewItemButton"><i class="zmdi zmdi-plus"></i> {{ __('Add New Item') }}</button>
                            </div>
                        </div>

                        <div id="customerxeroitem">
                            <div class="divXeroItem" style="border: 2px solid #ddd; padding: 20px; margin-bottom: 5px;">
                                <div class="row">
                                    <input type="hidden" name="customer_xero_item_id[]" id="customer_xero_item_id" value="0">

                                    <div class="form-group col-md-5">
                                        <label class="form-control-label">{{ __('Xero Item') }}</label>
                                        {{ Form::select('xero_item_id[]', $xero_items, null, [
                                                'class'=>'form-control' . ($errors->has('xero_item_id') ? ' is-invalid' : ''),
                                                'id'=>'xero_item_id',
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
                                    <div class="form-group col-md-12">
                                        <label class="form-control-label">{{ __('Notes') }}</label>
                                        <div class="radio-inline">
                                            <textarea name="notes[]" class="form-control notes" id="notes" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
       			</form>
      		</div>
      		<div class="modal-footer">
      		  	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button	>
      		  	<input type="button" class="btn btn-primary btnCreateInvoice" onclick="this.disabled=true;this.value='Processing...';" value="Create Invoice">
      		</div>
    	</div>
  	</div>
</div>