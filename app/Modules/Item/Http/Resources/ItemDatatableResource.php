<?php

namespace App\Modules\Item\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemDatatableResource extends JsonResource
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
            'status' => $this->status,
            'auction' => $this->auction->title,
            'lifecycle' => $this->lifecycle->name,
            'action' => view('item.items.action', ['item' => $this])->render()
        ];
    }
}
