<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerRelative extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    
    public function relative() {
        return $this->belongsTo(Relative::class);
    }

}
