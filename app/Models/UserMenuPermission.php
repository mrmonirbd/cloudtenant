<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMenuPermission extends Model
{
    use HasFactory;

    protected $table = 'user_menu_permissions';

    protected $fillable = [
        'user_id', 'menu_id', 'can_view', 'can_create', 'can_edit', 'can_delete'
    ];

    protected $casts = [
        'can_view' => 'boolean',
        'can_create' => 'boolean',
        'can_edit' => 'boolean',
        'can_delete' => 'boolean',
    ];

    /**
     * Get the user that owns the permission
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the menu that the permission belongs to
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}