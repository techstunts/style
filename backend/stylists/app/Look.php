<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Look extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'createdlook';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['look_image', 'look_name', 'look_description', 'product_id1', 'product_id2', 'product_id3',
        'product_id4','body_type', 'budget', 'age', 'occasion', 'gender', 'stylish_id', 'lookprice','date'];

    public $timestamps = false;

}
