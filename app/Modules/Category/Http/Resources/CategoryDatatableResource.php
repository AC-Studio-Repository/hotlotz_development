<?php

namespace App\Modules\Category\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryDatatableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'action' => view('item.items.action', ['item' => $this])->render()
        ];
    }
}
