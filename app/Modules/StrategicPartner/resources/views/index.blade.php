@extends('appshell::layouts.default')

@section('title')
    {{ __('Strategic Partners') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create customers')
                <a href="{{ route('strategic_partner.strategic_partners.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create Strategic Partner') }}
                </a>
                @endcan
            </div>

        </div>

        <div class="card-block">
            <div class="table-responsive" style="overflow-x:auto;">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Description') }}</th>
                    <th>{{ __('Image') }}</th>
                    <th style="width: 10%">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                @foreach($strategic_partners as $strategic_partner)
                    <tr>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                <a href="{{ route('strategic_partner.strategic_partners.show', $strategic_partner) }}">{{ $strategic_partner->title }}</a>
                            </span>
                        </td>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                <a href="{{ route('strategic_partner.strategic_partners.show', $strategic_partner) }}">{{ $strategic_partner->description }}</a>
                            </span>
                        </td>
                        <td>
                            <img onclick="imagepreview(this)" lazyload="on" alt="Item Image" src="{{ $strategic_partner->full_file_path }}" width="60px" height="60px">
                        </td>
                        <td>
                            <div class="mt-2">
                                @can('edit customers')
                                    <a href="{{route('strategic_partner.strategic_partners.edit',$strategic_partner)}}"
                                       class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>
                                @endcan

                                @can('delete customers')
                                    {!! Form::open(['route' => ['strategic_partner.strategic_partners.destroy',$strategic_partner->id],
                                                'method' => 'DELETE',
                                                'data-confirmation-text' => __('Are you sure to delete this :name?', ['name' => $strategic_partner->title])
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
            @if($strategic_partners->hasPages())
            <hr>
            <nav>
                {{ $strategic_partners->links() }}
            </nav>
        @endif
        </div>
    </div>

@stop
