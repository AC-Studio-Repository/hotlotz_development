@extends('appshell::layouts.default')

@section('title')
    {{ __('Team Members') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create customers')
                <a href="{{ route('our_team.our_teams.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create New Team Member') }}
                </a>
                @endcan
            </div>

        </div>

        <div class="card-block">
            <div class="table-responsive" style="overflow-x:auto;">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Position') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Image') }}</th>
                    <th style="width: 10%">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                @foreach($our_teams as $our_team)
                    <tr>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                <a href="{{ route('our_team.our_teams.show', $our_team) }}">{{ $our_team->name }}</a>
                            </span>
                        </td>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                {!! $our_team->position !!}
                            </span>
                        </td>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                {{ $our_team->contact_email }}
                            </span>
                        </td>
                        <td>
                            <img onclick="imagepreview(this)" lazyload="on" alt="Image" src="{{ $our_team->full_path }}" width="60px" height="60px">
                        </td>
                        <td>
                            <div class="mt-2">
                                @can('edit customers')
                                    <a href="{{route('our_team.our_teams.edit',$our_team)}}"
                                       class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>
                                @endcan

                                @can('delete customers')
                                    {!! Form::open(['route' => ['our_team.our_teams.destroy',$our_team->id],
                                                'method' => 'DELETE',
                                                'data-confirmation-text' => __('Are you sure to delete this :name?', ['name' => $our_team->name])
                                                ])
                                        !!}
                                    <button class="btn btn-xs btn-outline-danger btn-show-on-tr-hover float-right">{{ __('Delete') }}</button>
                                    {!! Form::close() !!}
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
            </div>
            @if($our_teams->hasPages())
            <hr>
            <nav>
                {{ $our_teams->links() }}
            </nav>
        @endif
        </div>
    </div>

@stop
