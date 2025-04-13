<?php

namespace App\Models;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
            'name',
            'image',
            'price',
            'stock'
    ];

    public function purchase()
    {
        return $this->hasMany(Purchase::class, 'purchase_id');
    }

    public function detail_purchase()
    {
        return $this->belongsTo(Detail_purchase::class, 'detail_purchase_id');
    }

}
