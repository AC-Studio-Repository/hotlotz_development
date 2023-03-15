<?php

namespace App\Modules\DocumentModule\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\DocumentModule\Models\DocumentModule;

class DocumentFile extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'document_files';

    public function document()
    {
        return $this->belongsTo(DocumentModule::class);
    }
}
