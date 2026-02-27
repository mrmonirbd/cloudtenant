<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Admin Menus
        $adminMenus = [
            // Main Dashboard
            [
                'name' => 'Dashboard',
                'icon' => 'bi bi-speedometer2',
                'route' => 'dashboard',
                'role' => 'both',
                'order' => 1,
                'section' => 'main'
            ],
            
            // Company Management Header
            [
                'name' => 'COMPANY MANAGEMENT',
                'header_text' => 'COMPANY MANAGEMENT',
                'role' => 'admin',
                'order' => 2,
                'section' => 'header'
            ],
            
            // Companies
            [
                'name' => 'Companies / Tenants',
                'icon' => 'bi bi-building',
                'route' => 'companies.index',
                'role' => 'admin',
                'order' => 3,
                'section' => 'main'
            ],
            
            // Users
            [
                'name' => 'Users',
                'icon' => 'bi bi-people',
                'route' => 'users.index',
                'role' => 'admin',
                'order' => 4,
                'section' => 'main'
            ],
            
            // Roles & Permissions
            [
                'name' => 'Roles & Permissions',
                'icon' => 'bi bi-shield-lock',
                'route' => 'roles.index',
                'role' => 'admin',
                'order' => 5,
                'section' => 'main'
            ],
            
            // Subscription Header
            [
                'name' => 'SUBSCRIPTION & BILLING',
                'header_text' => 'SUBSCRIPTION & BILLING',
                'role' => 'admin',
                'order' => 6,
                'section' => 'header'
            ],
            
            // Subscription Plans
            [
                'name' => 'Subscription Plans',
                'icon' => 'bi bi-credit-card',
                'route' => 'plans.index',
                'role' => 'admin',
                'order' => 7,
                'section' => 'main'
            ],
            
            // Transactions
            [
                'name' => 'Transactions / Billing',
                'icon' => 'bi bi-cash-stack',
                'route' => 'transactions.index',
                'role' => 'admin',
                'order' => 8,
                'section' => 'main'
            ],
            
            // Reports Header
            [
                'name' => 'REPORTS',
                'header_text' => 'REPORTS',
                'role' => 'admin',
                'order' => 9,
                'section' => 'header'
            ],
            
            // Reports Parent
            [
                'name' => 'Reports',
                'icon' => 'bi bi-bar-chart',
                'role' => 'admin',
                'order' => 10,
                'section' => 'main'
            ],
        ];

        // Create admin menus
        foreach ($adminMenus as $menu) {
            Menu::create($menu);
        }

        // Reports Submenus
        $reportsParent = Menu::where('name', 'Reports')->first();
        
        if ($reportsParent) {
            $reportsSubmenus = [
                [
                    'name' => 'Usage Report',
                    'icon' => 'bi bi-pie-chart',
                    'route' => 'reports.usage',
                    'role' => 'admin',
                    'parent_id' => $reportsParent->id,
                    'order' => 1,
                    'section' => 'submenu'
                ],
                [
                    'name' => 'Revenue Report',
                    'icon' => 'bi bi-graph-up',
                    'route' => 'reports.revenue',
                    'role' => 'admin',
                    'parent_id' => $reportsParent->id,
                    'order' => 2,
                    'section' => 'submenu'
                ],
                [
                    'name' => 'Login Activity',
                    'icon' => 'bi bi-clock-history',
                    'route' => 'reports.activity',
                    'role' => 'admin',
                    'parent_id' => $reportsParent->id,
                    'order' => 3,
                    'section' => 'submenu'
                ]
            ];

            foreach ($reportsSubmenus as $submenu) {
                Menu::create($submenu);
            }
        }

        // Settings Header
        Menu::create([
            'name' => 'SETTINGS',
            'header_text' => 'SETTINGS',
            'role' => 'admin',
            'order' => 11,
            'section' => 'header'
        ]);

        // Settings Parent
        $settingsParent = Menu::create([
            'name' => 'Settings',
            'icon' => 'bi bi-gear',
            'role' => 'admin',
            'order' => 12,
            'section' => 'main'
        ]);

        // Settings Submenus
        $settingsSubmenus = [
            [
                'name' => 'General Settings',
                'icon' => 'bi bi-sliders',
                'route' => 'settings.general',
                'role' => 'admin',
                'parent_id' => $settingsParent->id,
                'order' => 1,
                'section' => 'submenu'
            ],
            [
                'name' => 'Profile',
                'icon' => 'bi bi-person',
                'route' => 'profile.edit',
                'role' => 'both',
                'parent_id' => $settingsParent->id,
                'order' => 2,
                'section' => 'submenu'
            ],
            [
                'name' => 'Notification Settings',
                'icon' => 'bi bi-bell',
                'route' => 'settings.notifications',
                'role' => 'admin',
                'parent_id' => $settingsParent->id,
                'order' => 3,
                'section' => 'submenu'
            ],
            [
                'name' => 'Payment Gateway Settings',
                'icon' => 'bi bi-credit-card',
                'route' => 'settings.payment',
                'role' => 'admin',
                'parent_id' => $settingsParent->id,
                'order' => 4,
                'section' => 'submenu'
            ]
        ];

        foreach ($settingsSubmenus as $submenu) {
            Menu::create($submenu);
        }

        // ========== USER MENUS ==========
        
        // User Dashboard
        Menu::create([
            'name' => 'Dashboard',
            'icon' => 'bi bi-speedometer2',
            'route' => 'dashboard',
            'role' => 'both',
            'order' => 1,
            'section' => 'main'
        ]);

        // My Profile
        Menu::create([
            'name' => 'My Profile',
            'icon' => 'bi bi-person-circle',
            'route' => 'profile.edit',
            'role' => 'user',
            'order' => 2,
            'section' => 'main'
        ]);

        // Team
        Menu::create([
            'name' => 'Users (Staff / Team)',
            'icon' => 'bi bi-people',
            'route' => 'team.index',
            'role' => 'user',
            'order' => 3,
            'section' => 'main'
        ]);

        // Projects
        Menu::create([
            'name' => 'Projects / Items',
            'icon' => 'bi bi-kanban',
            'route' => 'projects.index',
            'role' => 'user',
            'order' => 4,
            'section' => 'main'
        ]);

        // Billing Header
        Menu::create([
            'name' => 'BILLING',
            'header_text' => 'BILLING',
            'role' => 'user',
            'order' => 5,
            'section' => 'header'
        ]);

        // Invoices
        Menu::create([
            'name' => 'Invoices / Billing',
            'icon' => 'bi bi-receipt',
            'route' => 'invoices.index',
            'role' => 'user',
            'order' => 6,
            'section' => 'main'
        ]);

        // Subscription Parent
        $subParent = Menu::create([
            'name' => 'Subscription Plan',
            'icon' => 'bi bi-credit-card',
            'role' => 'user',
            'order' => 7,
            'section' => 'main'
        ]);

        // Subscription Submenus
        $subscriptionSubmenus = [
            [
                'name' => 'Upgrade Plan',
                'icon' => 'bi bi-arrow-up-circle',
                'route' => 'subscription.upgrade',
                'role' => 'user',
                'parent_id' => $subParent->id,
                'order' => 1,
                'section' => 'submenu'
            ],
            [
                'name' => 'Payment History',
                'icon' => 'bi bi-clock-history',
                'route' => 'subscription.history',
                'role' => 'user',
                'parent_id' => $subParent->id,
                'order' => 2,
                'section' => 'submenu'
            ]
        ];

        foreach ($subscriptionSubmenus as $submenu) {
            Menu::create($submenu);
        }

        // User Reports Header
        Menu::create([
            'name' => 'REPORTS',
            'header_text' => 'REPORTS',
            'role' => 'user',
            'order' => 8,
            'section' => 'header'
        ]);

        // User Reports Parent
        $userReportsParent = Menu::create([
            'name' => 'Reports',
            'icon' => 'bi bi-bar-chart',
            'role' => 'user',
            'order' => 9,
            'section' => 'main'
        ]);

        // User Reports Submenus
        $userReportsSubmenus = [
            [
                'name' => 'Usage Stats',
                'icon' => 'bi bi-pie-chart',
                'route' => 'reports.usage',
                'role' => 'user',
                'parent_id' => $userReportsParent->id,
                'order' => 1,
                'section' => 'submenu'
            ],
            [
                'name' => 'Activity Logs',
                'icon' => 'bi bi-clock-history',
                'route' => 'reports.activity',
                'role' => 'user',
                'parent_id' => $userReportsParent->id,
                'order' => 2,
                'section' => 'submenu'
            ]
        ];

        foreach ($userReportsSubmenus as $submenu) {
            Menu::create($submenu);
        }

        // User Settings Header
        Menu::create([
            'name' => 'SETTINGS',
            'header_text' => 'SETTINGS',
            'role' => 'user',
            'order' => 10,
            'section' => 'header'
        ]);

        // User Settings Parent
        $userSettingsParent = Menu::create([
            'name' => 'Settings',
            'icon' => 'bi bi-gear',
            'role' => 'user',
            'order' => 11,
            'section' => 'main'
        ]);

        // User Settings Submenus
        $userSettingsSubmenus = [
            [
                'name' => 'Profile Settings',
                'icon' => 'bi bi-person',
                'route' => 'settings.profile',
                'role' => 'user',
                'parent_id' => $userSettingsParent->id,
                'order' => 1,
                'section' => 'submenu'
            ],
            [
                'name' => 'Notifications',
                'icon' => 'bi bi-bell',
                'route' => 'settings.notifications',
                'role' => 'user',
                'parent_id' => $userSettingsParent->id,
                'order' => 2,
                'section' => 'submenu'
            ]
        ];

        foreach ($userSettingsSubmenus as $submenu) {
            Menu::create($submenu);
        }
    }
}