<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'status', 'role'  // 'role' field for simple approach
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * If using many-to-many relationship with roles table
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * Check if user has a specific role (simple approach)
     */
    public function hasRole($role)
    {
        // Simple approach using role field
        if (is_array($role)) {
            return in_array($this->role, $role);
        }
        return $this->role === $role;
    }

    /**
     * Check if user is owner
     */
    public function isOwner()
    {
        return $this->role === 'owner';
    }

    /**
     * Check if user is superadmin
     */
    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is staff
     */
    public function isStaff()
    {
        return $this->role === 'staff';
    }

    /**
     * Check if user has higher role than given role
     */
    public function hasHigherRoleThan($role)
    {
        $roleLevels = [
            'owner' => 100,
            'superadmin' => 80,
            'admin' => 60,
            'staff' => 40,
            'user' => 20
        ];

        $userLevel = $roleLevels[$this->role] ?? 0;
        $targetLevel = $roleLevels[$role] ?? 0;

        return $userLevel > $targetLevel;
    }

    /**
     * Get user's role display name
     */
    public function getRoleDisplayNameAttribute()
    {
        $displayNames = [
            'owner' => 'Owner',
            'superadmin' => 'Super Admin',
            'admin' => 'Admin',
            'staff' => 'Staff',
            'user' => 'User'
        ];

        return $displayNames[$this->role] ?? $this->role;
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'user_menu_permissions', 'user_id', 'menu_id');
    }

    public function hasMenuPermission($menuId)
    {
        return \DB::table('user_menu_permissions')
        ->where('user_id', $this->id)
        ->where('menu_id', $menuId)
        ->exists();
    }

    public function permissions()
{
    return $this->belongsToMany(\App\Models\Menu::class, 'user_menu_permissions');
}
}