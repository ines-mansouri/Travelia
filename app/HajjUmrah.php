<?php

namespace App;

use Database\Factories\HajjUmrahFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HajjUmrah extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'description', 'content', 'image', 'published_at', 'category_id',
        'type', 'price', 'duration_days', 'mecca_hotel_id', 'medina_hotel_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'price' => 'decimal:2',
    ];

    protected static function newFactory()
    {
        return HajjUmrahFactory::new();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function meccaHotel()
    {
        return $this->belongsTo(Hotel::class, 'mecca_hotel_id');
    }

    public function medinaHotel()
    {
        return $this->belongsTo(Hotel::class, 'medina_hotel_id');
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc');
    }
}
