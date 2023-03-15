@extends('appshell::layouts.default')

@section('title')
    {{ __('What We Sell') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">

                <a href="{{ route('whatwesell.whatwesells.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create What We Sell') }}
                </a>
            </div>

        </div>

        <div class="card-block">
            <div class="table-responsive" style="overflow-x:auto;">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Image') }}</th>
                    <th style="width: 10%">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                @foreach($whatWeSells as $whatwesell)
                    <tr>
                        <td>
                            @can('view content managements')
                            <span class="font-lg mb-3 font-weight-bold">
                                <a href="{{ route('whatwesell.whatwesells.show', $whatwesell) }}">{{ $whatwesell->title }}</a>
                            </span>
                            @else
                            <span class="font-lg mb-3 font-weight-bold">
                                <a href="#">{{ $whatwesell->title }}</a>
                            </span>
                            @endcan
                        </td>
                        <td>
                            <img onclick="imagepreview(this)" lazyload="on" alt="What We Sell Image" src="{{ $whatwesell->list_image_file_path }}" width="60px" height="60px">
                        </td>
                        <td>
                            <div class="mt-2">
                                    <a href="{{route('whatwesell.whatwesells.edit',$whatwesell)}}"
                                       class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>

                                    {!! Form::open(['route' => ['whatwesell.whatwesells.destroy',$whatwesell->id],
                                                'method' => 'DELETE',
                                                'data-confirmation-text' => __('Are you sure to delete this :name?', ['name' => $whatwesell->title])
                                                ])
                                        !!}
                                    <button class="btn btn-xs btn-outline-danger btn-show-on-tr-hover float-right">{{ __('Delete') }}</button>
                                    {!! Form::close() !!}
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
            </div>
            @if($whatWeSells->hasPages())
            <hr>
            <nav>
                {{ $whatWeSells->links() }}
            </nav>
        @endif
        </div>
    </div>

@stop
