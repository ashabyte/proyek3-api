<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'nama',
        'deskripsi',
        'harga',
        'durasi',
        'kategori',
        'stok',
        'gambar',
    ];

    // Relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
