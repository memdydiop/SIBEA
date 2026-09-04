<?php

namespace Database\Seeders;

use App\Enums\ContractType;
use App\Enums\EmployeeStatus;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Root Directions
        $directionGenerale = Department::firstOrCreate(
            ['code' => 'DG'],
            ['name' => 'Direction Générale', 'description' => 'Direction générale et pilotage stratégique de SIBEA.']
        );

        $directionTechnique = Department::firstOrCreate(
            ['code' => 'DTT'],
            ['name' => 'Direction Technique & Travaux', 'description' => 'Supervision des études techniques, chantiers et opérations.']
        );

        $directionAdministrative = Department::firstOrCreate(
            ['code' => 'DAF'],
            ['name' => 'Direction Administrative & Financière', 'description' => 'Gestion financière, budgétaire, comptable et RH.']
        );

        $directionCommerciale = Department::firstOrCreate(
            ['code' => 'DCF'],
            ['name' => 'Direction Commerciale & Foncier', 'description' => 'Relations clients, appels d\'offres, programmes et lotissements.']
        );

        // 2. Child Departments / Services
        $bureauEtudes = Department::firstOrCreate(
            ['code' => 'BE'],
            [
                'parent_id' => $directionTechnique->id,
                'name' => 'Bureau d\'Études & Méthodes',
                'description' => 'Calculs de structures, plans d\'exécution et méthodes.',
            ]
        );

        $serviceChantiers = Department::firstOrCreate(
            ['code' => 'SCCT'],
            [
                'parent_id' => $directionTechnique->id,
                'name' => 'Service Conduite de Travaux & Chantiers',
                'description' => 'Pilotage direct de l\'exécution des travaux.',
            ]
        );

        $serviceMateriel = Department::firstOrCreate(
            ['code' => 'SALM'],
            [
                'parent_id' => $directionTechnique->id,
                'name' => 'Service Achats, Logistique & Matériel',
                'description' => 'Gestion du parc engins, approvisionnements et stocks.',
            ]
        );

        $serviceQhse = Department::firstOrCreate(
            ['code' => 'QHSE'],
            [
                'parent_id' => $directionTechnique->id,
                'name' => 'Service QHSE',
                'description' => 'Qualité, Hygiène, Sécurité et Environnement.',
            ]
        );

        // 3. Operational Teams
        Team::firstOrCreate(
            ['code' => 'EQ-GO-01'],
            [
                'department_id' => $serviceChantiers->id,
                'name' => 'Équipe Gros Œuvre 1',
                'description' => 'Terrassement, fondations, maçonnerie et béton armé.',
            ]
        );

        Team::firstOrCreate(
            ['code' => 'EQ-VRD-01'],
            [
                'department_id' => $serviceChantiers->id,
                'name' => 'Équipe VRD & Voirie',
                'description' => 'Voiries, réseaux divers et assainissement.',
            ]
        );

        Team::firstOrCreate(
            ['code' => 'EQ-SO-01'],
            [
                'department_id' => $serviceChantiers->id,
                'name' => 'Équipe Second Œuvre',
                'description' => 'Finitions, plâtrerie, carrelage et peinture.',
            ]
        );

        Team::firstOrCreate(
            ['code' => 'EQ-ENR-01'],
            [
                'department_id' => $serviceChantiers->id,
                'name' => 'Équipe Énergie & Électricité',
                'description' => 'Installations électriques, solaire et réseaux d\'énergie.',
            ]
        );

        // 4. Super Admin User & Employee record
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@sibea.com'],
            [
                'name' => 'Directeur Général SIBEA',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        if (! $adminUser->hasRole('super-admin')) {
            $adminUser->assignRole('super-admin');
        }

        $adminEmployee = Employee::firstOrCreate(
            ['registration_number' => 'SIB-0001'],
            [
                'user_id' => $adminUser->id,
                'department_id' => $directionGenerale->id,
                'first_name' => 'Directeur',
                'last_name' => 'Général',
                'email' => 'admin@sibea.com',
                'phone' => '+225 07 00 00 00 01',
                'job_title' => 'Directeur Général',
                'contract_type' => ContractType::Cdi,
                'status' => EmployeeStatus::Active,
                'hire_date' => now()->subYears(3)->toDateString(),
            ]
        );

        $directionGenerale->update(['manager_id' => $adminEmployee->id]);
    }
}
