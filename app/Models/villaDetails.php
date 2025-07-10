<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class villaDetails extends Model
{

    protected $fillable=[
        "id",
        "property_id",
        "floors",
        "rooms",
        "bathrooms",
        "hall",
        "entrance",
        "has_garden",
        "has_pool",
        "has_grash",
    ];
    public function property(){
        return $this->belongsTo(Property::class);
    }
    //
}
