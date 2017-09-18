<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UploadImages extends Model
{
    protected $table = 'isy_uploads';

    public function type() {
        return $this->belongsTo('App\Models\Lookups\ImageType', 'image_type_id');
    }
}
