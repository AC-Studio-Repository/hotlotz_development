<div class="btn-group blocks">
    <a href="{{ route('category.categories.edit', $category) }}"
       class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>
    {!! Form::open(['route' => ['category.categories.destroy', $category],
            'method' => 'DELETE',
            'data-confirmation-text' => __('Are you sure to delete :name?', ['name' => $category->name])
        ])
    !!}
    <button class="btn btn-xs btn-outline-danger btn-show-on-tr-hover float-right">{{ __('Delete') }}</button>
    {!! Form::close() !!}
</div>