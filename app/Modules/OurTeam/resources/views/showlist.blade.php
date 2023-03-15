@extends('appshell::layouts.default')

@section('title')
    {{ __('Our Team') }}
@stop

@section('content')

    <div class="card card-accent-secondary">
        <div class="card-block">
                <table class="table table-hover" style="border: 1px solid #c9d0d0;">
                        <tr>
                            <td><a href="{{ route('our_team.our_teams.index') }}" class="btn btn-outline-primary">{{ __('Team Members') }}</a></td>
                        </tr>
                        <tr>
                            <td><a href="{{ route('our_team.our_teams.infoIndex') }}" class="btn btn-outline-primary">{{ __('Our Team Page') }}</a></td>
                        </tr>
                </table>
        </div>
    </div>

@stop
