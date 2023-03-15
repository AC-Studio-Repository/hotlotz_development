@extends('appshell::layouts.default')

@section('title')
    {{ __('Media Resource') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                <a href="{{ route('media_resource.media_resources.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create Media Resource') }}
                </a>
            </div>

        </div>

        <div class="card-block">
            <div class="table-responsive" style="overflow-x:auto;">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('File') }}</th>
                    <th style="width: 10%">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                @foreach($media_resources as $resource)
                    <tr>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                <a href="{{ route('media_resource.media_resources.show', $resource) }}">{{ $resource->title }}</a>
                            </span>
                        </td>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                {{ $resource->display_date }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ $resource->file_path }}" width="60px" height="60px">File</a>
                        </td>
                        <td>
                            <div class="mt-2">
                                @can('edit customers')
                                    <a href="{{route('media_resource.media_resources.edit',$resource)}}"
                                       class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>
                                @endcan

                                @can('delete customers')
                                    {!! Form::open(['route' => ['media_resource.media_resources.destroy',$resource->id],
                                                'method' => 'DELETE',
                                                'data-confirmation-text' => __('Are you sure to delete this :name?', ['name' => $resource->title])
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
            @if($media_resources->hasPages())
            <hr>
            <nav>
                {{ $media_resources->links() }}
            </nav>
        @endif
        </div>
    </div>

@stop
