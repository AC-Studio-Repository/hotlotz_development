{!! Form::model($customer_note, ['route' => ['customer.customers.note_update'], 'method' => 'POST', 'id'=>'frmEditNote', 'data-parsley-validate'=>'true', 'autocomplete' => 'off' ]) !!}

    <input type="hidden" name="customer_id" id="customer_id" value="{{ $customer_note->customer_id ?? null }}">
    <input type="hidden" name="note_id" id="note_id" value="{{ $customer_note->id ?? null }}">

    <div class="row">
        <div class="form-group col-md-12">
            <label class="form-control-label">{{ __('Note') }} <span style="color: red;">*</span></label>
            {{ Form::textarea('note', $customer_note->note ?? null, [
                    'class' => 'form-control form-control-md',
                    'rows'=>'5',
                    "required",
                ])
            }}
        </div>
    </div>

    <div class="row text-center">
        <div class="form-group col-md-12">       
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button  >
            <button class="btn btn-primary" id="updateNote">Update</button>
        </div>
    </div>
{!! Form::close() !!}