<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categories';

    protected $fillable = ['name'];

    public $timestamps = false;

    public function getCategoryTree(){

        return DB::select("SELECT c3.id as category_id_1, c2.id as category_id_2, c1.id as id,

            CONCAT(
                (case when c3.id != 0 then concat(c3.name, ' - ') else '' end),
                (case when c2.id != 0 then concat(c2.name, ' - ') else '' end),
                c1.name
            ) category_with_parent_name,


            CONCAT(
                (case when c3.id != 0 then '&nbsp;&nbsp;' else '' end),
                (case when c2.id != 0 then '&nbsp;&nbsp;' else '' end),
                c1.name
            ) name,

            '' as product_count
            FROM categories c1
            JOIN categories c2 ON c1.parent_category_id = c2.id
            JOIN categories c3 ON c2.parent_category_id = c3.id
            ORDER BY category_with_parent_name");
    }
    public function subcategory () {
        return $this->hasMany('App\Category', 'parent_category_id');
    }
    public function leafcategory () {
        return $this->hasMany('App\Category', 'parent_category_id');
    }

}
