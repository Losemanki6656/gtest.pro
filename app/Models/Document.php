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

    public function to_organization() {
        return $this->belongsTo(Organization::class,'to_organization_id');
    }

    public function send_user() {
        return $this->belongsTo(User::class,'send_user_id');
    }

    public function workers() {
        return $this->hasMany(Worker::class);
    }

    public function executor() {
        return $this->belongsTo(User::class,'executor_id');
    }

}
