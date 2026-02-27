<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'icon', 'route', 'url', 'role', 
        'parent_id', 'order', 'section', 'header_text', 'is_active'
    ];

    // Submenus
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')
                    ->active()
                    ->orderBy('order');
    }

    // Parent
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    // Active menus
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Role filter
    public function scopeByRole($query, $role)
    {
        return $query->whereIn('role', [$role, 'both']);
    }

    // Only headers
    public function scopeHeaders($query)
    {
        return $query->where('section', 'header')->active()->orderBy('order');
    }

    // Get URL
    // Get menu URL safely
public function getUrlAttribute()
{
    if ($this->route) {
        if (Route::has($this->route)) {
            try {
                return route($this->route);
            } catch (\Exception $e) {
                \Log::error("Route error for {$this->name}: " . $e->getMessage());
                return '#';
            }
        }
        return '#';
    }
    return $this->attributes['url'] ?? '#';
}
}