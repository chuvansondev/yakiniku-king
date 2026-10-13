<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AccountManagementPermissionSeeder extends Seeder
{
    /** @var array<string, string> */
    private const PERMISSIONS = [
        'admin.dashboard.view' => 'View the admin dashboard.',
        'accounts.manage' => 'Create, update, lock and unlock user accounts.',
        'menu.categories.manage' => 'Manage menu categories.',
        'menu.items.manage' => 'Manage menu items.',
        'menu.combos.manage' => 'Manage menu combos.',
        'menu.banners.manage' => 'Manage menu banners.',
        'menu.promotions.manage' => 'Manage promotions.',
        'menu.recipes.manage' => 'Manage recipes.',
        'menu.tips.manage' => 'Manage tips.',
        'menu.restaurants.manage' => 'Manage restaurants.',
        'menu.bookings.manage' => 'Manage bookings.',
        'menu.leads.manage' => 'Manage leads.',
        'menu.customers.manage' => 'Manage customer profiles and interaction history.',
        'menu.settings.manage' => 'Manage menu settings.',
        'kids-items.manage' => 'Manage kids menu items.',
    ];

    public function run(): void
    {
        $permissionIds = [];

        foreach (self::PERMISSIONS as $slug => $description) {
            $permission = Permission::query()->updateOrCreate(
                ['slug' => $slug],
                ['name' => $slug, 'description' => $description],
            );
            $permissionIds[] = $permission->id;
        }

        $adminRole = Role::query()->updateOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Admin', 'description' => 'Administrator access.'],
        );

        $adminRole->permissions()->syncWithoutDetaching($permissionIds);

        User::query()
            ->where('is_admin', true)
            ->whereNull('role_id')
            ->update(['role_id' => $adminRole->id]);
    }
}
