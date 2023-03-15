{!! Form::model($customers, ['id'=>"customerFilterForm", 'route'  => ['customer.customers.fetch_data'], 'method' => 'POST', 'data-parsley-validate'=>"true"]) !!}

    <div class="row">
        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('Search Client') }}</label>
            {{ Form::text('search_text', null, [
                    'class'=>'form-control', 'id'=>'search_text'
                ])
            }}
        </div>
        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('Country') }}</label>
            {{ Form::select('country_id', [''=>'All'] + $countries, null, [
                    'class'=>'form-control', 'id'=>'country_id'
                ])
            }}
        </div>
        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('MCC') }}</label>
            {{ Form::select('main_client_contact', [''=>'All'] + $admin_users, null, [
                    'class'=>'form-control', 'id'=>'main_client_contact'
                ])
            }}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('Status') }}</label>
            {{ Form::select('status', [''=>'All', '1'=>'Active', '0'=>'Block'], null, [
                    'class'=>'form-control', 'id'=>'client_status'
                ])
            }}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-12 col-md-12 col-xl-12 text-right">
	        <button type="button" class="btn btn-md btn-outline-primary" id="btnSearch">{{ __('Search') }}</button>
            <button type="button" class="btn btn-md btn-outline-success float-right" id="btnResetAll">Reset All</button>
        </div>
    </div>

    <hr>
    <div class="row">
        <div class="form-group col-12 col-md-2 col-xl-2">
            <label class="form-control-label">{{ __('Per page') }}</label>
            {{ Form::select('per_page', ['10'=>'10', '50'=>'50', '100'=>'100'], null, [
                    'class'=>'form-control', 'id'=>'per_page'
                ])
            }}
        </div>
    </div>

{!! Form::close() !!}