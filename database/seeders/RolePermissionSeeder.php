<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'period.view',
            'period.create',
            'period.update',
            'period.delete',
            'period.activate',
            'period.close',
            'period.archive',
            'cluster.view',
            'cluster.create',
            'cluster.update',
            'cluster.delete',
            'cluster.assign-opd',
            'indicator.view',
            'indicator.create',
            'indicator.update',
            'indicator.delete',
            'subdocument.view',
            'subdocument.create',
            'subdocument.update',
            'subdocument.delete',
            'document.view',
            'document.upload',
            'document.submit',
            'document.download',
            'document.verify',
            'document.request-revision',
            'document.reject',
            'comment.create',
            'comment.reply',
            'verification.view',
            'report.view',
            'report.export',
            'user.view',
            'user.create',
            'user.update',
            'user.delete',
            'role.view',
            'role.update',
            'opd.view',
            'opd.create',
            'opd.update',
            'opd.delete',
            'audit-log.view',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $admin = Role::findOrCreate('admin', 'web');
        $pic = Role::findOrCreate('pic', 'web');
        $operator = Role::findOrCreate('operator_opd', 'web');

        $admin->syncPermissions($permissions);

        $pic->syncPermissions([
            'period.view',
            'cluster.view',
            'indicator.view',
            'subdocument.view',
            'document.view',
            'document.download',
            'document.verify',
            'document.request-revision',
            'document.reject',
            'comment.create',
            'comment.reply',
            'verification.view',
            'report.view',
            'report.export',
            'audit-log.view',
        ]);

        $operator->syncPermissions([
            'period.view',
            'cluster.view',
            'indicator.view',
            'subdocument.view',
            'document.view',
            'document.upload',
            'document.submit',
            'document.download',
            'comment.create',
            'comment.reply',
            'verification.view',
            'report.view',
        ]);
    }
}
