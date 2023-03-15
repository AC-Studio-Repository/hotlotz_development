@extends('appshell::layouts.default')

@section('title')
    {{ __('Team Members') }}
@stop

@section('content')

<div class="card card-accent-secondary">
    <div class="card-header">
        @yield('title')
        <div class="card-actionbar">
            <a href="{{ route('our_team.our_teams.create') }}" class="btn btn-sm btn-outline-success float-right">
                <i class="zmdi zmdi-plus"></i>
                {{ __('Create Team Member') }}
            </a>
        </div>
    </div>

    {!! Form::open(['route' => ['our_team.our_teams.team_member_reordering'], 'data-parsley-validate'=>'true', 'autocomplete' => 'off']) !!}

        <div class="card-block">
            <div class="row">
                <div id="our_teams" class="list-group col-md-12">
                    @foreach($our_teams as $key => $ourteam)
                        <div class="ourteam list-item mb-1" data-id="{{ $ourteam->order }}">
                            <img onclick="" lazyload="on" class="list_wh" src="{{ $ourteam->full_path }}">
                            &nbsp; &nbsp;

                            <input type="hidden" name="ourteam_id[]" value="{{ $ourteam->id }}" >

                            <span class="ourteam-description">
                                <a href="{{ route('our_team.our_teams.show', ['our_team' => $ourteam ]) }}" target="_blank">{{ $ourteam->name }}</a>
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button class="btn btn-success" id="btnTeamMemberReorder">{{ __('Update Order') }}</button>
        </div>

    {!! Form::close() !!}
    
</div>
@stop

@section('scripts')

<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<style type="text/css">
    .list-item {
        position: relative;
        display: block;
        padding: .75rem 1.25rem;
        margin-bottom: -1px;
        background-color: #fff;
        border: 1px solid rgba(0,0,0,.125);
    }
    .list_wh {        
        width: 150px;
        height: auto;
    }
</style>

<script type="text/javascript">
    $(function(){

        var sortable = new Sortable(our_teams,{
            group: "our_teams",
            multiDrag: true,
            selectedClass: 'selected',
            animation: 150
        });

    });
</script>

@stop