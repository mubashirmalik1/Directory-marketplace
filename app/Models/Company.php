<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'website',
        'phone',
        'address',
        'owner_user_id',
        'uuid',
        'tagline',
        'description',
        'website_url',
        'contact_email',
        'contact_phone',
        'country_code',
        'city',
    ];

    protected static function booted()
    {
        static::creating(function ($company) {
            $company->uuid = Str::uuid();
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function serviceCategories(): BelongsToMany
    {
        return $this->belongsToMany(ServiceCategory::class, 'company_service_category');
    }
}
