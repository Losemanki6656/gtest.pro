<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUploadFields;
use \Venturecraft\Revisionable\RevisionableTrait;

class Worker extends Model
{
    use HasFactory;
    use HasUploadFields;

    protected $guarded = ['id'];

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

    public function address_region() {
        return $this->belongsTo(Region::class,'address_region_id');
    }

    public function address_city() {
        return $this->belongsTo(City::class, 'address_city_id');
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

    public function setPhotoAttribute($value)
    {
        if($value == null)  $this->setPhotoAttribute = ''; 
        else 
        {
            $attribute_name = "photo";
            $disk = "public";
            $destination_path = 'worker-photos'; 
            $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
        }
    }

    public function setFile1Attribute($value)
    {
        if( $value == null )  
            $this->setPhotoAttribute = ''; 
        else 
        {
            $attribute_name = "file1";
            $disk = "public";
            $destination_path = 'worker-files'; 
            $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
        }
    }

    public function setRailStatusAttribute($value)
    {
        if($value) {
            $this->attributes['rail_status'] = true;
        } else  $this->attributes['rail_status'] = false;
    }


    public function setOldJobNameAttribute($value)
    {
        if( $value  == "null" ) {
            $this->attributes['old_job_name'] = null;
        } else 
            $this->attributes['old_job_name'] = $value;
    }

    public function setDelRailCommentAttribute($value)
    {
        if( $value  == "null" ) {
            $this->attributes['del_rail_comment'] = null;
        } else 
            $this->attributes['del_rail_comment'] = $value;
    }
}
