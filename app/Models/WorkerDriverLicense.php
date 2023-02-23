<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerDriverLicense extends Model
{
    use HasFactory;

    public function workers()
    {
        return $this->belongsToMany(DriverLicense::class, 'worker_driver_licenses');
    }
}
