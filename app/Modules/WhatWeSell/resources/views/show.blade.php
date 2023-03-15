@extends('appshell::layouts.default')

@section('title')
    {{ __('What We Sell Details') }}
@stop

@section('content')

    <div class="row">
        <div class="col-sm-6 col-md-6">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => 'layers',
                    'type' => 'success'
            ])
                {{ $whatwesell->title }}
                @slot('subtitle')

                @endslot
            @endcomponent
        </div>

        <div class="col-sm-6 col-md-6">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => 'time-countdown',
                    'type' => null
            ])

                @slot('subtitle')
                    {{ __('Created since') }}
                    {{ $whatwesell->created_at->format(__('Y-m-d H:i')) }}
                @endslot
            @endcomponent
        </div>

        @yield('widgets')

    </div>

    @yield('cards')

    <div class="card">
        <div class="card-block">
            <a href="{{ route('whatwesell.whatwesells.edit', $whatwesell) }}" class="btn btn-outline-primary">{{ __('Edit What We Sell')
            }}</a>

            @yield('actions')

            {!! Form::open(['route' => ['whatwesell.whatwesells.destroy', $whatwesell],
                                        'method' => 'DELETE',
                                        'data-confirmation-text' => __('Are you sure to delete this?'),
                                        'class' => 'float-right'
                                       ])
            !!}
                <button class="btn btn-outline-danger">
                    {{ __('Delete What We Sell') }}
                </button>
            {!! Form::close() !!}

        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12 col-md-12">
            <label class="control-label col-md-12">
                {{ __('Highlight Image') }}
            </label>
            <label class="control-label col-md-6">
                <img onclick="imagepreview(this)" lazyload="on" alt="Item Image" src="{{ $whatwesell->list_image_file_path }}" width="576px" height="576px">
            </label>
        </div>
        <div class="form-group col-sm-12 col-md-12">
            <label class="control-label col-md-12">
                {{ __('Banner Image') }}
            </label>
            <label class="control-label col-md-6">
                <img onclick="imagepreview(this)" lazyload="on" alt="Item Image" src="{{ $whatwesell->list_image_file_path }}" width="1920px" height="480px">
            </label>
        </div>
        <div class="form-group col-sm-12 col-md-12">
            <label class="control-label col-md-12">
                {{ __('Caption') }}
            </label>
            <label class="control-label col-md-12">
                {{ $whatwesell->caption }}
            </label>
        </div>
        <div class="form-group col-sm-12 col-md-12">
            <label class="control-label col-md-12">
                {{ __('Price Status') }}
            </label>
            <label class="control-label col-md-12">
                @if($whatwesell->price_status == 1)
                    Sold
                @else
                    -
                @endif
            </label>
        </div>
        <div class="form-group col-sm-12 col-md-12">
            <label class="control-label col-md-12">
                {{ __('Key Contact One') }}
            </label>
            <label class="control-label col-md-12">
                {!! $key_contact_1 !!}
            </label>
        </div>
        <div class="form-group col-sm-12 col-md-12">
            <label class="control-label col-md-12">
                {{ __('Key Contact Two') }}
            </label>
            <label class="control-label col-md-12">
                {!! $key_contact_2 !!}
            </label>
        </div>
    </div>
@stop
