<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itempajak extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_item', 'id_pajak'
    ];

    public function pajakdetail()
    {
        return $this->hasOne(Pajak::class, 'id', 'id_pajak');
    }
}
