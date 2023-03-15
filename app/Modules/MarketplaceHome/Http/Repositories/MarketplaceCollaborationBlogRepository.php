<?php

namespace App\Modules\MarketplaceHome\Http\Repositories;

use App\Modules\MarketplaceHome\Models\MarketplaceCollaborationBlog;

class MarketplaceCollaborationBlogRepository
{
    public function __construct(MarketplaceCollaborationBlog $marketplaceCollaborationBlog) {
        $this->marketplaceCollaborationBlog = $marketplaceCollaborationBlog;
    }

    public function create($payload) {
        return $this->marketplaceCollaborationBlog->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->marketplaceCollaborationBlog
                    ->find($id)->update($payload);
    }

    public function destroy($id) {
        return $this->marketplaceCollaborationBlog->destroy($id);
    }
}