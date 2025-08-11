<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    use HasFactory;

    protected $table = 'exports';

    protected $fillable = [
        'uuid',
        'company_id',
        'user_id',
        'status',
        'file_path',
        'filters',
        'delivered_at'
    ];

    protected $casts = [
        'filters' => 'array'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
