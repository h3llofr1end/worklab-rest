<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 * @package App
 *
 * @property int $id
 * @property string $title
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Category extends Model
{
    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_products');
    }
}
