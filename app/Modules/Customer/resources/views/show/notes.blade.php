<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Notes List') }} </label>
    </div>
    <div class="col-md-8 text-right">
        <div class="form-group">
            <button type="button" class="btn btn-outline-success" id="btnAddNote" data-toggle="modal" data-target="#addNoteModal" >{{ __('Add Note') }}</button>
        </div>
    </div>
</div>
<div class="row">
    <table class="table table-striped" id="invoices_table">
        <thead>
            <tr>
                <th width="15%">Admin</th>
                <th width="30%">Note</th>
                <th width="10%">Date</th>
                <th width="10%">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notes as $key => $note)
                <tr>
                    <td>{{ $note['admin_name'] }}</td>
                    <td>{{ $note['note'] }}</td>
                    <td>{{ $note['date'] }}</td>
                    <td>
                        @if($admin_id == $note['user_id'])
                            <button type="button" class="btn btn-outline-success" id="btnNoteEdit" data-toggle="modal" data-target="#editNoteModal" data-id="{{ $note['note_id'] }}" >{{ __('Edit') }}</button>

                            <a href="#" id="{{ $note['note_id'] }}" class="btn btn-sm btn-outline-danger" onclick="delete_note(this.id);">Delete</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Note Modal -->
<div class="modal fade" id="addNoteModal" tabindex="-1" role="dialog" aria-labelledby="addNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNoteModalLabel">Add New Note</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            	{!! Form::model($customer, ['route' => ['customer.customers.note_create'], 'method' => 'POST', 'id'=>'frmAddNote', 'data-parsley-validate'=>'true', 'autocomplete' => 'off' ]) !!}

                    <input type="hidden" name="customer_id" id="customer_id" value="{{ $customer->id ?? null }}">
				    <div class="row">
				        <div class="form-group col-md-12">
				            <label class="form-control-label">{{ __('Note') }} <span style="color: red;">*</span></label>
				            {{ Form::textarea('note', null, [
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
				            <button class="btn btn-primary" id="addNote">Save</button>
				        </div>
				    </div>
				{!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<!-- Edit Note Modal -->
<div class="modal fade" id="editNoteModal" tabindex="-1" role="dialog" aria-labelledby="editNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editNoteModalLabel">Address Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('customer::show.edit_note_detail', array('customer_note'=>null))
            </div>
        </div>
    </div>
</div>

@section('scripts')
@parent
<script type="text/javascript">
    $(document).on( 'click', '#btnNoteEdit', function(){
        var note_id = $(this).attr('data-id');
        $.ajax({
            url: "/manage/customers/get_note",
            type: 'post',
            data: "customer_id=" + customer_id + "&note_id=" + note_id + "&_token=" + _token,
            dataType: 'json',
            async: false,
            success: function(response) {
                if(response.status == '1') {
                    $('#editNoteModal .modal-body').html(response.html);
                }
            }
        });
    });

    function delete_note(customer_note_id) {
        var response = confirm("Are you sure to delete this Note?");
        if (response == true) {
            $.ajax({
                url: "/manage/customers/delete_note",
                type: 'post',
                data: "customer_id=" + customer_id + "&note_id=" + customer_note_id + "&_token=" + _token,
                dataType: 'json',
                async: false,
                success: function(response) {
                    if(response.status == '1') {
                        location.reload();
                    }else {
                        alert(response.message);
                        return false;
                    }
                }
            });
        }
    }
</script>
@stop