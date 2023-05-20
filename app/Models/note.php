<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class note extends Model
{
    use HasFactory;
    protected $fillable = [
        'plant_id',
        'text',

    ];
    public $timestamps = false;
    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }
}
