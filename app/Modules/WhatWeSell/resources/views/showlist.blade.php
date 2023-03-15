@extends('appshell::layouts.default')

@section('title')
    {{ __('What We Sell') }}
@stop

@section('content')

    <div class="card card-accent-secondary">
        <div class="card-block">
            <table class="table table-hover" style="border: 1px solid #c9d0d0;">
                <tr>
                    <td><a href="{{ route('whatwesell.whatwesells.index') }}" class="btn btn-outline-primary">{{ __('What We Sell') }}</a></td>
                </tr>
                <tr>
                    <td><a href="{{ route('what_we_sell.what_we_sells.index') }}" class="btn btn-outline-primary">{{ __('What We Sell (New)') }}</a></td>
                </tr>
                <tr>
                    <td><a href="{{ route('whatwesell.whatwesells.infopage') }}" class="btn btn-outline-primary">{{ __('What We Sell Main Page') }}</a></td>
                </tr>
            </table>
        </div>
    </div>

@stop
