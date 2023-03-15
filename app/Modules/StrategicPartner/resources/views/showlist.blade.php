@extends('appshell::layouts.default')

@section('title')
    {{ __('Strategic Partners') }}
@stop

@section('content')

    <div class="card card-accent-secondary">
        <div class="card-block">
                <table class="table table-hover" style="border: 1px solid #c9d0d0;">
                        <tr>
                            <td><a href="{{ route('strategic_partner.strategic_partners.index') }}" class="btn btn-outline-primary">{{ __('Strategic Partners') }}</a></td>
                        </tr>
                        <tr>
                            <td><a href="{{ route('strategic_partner.strategic_partners.infoIndex') }}" class="btn btn-outline-primary">{{ __('Strategic Partners Main Page') }}</a></td>
                        </tr>
                </table>
        </div>
    </div>

@stop
