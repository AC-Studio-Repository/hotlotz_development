<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-control-label">{{ __('Full Name ') }}<span style="color: red; font-size: 0.75rem;">{{ __('(as written in Identification Document)') }}</span></label>
            {{ Form::text('legal_name', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('legal_name') ? ' is-invalid' : ''),
                    'id' => 'legal_name',
                ])
            }}

            @if ($errors->has('legal_name'))
                <div class="form-control-feedback">{{ $errors->first('legal_name') }}</div>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Date of Birth') }}</label>
        <div class="form-group">
            {{ Form::text('date_of_birth', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('date_of_birth') ? ' is-invalid' : ''),
                    'id' => 'date_of_birth',
                ])
            }}
            {{
               Form::hidden('birth_date',null,['id'=>'birth_date'])
            }}
            @if ($errors->has('date_of_birth'))
                <div class="invalid-feedback">{{ $errors->first('date_of_birth') }}</div>
            @endif
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
            {{ Form::text('occupation', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('occupation') ? ' is-invalid' : ''),
                    'id' => 'occupation',
                ])
            }}

            @if ($errors->has('occupation'))
                <div class="form-control-feedback">{{ $errors->first('occupation') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-control-label">{{ __('Citizenship') }}</label>
        <div class="form-group">
            {{ Form::select('citizenship_one', $countries, $customer->citizenship_one ?? 702, [
                    'class' => 'form-control form-control-md' . ($errors->has('citizenship_one') ? ' is-invalid' : ''),
                    'id' => 'citizenship_one'
                ])
            }}

            @if ($errors->has('citizenship_one'))
                <div class="invalid-feedback">{{ $errors->first('citizenship_one') }}</div>
            @endif
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
                {{ Form::radio('id_type', 'nric', ($customer->id_type == 'nric')?true:false, ['id' => "id_type_nric", 'v-model' =>
                'idType']) }}
                NRIC
                &nbsp;
            </label>
            <label class="radio-inline" for="id_type_fin">
                {{ Form::radio('id_type', 'fin', ($customer->id_type == 'fin')?true:false, ['id' => "id_type_fin", 'v-model' =>
                'idType']) }}
                FIN
                &nbsp;
            </label>
            <label class="radio-inline" for="id_type_passport">
                {{ Form::radio('id_type', 'passport', ($customer->id_type == 'passport')?true:false, ['id' => "id_type_passport", 'v-model' =>
                'idType']) }}
                PASSPORT
                &nbsp;
            </label>
        </div>

        @if ($errors->has('id_type'))
            <div class="invalid-feedback">{{ $errors->first('id_type') }}</div>
        @endif
    </div>
    <div class="col-md-4"  v-show="idType == 'nric'">
        <div class="form-group">
            <label class="form-control-label">{{ __('NRIC Number ') }}</label>
            {{ Form::text('nric', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('nric') ? ' is-invalid' : ''),
                    'id' => 'nric',
                ])
            }}

            @if ($errors->has('nric'))
                <div class="form-control-feedback">{{ $errors->first('nric') }}</div>
            @endif
        </div>
    </div>
    <div class="col-md-4"  v-show="idType == 'fin'">
        <div class="form-group">
            <label class="form-control-label">{{ __('FIN Number ') }}</label>
            {{ Form::text('fin', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('fin') ? ' is-invalid' : ''),
                    'id' => 'fin',
                ])
            }}

            @if ($errors->has('fin'))
                <div class="form-control-feedback">{{ $errors->first('fin') }}</div>
            @endif
        </div>
    </div>
    <div class="col-md-4"  v-show="idType == 'passport'">
        <div class="form-group">
            <label class="form-control-label">{{ __('Passport Number ') }}</label>
            {{ Form::text('passport', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('passport') ? ' is-invalid' : ''),
                    'id' => 'passport',
                ])
            }}

            @if ($errors->has('passport'))
                <div class="form-control-feedback">{{ $errors->first('passport') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="row"  v-show="idType == 'passport'">
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-control-label">{{ __('Country of Issue ') }}</label>
            {{ Form::select('country_of_issue', $countries, $customer->country_of_issue ?? null, [
                    'class' => 'form-control form-control-md' . ($errors->has('country_of_issue') ? ' is-invalid' : ''),
                    'id' => 'country_of_issue'
                ])
            }}

            @if ($errors->has('country_of_issue'))
                <div class="form-control-feedback">{{ $errors->first('country_of_issue') }}</div>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Passport Expiry Date') }}</label>
        <div class="form-group">
            {{ Form::text('passport_expiry_date', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('passport_expiry_date') ? ' is-invalid' : ''),
                    'id' => 'passport_expiry_date',
                ])
            }}
            {{
               Form::hidden('passport_ep_date',null,['id'=>'passport_ep_date'])
            }}
            @if ($errors->has('passport_expiry_date'))
                <div class="invalid-feedback">{{ $errors->first('passport_expiry_date') }}</div>
            @endif
        </div>
    </div>
