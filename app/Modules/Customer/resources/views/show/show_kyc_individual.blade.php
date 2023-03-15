<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-control-label">{{ __('Full Name ') }}<span style="color: red; font-size: 0.75rem;">{{ __('(as written in Identification Document)') }}</span></label>
            {{ Form::text('legal_name', $customer->legal_name, [
                    'class' => 'form-control form-control-md',
                    'id' => 'legal_name',
                    'disabled'
                ])
            }}
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Date of Birth') }}</label>
        <div class="form-group">
            {{ Form::text('date_of_birth', $customer->date_of_birth, [
                    'class' => 'form-control form-control-md',
                    'id' => 'date_of_birth',
                    'disabled'
                ])
            }}
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Contact Number') }}</label>
        <div class="form-group">
            {{ Form::text('contact_number', $customer->dialling_code.' '.$customer->phone, [
                    'class' => 'form-control form-control-md',
                    'disabled'=>'true'
                ])
            }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Email') }}</label>
        <div class="form-group">
            {{ Form::text('email', $customer->email, [
                    'class' => 'form-control form-control-md',
                    'disabled'=>'true'
                ])
            }}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-control-label">{{ __('Occupation ') }}</label>
            {{ Form::text('occupation', $customer->occupation, [
                    'class' => 'form-control form-control-md',
                    'id' => 'occupation',
                    'disabled'
                ])
            }}
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Citizenship') }}</label>
        <div class="form-group">
            {{ Form::select('citizenship_one', $countries, $customer->citizenship_one ?? 702, [
                    'class' => 'form-control form-control-md',
                    'id' => 'citizenship_one',
                    'disabled'
                ])
            }}
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-4">
        @php
            if($customer->id_type == null){
                $customer->id_type = 'nric';
            }
        @endphp
        <label class="form-control-label">{{ __('Identification Type') }}</label>
        <div class="input-group">
            <label class="radio-inline" for="id_type_nric">
                {{ Form::radio('id_type', 'nric', ($customer->id_type == 'nric')?true:false, ['id' => "id_type_nric", 'disabled']) }}
                NRIC
                &nbsp;
            </label>
            <label class="radio-inline" for="id_type_fin">
                {{ Form::radio('id_type', 'fin', ($customer->id_type == 'fin')?true:false, ['id' => "id_type_fin", 'disabled']) }}
                FIN
                &nbsp;
            </label>
            <label class="radio-inline" for="id_type_passport">
                {{ Form::radio('id_type', 'passport', ($customer->id_type == 'passport')?true:false, ['id' => "id_type_passport", 'disabled']) }}
                PASSPORT
                &nbsp;
            </label>
        </div>
    </div>
    @if($customer->id_type == 'nric')
    <div class="col-md-4" >
        <div class="form-group">
            <label class="form-control-label">{{ __('NRIC Number ') }}</label>
            {{ Form::text('nric', $customer->nric, [
                    'class' => 'form-control form-control-md',
                    'id' => 'nric',
                    'disabled'
                ])
            }}
        </div>
    </div>
    @endif
    @if($customer->id_type == 'fin')
    <div class="col-md-4" >
        <div class="form-group">
            <label class="form-control-label">{{ __('FIN Number ') }}</label>
            {{ Form::text('fin', $customer->fin, [
                    'class' => 'form-control form-control-md',
                    'id' => 'fin',
                    'disabled'
                ])
            }}
        </div>
    </div>
    @endif
    @if($customer->id_type == 'passport')
    <div class="col-md-4" >
        <div class="form-group">
            <label class="form-control-label">{{ __('Passport Number ') }}</label>
            {{ Form::text('passport', $customer->passport, [
                    'class' => 'form-control form-control-md',
                    'id' => 'passport',
                    'disabled'
                ])
            }}
        </div>
    </div>
    @endif
</div>

@if($customer->id_type == 'passport')
<div class="row" >
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-control-label">{{ __('Country of Issue ') }}</label>
            {{ Form::select('country_of_issue', $countries, $customer->country_of_issue ?? null, [
                    'class' => 'form-control form-control-md',
                    'id' => 'country_of_issue',
                    'disabled'
                ])
            }}
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Passport Expiry Date') }}</label>
        <div class="form-group">
            {{ Form::text('passport_expiry_date', $customer->passport_expiry_date, [
                    'class' => 'form-control form-control-md',
                    'id' => 'passport_expiry_date',
                    'disabled'
                ])
            }}
        </div>
    </div>
</div>
@endif

@if(count($doc_data['doc']) > 0)
<div class="row">
    <label class="form-control-label col-12 col-md-12 col-xl-12">{{ __('Identification Document') }}</label>
    @foreach($doc_data['doc'] as $key => $document)
        <div class="form-group col-md-4">
            <label style="width: 300px; height: 300px; text-align: center;">
                @if($document['ext'] === 'pdf')
                    <embed src="{{ $document['full_path'] }}" type="application/pdf" width="100%" height="250px"></embed>
                    <!-- <object
                        data="{{ $document['full_path'] }}"
                        type="application/pdf"
                        width="100%"
                        height="100%"
                    >
                        <p>
                            Your browser does not support PDFs.
                            <a href="{{ $document['full_path'] }}">Download the PDF</a>
                            .
                        </p>
                    </object> -->
                @else
                    <img onclick="imagepreview(this)" lazyload="on" src="{{ $document['full_path'] }}" alt="{{ $document['file_name'] }}" style="width:auto;height:auto;max-width:100%;max-height:100%;">
                @endif
            </label>
        </div>
    @endforeach
</div>
@endif

<div class="row">
    <div class="form-group col-md-4">
        @php
            if($customer->citizenship_type == null){
                $customer->citizenship_type = 'single';
            }
        @endphp
        <label class="form-control-label">{{ __('Do you hold dual citizenship?') }}</label>
        <div class="input-group">
            <label class="radio-inline" for="citizenship_type_single">
                {{ Form::radio('citizenship_type', 'dual', ($customer->citizenship_type == 'dual')?true:false, ['id' => "citizenship_type_dual", 'disabled']) }}
                Yes
                &nbsp;
            </label>
            <label class="radio-inline" for="citizenship_type_dual">
                {{ Form::radio('citizenship_type', 'single', ($customer->citizenship_type == 'single')?true:false, ['id' => "citizenship_type_single", 'disabled']) }}
                No
                &nbsp;
            </label>
        </div>
    </div>
    @if($customer->citizenship_type == 'dual')
    <div class="col-md-4">        
        <label class="form-control-label">{{ __('Second Citizenship') }}</label>
        <div class="form-group">
            {{ Form::select('citizenship_two', $countries, $customer->citizenship_two ?? null, [
                    'class' => 'form-control form-control-md',
                    'id' => 'citizenship_two',
                    'disabled'
                ])
            }}
        </div>
    </div>
    @endif

    <div class="col-md-4">
        <label class="form-control-label">Uploaded Date</label>
        <div class="form-group">
            {{ Form::text('uploaded_date', ($customer->uploaded_date) ? date("Y-m-d h:i A", strtotime($customer->uploaded_date)) : null, [
                    'class' => 'form-control form-control-md',
                    'id' => 'uploaded_date',
                    'disabled'
                ])
            }}
        </div>
    </div>
</div>

@if($kyc_address)
@php
    $country = \App\Models\Country::where('id', '=', $kyc_address->country_id)->first();
@endphp
<div class="row">
    <div class="col-md-12">
        <label class="form-control-label">Address</label>
        <table class="table table-striped" id="invoices_table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Country</th>
                    <th>Postal Code</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $kyc_address->firstname. ' ' . $kyc_address->lastname}}</td>
                    <td>{{ $kyc_address->address }}</td>
                    <td>{{ $kyc_address->city }}</td>
                    <td>{{ $kyc_address->state }}</td>
                    <td>{{ ($country)? $country->name : null }}</td>
                    <td>{{ $kyc_address->postalcode }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endif

@if($customer->kyc_status == 'complete')
    <div class="row">
        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('Approver') }}</label>
            {{ Form::select('kyc_approver_id', $approvers, ($customer->kyc_approver_id > 0)? (integer)$customer->kyc_approver_id:null, [
                    'class'=>'form-control',
                    'id' => 'kyc_approver_id',
                    (isset($customer->kyc_approver_id) && $customer->kyc_approver_id > 0)?'disabled':null,
                ])
            }}

            @if ($errors->has('kyc_approver_id'))
                <input hidden class="form-control is-invalid">
                <div class="invalid-feedback">{{ $errors->first('kyc_approver_id') }}</div>
            @endif
        </div>
        @if( $customer->is_kyc_approved != 'Y' )
        <div class="form-group col-12 col-md-4 col-xl-4" id="divKycApproveButton">
            <label class="form-control-label">&nbsp;</label>
                <div>
                    <button type="button" class="btn btn-primary" id="btnKycApprove">{{ __('Approve') }}</button>
                </div>
        </div>
        @endif
    </div>
    @if( $customer->is_kyc_approved == 'Y' )
    <div class="row">
        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('Approved at ') }} {{ date_format(date_create($customer->kyc_approval_date), 'Y-m-d h:i A') }}</label>
        </div>
    </div>
    @endif
@endif

@section('scripts')
@parent
<script type="text/javascript">    
    $(function(){
        $('#btnKycApprove').click(function(){
            var kyc_approver_id = $('#kyc_approver_id').val();
            $.ajax({
                url: "/manage/customers/{{ $customer->id }}/kyc_approve",
                type: 'post',
                data: "kyc_approver_id="+kyc_approver_id+"&_token="+_token,
                dataType: 'json',
                async: false,
                success: function(data) {
                    if(data.status == 'success'){
                        $('#divKycApproveButton').hide();
                        $('#kyc_approver_id').attr('disabled','true');
                        location.reload();
                    }
                }
            });
        });
    });
</script>
@stop