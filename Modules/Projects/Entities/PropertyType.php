<?php

namespace Modules\Projects\Entities;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model
{
    use SoftDeletes;
    protected $table = "project_types";
    protected $fillable = ['language_id','name','slug'];
    protected $dates = ['deleted_at'];


    public function created_language()
    {
        return $this->belongsTo("Modules\Countries\Entities\Alllanguages","language_id","id");
    }

    public function types() 
    {
        return $this->hasMany("Modules\Projects\Entities\PropertyType","parent_id");
    }
}

  
