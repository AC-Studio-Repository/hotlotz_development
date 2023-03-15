<?php

namespace App\Modules\BlogPost\Http\Repositories;

use App\Modules\BlogPost\Models\BlogPost;

class BlogPostRepository
{
    public function __construct(BlogPost $blog_post) {
        $this->blog_post = $blog_post;
    }

    public function all($eagerLoad = [], $withTrash = true, $paginateCount = 0) {
        return $this->blog_post
                    ->orderBy('order','asc')
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->when($eagerLoad, function ($query) use ($eagerLoad, $withTrash) {
                        if ($withTrash) {
                            return $query->withEagerTrashed($eagerLoad);
                        } else {
                            return $query->with($eagerLoad);
                        }
                    })
                    ->when($paginateCount, function ($query, $role) use ($paginateCount) {
                        return $query->paginate($paginateCount);
                    }, function ($query) {
                        return $query->get();
                    });
    }

    public function show($column, $value, $eagerLoad = [], $withTrash = false, $returnMany = false) {
        return $this->blog_post
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->when($eagerLoad, function ($query) use ($eagerLoad, $withTrash) {
                        if ($withTrash) {
                            return $query->withEagerTrashed($eagerLoad);
                        } else {
                            return $query->with($eagerLoad);
                        }
                    })
                    ->where($column, $value)
                    ->when($returnMany, function ($query, $role) {
                        return $query->get();
                    }, function ($query) {
                        return $query->first();
                    });
    }

    public function create($payload) {
        return $this->blog_post->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->blog_post
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->find($id)->update($payload);
    }

    public function canDestroy($id) {
        return $this->blog_post->where('id', $id)->doesntHave('childrens')->exists();
    }

    public function destroy($id, $type = 1) { // 1 - normal, 2 - force
        if ($type == 1) {
            return $this->blog_post->destroy($id);
        } else {
            return $this->blog_post->withTrashed()->find($id)->forceDelete();
        }
    }

    public function restore($id) {
        return $this->blog_post->withTrashed()->find($id)->restore();
    }

    public function packData($request)
    {
        $payload['title'] = $request['title'];
        $payload['post_date'] = $request['post_date'];
        $payload['link_name'] = $request['link_name'];
        $payload['link'] = $request['link'];

        return $payload;
    }
}