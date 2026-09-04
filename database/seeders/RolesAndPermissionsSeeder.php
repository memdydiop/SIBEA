<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Granular permissions defined in CDC (module.action)
        $permissions = [
            // Organization
            'organization.view',
            'departments.manage',
            'teams.manage',
            'employees.manage',

            // Projects
            'projects.view',
            'projects.create',
            'projects.update',
            'projects.delete',
            'projects.approve',
            'projects.archive',

            // Tasks & Planning
            'tasks.view',
            'tasks.create',
            'tasks.update',
            'tasks.assign',
            'tasks.complete',

            // Sites & Daily Reports
            'sites.view',
            'sites.create',
            'sites.update',
            'reports.view',
            'reports.create',
            'reports.submit',
            'reports.validate',

            // Documents (GED)
            'documents.view',
            'documents.upload',
            'documents.update',
            'documents.delete',
            'documents.download',
            'documents.validate',

            // Budgets & Finance
            'budgets.view',
            'budgets.create',
            'budgets.update',
            'budgets.approve',
            'expenses.view',
            'expenses.create',
            'expenses.update',
            'expenses.validate',

            // Land Lots (Foncier)
            'lots.view',
            'lots.create',
            'lots.update',
            'lots.reserve',
            'lots.sell',

            // CMS & Vitrine
            'cms.manage',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // 1. Super Admin (All permissions)
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        // 2. Admin (General administration)
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        // 3. Direction Générale
        $directionGenerale = Role::firstOrCreate(['name' => 'direction-generale', 'guard_name' => 'web']);
        $directionGenerale->syncPermissions([
            'organization.view',
            'projects.view', 'projects.approve', 'projects.archive',
            'tasks.view',
            'sites.view',
            'reports.view', 'reports.validate',
            'documents.view', 'documents.download', 'documents.validate',
            'budgets.view', 'budgets.approve',
            'expenses.view', 'expenses.validate',
            'lots.view',
        ]);

        // 4. Direction Technique
        $directionTechnique = Role::firstOrCreate(['name' => 'direction-technique', 'guard_name' => 'web']);
        $directionTechnique->syncPermissions([
            'organization.view',
            'projects.view', 'projects.create', 'projects.update', 'projects.approve',
            'tasks.view', 'tasks.create', 'tasks.update', 'tasks.assign', 'tasks.complete',
            'sites.view', 'sites.create', 'sites.update',
            'reports.view', 'reports.validate',
            'documents.view', 'documents.upload', 'documents.update', 'documents.download', 'documents.validate',
            'budgets.view',
            'expenses.view',
        ]);

        // 5. Chef de Projet
        $chefProjet = Role::firstOrCreate(['name' => 'chef-de-projet', 'guard_name' => 'web']);
        $chefProjet->syncPermissions([
            'projects.view', 'projects.update',
            'tasks.view', 'tasks.create', 'tasks.update', 'tasks.assign', 'tasks.complete',
            'sites.view', 'sites.create', 'sites.update',
            'reports.view', 'reports.create', 'reports.submit', 'reports.validate',
            'documents.view', 'documents.upload', 'documents.update', 'documents.download',
            'budgets.view',
            'expenses.view', 'expenses.create',
        ]);

        // 6. Conducteur de Travaux
        $conducteurTravaux = Role::firstOrCreate(['name' => 'conducteur-travaux', 'guard_name' => 'web']);
        $conducteurTravaux->syncPermissions([
            'projects.view',
            'tasks.view', 'tasks.create', 'tasks.update', 'tasks.assign', 'tasks.complete',
            'sites.view', 'sites.update',
            'reports.view', 'reports.create', 'reports.submit', 'reports.validate',
            'documents.view', 'documents.upload', 'documents.download',
            'expenses.view', 'expenses.create',
        ]);

        // 7. Chef de Chantier
        $chefChantier = Role::firstOrCreate(['name' => 'chef-de-chantier', 'guard_name' => 'web']);
        $chefChantier->syncPermissions([
            'projects.view',
            'tasks.view', 'tasks.complete',
            'sites.view',
            'reports.view', 'reports.create', 'reports.submit',
            'documents.view', 'documents.upload', 'documents.download',
            'expenses.create',
        ]);

        // 8. Ingénieur
        $ingenieur = Role::firstOrCreate(['name' => 'ingenieur', 'guard_name' => 'web']);
        $ingenieur->syncPermissions([
            'projects.view',
            'tasks.view', 'tasks.create', 'tasks.update',
            'sites.view',
            'reports.view',
            'documents.view', 'documents.upload', 'documents.update', 'documents.download',
        ]);

        // 9. Technicien
        $technicien = Role::firstOrCreate(['name' => 'technicien', 'guard_name' => 'web']);
        $technicien->syncPermissions([
            'projects.view',
            'tasks.view', 'tasks.complete',
            'sites.view',
            'reports.view',
            'documents.view', 'documents.download',
        ]);

        // 10. QHSE
        $qhse = Role::firstOrCreate(['name' => 'qhse', 'guard_name' => 'web']);
        $qhse->syncPermissions([
            'projects.view',
            'sites.view',
            'reports.view',
            'documents.view', 'documents.upload', 'documents.download',
        ]);

        // 11. Achats & Logistique
        $achats = Role::firstOrCreate(['name' => 'achats-logistique', 'guard_name' => 'web']);
        $achats->syncPermissions([
            'projects.view',
            'expenses.view', 'expenses.create', 'expenses.update',
            'documents.view', 'documents.upload', 'documents.download',
        ]);

        // 12. Finance
        $finance = Role::firstOrCreate(['name' => 'finance', 'guard_name' => 'web']);
        $finance->syncPermissions([
            'projects.view',
            'budgets.view', 'budgets.create', 'budgets.update', 'budgets.approve',
            'expenses.view', 'expenses.create', 'expenses.update', 'expenses.validate',
            'documents.view', 'documents.download',
        ]);

        // 13. Commercial & Foncier
        $commercial = Role::firstOrCreate(['name' => 'commercial-foncier', 'guard_name' => 'web']);
        $commercial->syncPermissions([
            'lots.view', 'lots.create', 'lots.update', 'lots.reserve', 'lots.sell',
            'cms.manage',
            'documents.view', 'documents.upload', 'documents.download',
        ]);

        // 14. Client / Maître d'ouvrage
        $client = Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);
        $client->syncPermissions([
            'projects.view',
            'documents.view', 'documents.download',
        ]);
    }
}
