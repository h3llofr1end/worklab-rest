<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * @package App
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $price
 * @property int $count
 * @property int $user_id
 * @property string $uniqid
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Product extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function($model){
            $model->uniqid = sha1($model->title.env('APP_KEY'));
        });
    }

    /**
     * Return all categories for current product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_products');
    }

    /**
     * Return user model of current product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Return all modified products by last minutes in given parameter
     *
     * @param int $minutes
     * @return string
     */
    public static function getModifiedProducts($minutes)
    {
        $time = new Carbon("- $minutes minutes");
        return self::where('updated_at', '>=', $time)->get();
    }
}
