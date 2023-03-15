<?php

namespace App\Modules\DocumentModule\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Modules\DocumentModule\Models\DocumentFile;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentModule extends Model
{
    use SoftDeletes;
    
    protected $guarded = [
        'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    public $table = 'documents';

    public function files()
    {
        return $this->hasMany(DocumentFile::class, 'document_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function packData($request)
    {
        $payload = [];
        
        $payload['title'] = $request->title ?? null;
        $payload['publish_date'] = $request->publish_date ?? null;
        $payload['type'] = $request->type ?? null;
        $payload['document_type'] = $request->document_type ?? null;
        $payload['created_by'] = $request->created_by ?? null;

        return $payload;
    }
}
