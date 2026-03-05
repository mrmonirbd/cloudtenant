<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'display_name', 'description', 'level', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'level' => 'integer'
    ];

    /**
     * Get users with this role
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }

    /**
     * Check if role is owner
     */
    public function isOwner()
    {
        return $this->name === 'owner';
    }

    /**
     * Check if role is superadmin
     */
    public function isSuperAdmin()
    {
        return $this->name === 'superadmin';
    }

    /**
     * Check if role is admin
     */
    public function isAdmin()
    {
        return $this->name === 'admin';
    }

    /**
     * Check if role is staff
     */
    public function isStaff()
    {
        return $this->name === 'staff';
    }

    /**
     * Scope active roles
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get role by name
     */
    public static function getRole($name)
    {
        return self::where('name', $name)->first();
    }
}