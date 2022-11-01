<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'title','description','subcategory_id','price', 'thumbnail'
    ];
    
    public function getSubCategory(){
        return $this->belongsTo(Subcategory::class,'subcategory_id');
    }
}
