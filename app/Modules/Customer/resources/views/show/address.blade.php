<div class="card-block">
    <div class="row">
        <div class="col-md-2">
            <label class="form-control-label">{{ __('Address List') }} </label>
        </div>
        <div class="col-md-10 text-right">
            <div class="form-group">
                @if($exist_correspondence_address != 'Y')
                    <button type="button" class="btn btn-outline-success" id="btnAddCorrespondence" data-toggle="modal" data-target="#addCorrespondenceAddressModal" >{{ __('Add New Correspondence Address') }}</button>
                @endif

                <button type="button" class="btn btn-outline-success" id="btnAddShipping" data-toggle="modal" data-target="#addShippingAddressModal" >{{ __('Add New Shipping Address') }}</button>

                @if($exist_kyc_address != 'Y')
                    <button type="button" class="btn btn-outline-success" id="btnAddKyc" data-toggle="modal" data-target="#addKycAddressModal" >{{ __('Add New KYC Address') }}</button>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <table class="table table-striped" id="invoices_table">
            <thead>
                <tr>
                    <th>Primary</th>
                    <th>Type</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Country</th>
                    <th>Postal Code</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach($addresses as $key => $address)
                    <tr>
                        <td class="text-center">
                            {{ Form::radio('is_primary', '1', ($address['is_primary'] == 1)?true:false, ['id' => "is_primary", 'disabled']) }}
                        </td>
                        <td>{{ ($address['type']!=null)?ucfirst($address['type']):null }}</td>
                        <td>{{ $address['address'] }}</td>
                        <td>{{ $address['city'] }}</td>
                        <td>{{ $address['state'] }}</td>
                        <td>{{ $address['country_name'] }}</td>
                        <td>{{ $address['postalcode'] }}</td>
                        <td>
                            <button type="button" class="btn btn-outline-success" id="btnAddressEdit" data-toggle="modal" data-target="#addressEditModal" data-id="{{ $address['address_id'] }}" >{{ __('Edit') }}</button>

                            <a href="#" id="{{ $address['address_id'] }}" class="btn btn-sm btn-outline-danger" onclick="delete_address(this.id);">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add New Correspondence Address Modal -->
<div class="modal fade" id="addCorrespondenceAddressModal" tabindex="-1" role="dialog" aria-labelledby="addCorrespondenceAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCorrespondenceAddressModalLabel">Add New Correspondence Address Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- @include('customer::show.correspondence_address_detail') -->
                {!! Form::open(['route' => ['customer.customers.address_create'], 'method' => 'POST', 'id'=>'frmCorrespondenceAddress', 'data-parsley-validate'=>'true', 'autocomplete' => 'off' ]) !!}
                    <input type="hidden" name="type" id="type" value="correspondence">
                    @include('customer::show.edit_address_detail', array('type'=>'correspondence', 'address_detail'=>null))
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<!-- Add New Shipping Address Modal -->
<div class="modal fade" id="addShippingAddressModal" tabindex="-1" role="dialog" aria-labelledby="addShippingAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addShippingAddressModalLabel">Add New Shipping Address Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- @include('customer::show.shipping_address_detail') -->
                {!! Form::open(['route' => ['customer.customers.address_create'], 'method' => 'POST', 'id'=>'frmShippingAddress', 'data-parsley-validate'=>'true', 'autocomplete' => 'off' ]) !!}
                    <input type="hidden" name="type" id="type" value="shipping">
                    @include('customer::show.edit_address_detail', array('type'=>'shipping', 'address_detail'=>null))
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<!-- Address Edit Modal -->
<div class="modal fade" id="addressEditModal" tabindex="-1" role="dialog" aria-labelledby="addressEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addressEditModalLabel">Address Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => ['customer.customers.address_update'], 'method' => 'POST', 'id'=>'frmAddress', 'data-parsley-validate'=>'true', 'autocomplete' => 'off' ]) !!}
                    <div id="divAddress">
                        @include('customer::show.edit_address_detail', array('type'=>null, 'address_detail'=>null))
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<!-- Add New KYC Address Modal -->
<div class="modal fade" id="addKycAddressModal" tabindex="-1" role="dialog" aria-labelledby="addKycAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addKycAddressModalLabel">Add New KYC Address Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => ['customer.customers.address_create'], 'method' => 'POST', 'id'=>'frmKycAddress', 'data-parsley-validate'=>'true', 'autocomplete' => 'off' ]) !!}
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="form-control-label">{{ __('Existing Address') }}</label>
                            {{ Form::select('select_address_id', $address_list, null, [
                                    'class'=>'form-control' . ($errors->has('country_id') ? ' is-invalid' : ''),
                                    'id'=>'select_address_id',
                                ])
                            }}
                        </div>
                    </div>

                    <input type="hidden" name="type" id="type" value="kyc">
                    <div id="divAddress">
                        @include('customer::show.edit_address_detail', array('type'=>'kyc', 'address_detail'=>null))
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@section('scripts')
@parent

<script type="text/javascript">
    
    $(document).on( 'change', '#select_address_id', function(){
        var select_address_id = $(this).val();
        console.log('select_address_id : ', select_address_id);
        getAddress(select_address_id, $('#addKycAddressModal .modal-body #divAddress'), 'kyc');
    });
    
    $(document).on( 'click', '#btnAddressEdit', function(){
        var address_id = $(this).attr('data-id');
        getAddress(address_id, $('#addressEditModal .modal-body #divAddress'));
    });

    function getAddress(address_id, objModal, type=''){
        $.ajax({
            url: "/manage/customers/get_address",
            type: 'post',
            data: "customer_id=" + customer_id + "&address_id=" + address_id + "&type=" + type + "&_token=" + _token,
            dataType: 'json',
            async: false,
            success: function(response) {
                if(response.status == '1') {
                    objModal.html(response.html);
                }
            }
        });
    }

    function delete_address(address_id) {
        var response = confirm("Are you sure to delete this Address?");
        if (response == true) {
            $.ajax({
                url: "/manage/customers/delete_address",
                type: 'post',
                data: "customer_id=" + customer_id + "&address_id=" + address_id + "&_token=" + _token,
                dataType: 'json',
                async: false,
                success: function(response) {
                    if(response.status == 'success') {
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