</div>
<div class="row"  v-show="idType == 'nric'">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Identification Document') }}</label>
        <div class="file-loading">
            <input id="nric_document" name="nric_document[]" type="file" multiple id="nric_document">
        </div>
        
        <input type="text" style="display: none;" name="hide_nric_doc_ids" id="hide_nric_doc_ids" value="{{$hide_nric_doc_ids}}" data-parsley-required="false" data-parsley-errors-container="#error_nric_document_block" data-parsley-required-message="Please select and upload at least one NRIC Document!">
        <div id="error_nric_document_block" class="help-block"></div>
    </div>
</div>
<div class="row"  v-show="idType == 'fin'">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Identification Document') }}</label>
        <div class="file-loading">
            <input id="fin_document" name="fin_document[]" type="file" multiple id="fin_document">
        </div>
        
        <input type="text" style="display: none;" name="hide_fin_doc_ids" id="hide_fin_doc_ids" value="{{$hide_fin_doc_ids}}" data-parsley-required="false" data-parsley-errors-container="#error_fin_document_block" data-parsley-required-message="Please select and upload at least one FIN Document!">
        <div id="error_fin_document_block" class="help-block"></div>
    </div>
</div>
<div class="row"  v-show="idType == 'passport'">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Identification Document') }}</label>
        <div class="file-loading">
            <input id="passport_document" name="passport_document[]" type="file" multiple id="passport_document">
        </div>
        
        <input type="text" style="display: none;" name="hide_passport_doc_ids" id="hide_passport_doc_ids" value="{{$hide_passport_doc_ids}}" data-parsley-required="false" data-parsley-errors-container="#error_passport_document_block" data-parsley-required-message="Please select and upload at least one Passport Document!">
        <div id="error_passport_document_block" class="help-block"></div>
    </div>
