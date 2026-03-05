<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name', 'route', 'parent_id'];

    // Many-to-many relationship with User
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_menu_permissions', 'menu_id', 'user_id');
    }

    // Get child menus (for parent-child hierarchy)
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }

    // Get parent menu (if exists)
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }
}