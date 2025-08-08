<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use APP\Scopes\CompanyScope;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'user_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'completed_at'
    ];

    protected static function booted()    
    {
        static::addGlobalScope(new CompanyScope);
    }

    public function company() 
    {
        return $this->belongsTo(Company::class);    
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