</div>

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
                {{ Form::radio('citizenship_type', 'dual', ($customer->citizenship_type == 'dual')?true:false, ['id' => "citizenship_type_dual", 'v-model' =>
                'citizenshipType']) }}
                Yes
                &nbsp;
            </label>
            <label class="radio-inline" for="citizenship_type_dual">
                {{ Form::radio('citizenship_type', 'single', ($customer->citizenship_type == 'single')?true:false, ['id' => "citizenship_type_single", 'v-model' =>
                'citizenshipType']) }}
                No
                &nbsp;
            </label>
        </div>

        @if ($errors->has('citizenship_type'))
            <div class="invalid-feedback">{{ $errors->first('citizenship_type') }}</div>
        @endif
    </div>

    <div class="col-md-4" v-show="citizenshipType == 'dual'">
        <label class="form-control-label">Secondary Citizenship</label>
        <div class="form-group">
            {{ Form::select('citizenship_two', $countries, $customer->citizenship_two ?? null, [
                    'class' => 'form-control form-control-md' . ($errors->has('citizenship_two') ? ' is-invalid' : ''),
                    'id' => 'citizenship_two'
                ])
            }}

            @if ($errors->has('citizenship_two'))
                <div class="invalid-feedback">{{ $errors->first('citizenship_two') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-control-label">Uploaded Date</label>
        <div class="form-group">
            <input type="datetime-local" id="uploaded_date" name="uploaded_date" value="{{ ($customer->uploaded_date) ? strftime('%Y-%m-%dT%H:%M:%S', strtotime($customer->uploaded_date)) : null }}">

            @if ($errors->has('uploaded_date'))
                <div class="invalid-feedback">{{ $errors->first('uploaded_date') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <label class="form-control-label" style="color: red;">If you want to edit the KYC address of the client, please go to the Contact Details tab and edit in the address table.</label>
    </div>
</div>

@section('scripts')
@parent
<script type="text/javascript">
    var nric_initialpreview = {!! json_encode($nric_initialpreview) !!};
    var nric_initialpreviewconfig = {!! json_encode($nric_initialpreviewconfig) !!};
    var fin_initialpreview = {!! json_encode($fin_initialpreview) !!};
    var fin_initialpreviewconfig = {!! json_encode($fin_initialpreviewconfig) !!};
    var passport_initialpreview = {!! json_encode($passport_initialpreview) !!};
    var passport_initialpreviewconfig = {!! json_encode($passport_initialpreviewconfig) !!};

    $(function(){

        $('#date_of_birth').focus(function(){
            $('#date_of_birth').click();
        });
        $( "#date_of_birth" ).click(function() {
            $('#birth_date').hide();
            $('#birth_date').pickadate({
                format: 'yyyy-mm-dd',
                onSet:function(){
                    let date = $('#birth_date').val();
                    $('#date_of_birth').val(date);
                }
            })
            $('#birth_date').click();
        });

        $('#passport_expiry_date').focus(function(){
            $('#passport_expiry_date').click();
        });
        $( "#passport_expiry_date" ).click(function() {
            $('#passport_ep_date').hide();
            $('#passport_ep_date').pickadate({
                format: 'yyyy-mm-dd',
                onSet:function(){
                    let date = $('#passport_ep_date').val();
                    $('#passport_expiry_date').val(date);
                }
            })
            $('#passport_ep_date').click();
        });

        $('#citizenship_one').change(function(){
            var citizenship_one = $('#citizenship_one').find(":selected").val();
            // console.log('citizenship_one ', citizenship_one);
            $('#country_of_issue').val(citizenship_one).change();
        });

        //#NRIC Documents
        $("#nric_document").fileinput({
            theme: "fas",
            uploadUrl: '/manage/customers/{{$customer->id}}/document_upload/nric',
            uploadAsync: true,
            uploadExtraData: function() {
                return {
                    _token: _token,
                    customer_id: $("#customer_id").val()
                };
            },
            showPreview : true,
            showCancel : false,
            fileActionSettings: {
                showUpload: false,
                showDrag: false,
            },
            minFileCount: 1,
            maxTotalFileCount: 2,
            allowedFileExtensions: ["jpg", "jpeg","pdf","png"],
            initialPreviewFileType: 'image',
            overwriteInitial: false,
            initialPreviewAsData: true,
            initialPreview: nric_initialpreview,
            initialPreviewConfig: nric_initialpreviewconfig,
        }).on('fileuploaded', function(event, data, previewId, index) {
            if(data.response.ids){
                $('#error_nric_document_block').html('');
                var hide_nric_doc_ids_val = $("#hide_nric_doc_ids").val();
                hide_nric_doc_ids_val += data.response.ids[0] + ',';
                $('#hide_nric_doc_ids').val(hide_nric_doc_ids_val);
            }
        }).on('filesuccessremove', function(event, id) {

        }).on("filedeleted", function(event,key,data) {
            var customer_img_id = (JSON.parse(data.responseText)).customer_document_id;
            var hide_nric_doc_ids = $("#hide_nric_doc_ids").val();
            hide_nric_doc_ids = hide_nric_doc_ids.replace((customer_img_id + ','),'');
            $('#hide_nric_doc_ids').val(hide_nric_doc_ids);
        });

        //#FIN Documents
        $("#fin_document").fileinput({
            theme: "fas",
            uploadUrl: '/manage/customers/{{$customer->id}}/document_upload/fin',
            uploadAsync: true,
            uploadExtraData: function() {
                return {
                    _token: _token,
                    customer_id: $("#customer_id").val()
                };
            },
            showPreview : true,
            showCancel : false,
            fileActionSettings: {
                showUpload: false,
                showDrag: false,
            },
            minFileCount: 1,
            maxTotalFileCount: 2,
            allowedFileExtensions: ["jpg", "jpeg","pdf","png"],
            initialPreviewFileType: 'image',
            overwriteInitial: false,
            initialPreviewAsData: true,
            initialPreview: fin_initialpreview,
            initialPreviewConfig: fin_initialpreviewconfig,
        }).on('fileuploaded', function(event, data, previewId, index) {
            if(data.response.ids){
                $('#error_fin_document_block').html('');
                var hide_fin_doc_ids_val = $("#hide_fin_doc_ids").val();
                hide_fin_doc_ids_val += data.response.ids[0] + ',';
                $('#hide_fin_doc_ids').val(hide_fin_doc_ids_val);
            }
        }).on('filesuccessremove', function(event, id) {

        }).on("filedeleted", function(event,key,data) {
            var customer_img_id = (JSON.parse(data.responseText)).customer_document_id;
            var hide_fin_doc_ids = $("#hide_fin_doc_ids").val();
            hide_fin_doc_ids = hide_fin_doc_ids.replace((customer_img_id + ','),'');
            $('#hide_fin_doc_ids').val(hide_fin_doc_ids);
        });

        //#Passport Documents
        $("#passport_document").fileinput({
            theme: "fas",
            uploadUrl: '/manage/customers/{{$customer->id}}/document_upload/passport',
            uploadAsync: true,
            uploadExtraData: function() {
                return {
                    _token: _token,
                    customer_id: $("#customer_id").val()
                };
            },
            showPreview : true,
            showCancel : false,
            fileActionSettings: {
                showUpload: false,
                showDrag: false,
            },
            minFileCount: 1,
            maxTotalFileCount: 1,
            allowedFileExtensions: ["jpg", "jpeg","pdf","png"],
            initialPreviewFileType: 'image',
            overwriteInitial: false,
            initialPreviewAsData: true,
            initialPreview: passport_initialpreview,
            initialPreviewConfig: passport_initialpreviewconfig,
        }).on('fileuploaded', function(event, data, previewId, index) {
            if(data.response.ids){
                $('#error_passport_document_block').html('');
                var hide_passport_doc_ids_val = $("#hide_passport_doc_ids").val();
                hide_passport_doc_ids_val += data.response.ids[0] + ',';
                $('#hide_passport_doc_ids').val(hide_passport_doc_ids_val);
            }
        }).on('filesuccessremove', function(event, id) {

        }).on("filedeleted", function(event,key,data) {
            var customer_img_id = (JSON.parse(data.responseText)).customer_document_id;
            var hide_passport_doc_ids = $("#hide_passport_doc_ids").val();
            hide_passport_doc_ids = hide_passport_doc_ids.replace((customer_img_id + ','),'');
            $('#hide_passport_doc_ids').val(hide_passport_doc_ids);
        });

    });
</script>
@stop