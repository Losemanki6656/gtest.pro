<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'worker_languages');
    }

    public function driver_licensies()
    {
        return $this->belongsToMany(DriverLicense::class, 'worker_driver_licenses');
    }

    public function document() {
        return $this->belongsTo(Document::class);
    }

    public function education() {
        return $this->belongsTo(Education::class);
    }

    public function region() {
        return $this->belongsTo(Region::class);
    }

    public function city() {
        return $this->belongsTo(City::class);
    }

    public function nationality() {
        return $this->belongsTo(Nationality::class);
    }

    public function academic_degree() {
        return $this->belongsTo(AcademicDegree::class);
    }

    public function academic_title() {
        return $this->belongsTo(AcademicTitle::class);
    }

    public function party() {
        return $this->belongsTo(Party::class);
    }
}
