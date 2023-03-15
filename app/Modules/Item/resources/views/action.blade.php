<div class="btn-group blocks">
    <a href="{{ route('item.items.edit', $item) }}"
       class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>
    {!! Form::open(['route' => ['item.items.destroy', $item],
            'method' => 'DELETE',
            'data-confirmation-text' => __('Are you sure to delete :name?', ['name' => $item->name])
        ])
    !!}
    <button class="btn btn-xs btn-outline-danger btn-show-on-tr-hover float-right">{{ __('Delete') }}</button>
    {!! Form::close() !!}
</div>