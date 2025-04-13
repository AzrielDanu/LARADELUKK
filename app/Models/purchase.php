<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;
use App\Models\Member;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'purchases'; // Nama tabel di database

    protected $fillable = [
        'purchase_date',
        'products_id',
        'user_id',
        'member_id',
        'total_price',
        'total_payment',
        'change',
        'used_point',
        'purchase_product'
    ];

    // Relasi ke Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function detail_purchase()
    {
        return $this->belongsTo(Detail_purchase::class, 'detail_purchase_id');
    }
}
