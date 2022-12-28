<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    public function type_document() {
        return $this->belongsTo(TypeDocument::class);
    }

    public function rec_user() {
        return $this->belongsTo(User::class,'rec_user_id');
    }
}
