<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Glossary\Models\Glossary;

class GlossaryCategory extends Model
{
    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public $table = 'glossary_category';

    public function glossary()
    {
        return $this->hasMany(Glossary::class);
    }
}
