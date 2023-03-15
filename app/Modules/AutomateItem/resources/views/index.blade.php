@extends('appshell::layouts.default')

@section('title')
    {{ __('Automate Items') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')
        </div>

        {!! Form::model($item, ['route' => 'automate_item.automate_items.autocreate', 'data-parsley-validate'=>'true', 'autocomplete' => 'off']) !!}

        <div class="card-block">

            <div class="form-row">
                <div class="form-group col-12 col-md-4 col-xl-4">
                    <label class="form-control-label">{{ __('Lifecycle Type') }}</label>
                    <div class="input-group">
                        <label class="radio-inline" for="type_mp">
                            {{ Form::radio('lifecycle_type', 'marketplace', true, ['class'=>'lifecycle_type', 'id' => "type_mp"]) }}
                            Marketplace
                            &nbsp;
                        </label>
                        <label class="radio-inline" for="type_au">
                            {{ Form::radio('lifecycle_type', 'auction', null, ['class'=>'lifecycle_type', 'id' => "type_au"]) }}
                            Auction
                            &nbsp;
                        </label>
                        <label class="radio-inline" for="type_ps">
                            {{ Form::radio('lifecycle_type', 'privatesale', null, ['class'=>'lifecycle_type', 'id' => "type_ps"]) }}
                            Private Sale
                            &nbsp;
                        </label>
                    </div>
                </div>
                <div class="form-group col-12 col-md-4 col-xl-4">
                    <label class="form-control-label">{{ __('Item Count') }}</label>
                    {{ Form::number('item_count', 1, ['class'=>'form-control', 'min'=>'1', 'max'=>'10', 'required'] ) }}
                </div>
            </div>

            <div class="form-row">
                {{-- <div class="form-group col-12 col-md-4 col-xl-4">
                    <label class="form-control-label">{{ __('Item Name') }}</label>
                    {{ Form::text('name', null, ['class'=>'form-control'] ) }}
                </div> --}}
                <div class="form-group col-12 col-md-4 col-xl-4">
                    <label class="form-control-label">{{ __('Seller') }}<span style="color: red;">*</span></label>
                    {{ Form::select('seller_id', [], null, ['class'=>'form-control select2', 'id'=>'seller_id', 'required']) }}
                </div>
                <div class="form-group col-12 col-md-4 col-xl-4 divAuction">
                    <label class="form-control-label">{{ __('Auction') }}</label>
                    {{ Form::select('auction_id', $auctions, null, ['class'=>'form-control', 'id'=>'auction_id']) }}
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button class="btn btn-success">{{ __('Create') }}</button>
        </div>

        {!! Form::close() !!}

    </div>

@stop

@section('scripts')
<!-- Parsley CSS -->
<link rel="stylesheet" href="{{asset('plugins/Parsley.js-2.9.1/src/parsley.css')}}">
<!-- Parsley JS -->
<script src="{{asset('plugins/Parsley.js-2.9.1/dist/parsley.min.js')}}"></script>

<!-- Select2 CSS -->
<link href="{{asset('plugins\select2-develop\dist\css\select2.min.css')}}" rel="stylesheet" />
<link href="{{asset('plugins\select2-bootstrap4-theme-master\dist\select2-bootstrap4.min.css')}}" rel="stylesheet" />
<!-- Select2 JS -->
<script src="{{asset('plugins\select2-develop\dist\js\select2.full.min.js')}}"></script>

<script type="text/javascript">
    var select2customers = {!! json_encode($select2customers) !!};
    $(function() {

        $('#seller_id').val('');
        $('#seller_id').select2({allowClear:true}).empty();
        $('#seller_id').select2({data:select2customers});
        $('#seller_id').select2();

        $('.divAuction').hide();
        $('.lifecycle_type').change(function(){
            var lifecycle_type = $('.lifecycle_type:checked').val();
            console.log('lifecycle_type :',lifecycle_type);
            if(lifecycle_type == 'auction'){
                $('.divAuction').show();
            }else{
                $('.divAuction').hide();
            }
        });
    });
</script>
@stop
