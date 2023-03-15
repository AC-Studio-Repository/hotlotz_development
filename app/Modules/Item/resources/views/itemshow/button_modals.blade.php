<!-- btnDeclinedItem Modal -->
<div class="modal fade" id="btnDeclinedItemModal" tabindex="-1" role="dialog" aria-labelledby="btnDeclinedItemModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="btnDeclinedItemModalLabel">Declined Item</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
        	  		<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<label>Are you sure to decline this item?</label>
      		</div>
      		<div class="modal-footer">
      		  	<button type="button" class="btn btn-info" id="btnDeclinedItem">Yes</button>
      		  	<button type="button" class="btn btn-secondary" data-dismiss="modal" id="btnDeclinedItemModalClose">No</button	>
      		</div>
    	</div>
  	</div>
</div>

<!-- btnWithdrawnItem Modal -->
<div class="modal fade" id="btnWithdrawnItemModal" tabindex="-1" role="dialog" aria-labelledby="btnWithdrawnItemModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="btnWithdrawnItemModalLabel">Withdrawn Item</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
        	  		<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        		<label>
                @if($item->fee_structure->withdrawal_fee_setting == 1)
                    @php
                    $withdrawalfee = str_replace('$', '', $item->fee_structure->withdrawal_fee);
                    @endphp
                    This item has a Withdrawal Fee of ${{ number_format($withdrawalfee, 2) }}. Do you wish to still charge the withdrawal fee to the seller?
                @else
                Are you sure to withdraw this item?
                @endif
               </label>
      		</div>
      		<div class="modal-footer">
                @if($item->fee_structure->withdrawal_fee_setting == 1)
                <button type="button" class="btn btn-info" onclick="chargeWithdrawlFee('{{ $item->id }}')">Charge Withdrawal Fee</button>
                <button type="button" class="btn btn-secondary" onclick="chargeWithoutWithdrawlFee('{{ $item->id }}')">Withdraw without Fee</button	>
                @else
      		  	<button type="button" class="btn btn-info" id="btnWithdrawnItem">Yes</button>
      		  	<button type="button" class="btn btn-secondary" data-dismiss="modal" id="btnWithdrawnItemModalClose">No</button	>
                @endif
      		</div>
    	</div>
  	</div>
</div>

<!-- btnInternalWithdrawnItem Modal -->
<div class="modal fade" id="btnInternalWithdrawnItemModal" tabindex="-1" role="dialog" aria-labelledby="btnInternalWithdrawnItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="btnInternalWithdrawnItemModalLabel">Withdrawn Item</h5>
            <button type="button" class="close" data-dismiss="modal" id="btnInternalWithdrawnItemModalClose" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <label>Are you sure to withdraw this item?</label>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-info" id="btnInternalWithdrawnItem">Yes</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button >
          </div>
      </div>
    </div>
</div>

<!-- btnDispatchedItem Modal -->
<div class="modal fade" id="btnDispatchedItemModal" tabindex="-1" role="dialog" aria-labelledby="btnDispatchedItemModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="btnDispatchedItemModalLabel">Dispatched Item</h5>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
        	  		<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body">
        	    <form id="frmDispatchedItem" data-parsley-validate="true">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="dispatched_or_collected_date" class="col-form-label">Dispatched Date <span style="color:red">*</span></label>
                            {{ Form::text('dispatched_or_collected_date', null, [
                                    'class' => 'form-control form-control-md', 'id'=>'dispatched_or_collected_date',
                                    'required',
                                ])
                            }}
                            {{
                                Form::hidden('dispatched_date',null,['id'=>'dispatched_date'])
                            }}
                            {{
                                Form::hidden('dispatched_time',null,['id'=>'dispatched_time'])
                            }}
                        </div>
                        <div class="form-group col-md-6">
                            <label for="dispatched_person" class="col-form-label">Dispatched Person <span style="color:red">*</span></label>
                            {{ Form::text('dispatched_person', null, [
                                    'class' => 'form-control form-control-md', 'id'=>'dispatched_person',
                                    'required',
                                ])
                            }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="dispatched_remark" class="col-form-label">Dispatched Remark</label>
                            {{ Form::textarea('dispatched_remark', null, [
                                    'class' => 'form-control form-control-md', 'id'=>'dispatched_remark',
                                    'rows' => 5,
                                ])
                            }}
                        </div>
                    </div>
                </form>
      		</div>
      		<div class="modal-footer">
      		  	<button type="button" class="btn btn-info" id="btnDispatchedItem">Save</button>
      		  	<button type="button" class="btn btn-secondary" data-dismiss="modal" id="btnDispatchedItemModalClose">Close</button	>
      		</div>
    	</div>
  	</div>
</div>

<!-- btnDeliveryBooked Modal -->
<div class="modal fade" id="btnDeliveryBookedModal" tabindex="-1" role="dialog" aria-labelledby="btnDeliveryBookedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="btnDeliveryBookedModalLabel">Delivery/pickup Booked Item</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <form id="frmDeliveryBookedItem" data-parsley-validate="true">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="delivery_booked_date" class="col-form-label">Delivery/pickup Booked Date <span style="color:red">*</span></label>
                            {{ Form::text('delivery_booked_date', null, [
                                    'class' => 'form-control form-control-md', 'id'=>'delivery_booked_date',
                                    'required',
                                ])
                            }}
                            {{
                                Form::hidden('booked_date',null,['id'=>'booked_date'])
                            }}
                            {{
                                Form::hidden('booked_time',null,['id'=>'booked_time'])
                            }}
                        </div>
                    </div>
                </form>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-info" id="btnDeliveryBookedItem">Save</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btnDeliveryBookedModalClose">Close</button  >
          </div>
      </div>
    </div>
</div>

<!-- btnCancelSaleModal Modal -->
<div class="modal fade" id="btnCancelSaleModal" tabindex="-1" role="dialog" aria-labelledby="btnCancelSaleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="btnCancelSaleModalLabel">Cancel Sale Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label>Are you sure to cancel sale this item?</label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="btnCancelSaleItem">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btnCancelSaleModalClose">No</button  >
            </div>
        </div>
    </div>
</div>

<!-- btnCreditNoteModal Modal -->
<div class="modal fade" id="btnCreditNoteModal" tabindex="-1" role="dialog" aria-labelledby="btnCreditNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="btnCreditNoteModalLabel">Credit Note Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label>Are you sure to credit note this item?</label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="btnCreditNoteItem">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btnCreditNoteModalClose">No</button  >
            </div>
        </div>
    </div>
</div>

<!-- btnCancelDispatchModal Modal -->
<div class="modal fade" id="btnCancelDispatchModal" tabindex="-1" role="dialog" aria-labelledby="btnCancelDispatchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="btnCancelDispatchModalLabel">Cancel Dispatch Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label>Are you sure to cancel dispatch this item?</label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="btnCancelDispatchItem">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btnCancelDispatchModalClose">No</button  >
            </div>
        </div>
    </div>
</div>