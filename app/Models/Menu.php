<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'icon', 'route', 'url', 'role', 
        'parent_id', 'order', 'section', 'header_text', 'is_active'
    ];

    // Relationship for submenus
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    // Parent menu
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    // Scope for active menus
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope by role
    public function scopeByRole($query, $role)
    {
        return $query->where(function($q) use ($role) {
            $q->where('role', $role)
              ->orWhere('role', 'both');
        });
    }

    // Get menu URL
    public function getUrlAttribute()
    {
        if ($this->route) {
            return route($this->route);
        }
        return $this->attributes['url'] ?? '#';
    }
}