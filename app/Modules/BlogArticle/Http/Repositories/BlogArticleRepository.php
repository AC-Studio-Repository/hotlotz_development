<?php

namespace App\Modules\BlogArticle\Http\Repositories;

use App\Modules\BlogArticle\Models\BlogArticle;

class BlogArticleRepository
{
    public function __construct(BlogArticle $blog_article) {
        $this->blog_article = $blog_article;
    }

    public function all($eagerLoad = [], $withTrash = true, $paginateCount = 0) {
        return $this->blog_article
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
        return $this->blog_article
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
        return $this->blog_article->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->blog_article
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->find($id)->update($payload);
    }

    public function canDestroy($id) {
        return $this->blog_article->where('id', $id)->doesntHave('childrens')->exists();
    }

    public function destroy($id, $type = 1) { // 1 - normal, 2 - force
        if ($type == 1) {
            return $this->blog_article->destroy($id);
        } else {
            return $this->blog_article->withTrashed()->find($id)->forceDelete();
        }
    }

    public function restore($id) {
        return $this->blog_article->withTrashed()->find($id)->restore();
    }

    public function packData($request)
    {
        $payload['title'] = $request['title'];
        $payload['publication_date'] = $request['publication_date'];
        $payload['type'] = $request['type'];
        if($request->type == 'url'){
            $payload['article_url'] = $request['article_url'];
        }

        return $payload;
    }
}