<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create roles
        $superAdminRole = Role::create(['name' => 'superAdmin']);
        $volunteerRole = Role::create(['name' => 'Volunteer']);
        $donorRole = Role::create(['name' => 'Donor']);

        // 2. Create permissions
        $permissions = [
            'register', 'signin', 'userForgotPassword', 'userCheckCode', 'userResetPassword',
            'logout', 'getClassification', 'createIndiviCompa', 'viewMyIndiviCompa',
            'viewIndiviCompa', 'viewAssociationsCompaingsActive',
            'getUserCountsLastFiveYears', 'getTotalCampaignsCount', 'countAssociations', 'lastNewUsers'
        ];

        foreach ($permissions as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        // 3. Assign permissions
        $volunteerRole->syncPermissions($permissions);
        $donorRole->syncPermissions($permissions);
        $superAdminRole->syncPermissions($permissions);

        // 4. Create users for each role
        $donorUser = User::factory()->create([
            'role_id' => $donorRole->id,
            'gender_id' => 1,
            'phone' => '0954411753',
            'nationality_id' => 1,
            'age' => '20',
            'name' => 'Donor',
            'email' => 'Donor@example.com',
            'password' => bcrypt('password')
        ]);

        $donorUser->assignRole($donorRole);

        $volunteerUser = User::factory()->create([
            'role_id' => $volunteerRole->id,
            'gender_id' => 2,
            'phone' => '09544117593',
            'nationality_id' => 1,
            'age' => '20',
            'name' => 'Volunteer',
            'email' => 'Volunteer@example.com',
            'password' => bcrypt('password')
        ]);

        $volunteerUser->assignRole($volunteerRole);

        // 5. Create additional Donor users
        $names = ['shahed', 'dana', 'rama', 'yumna', 'rania', 'lana', 'rayan', 'mohammed', 'marwa', 'sawsan'];
        $nationalities = [1, 2, 3, 4, 5, 6, 7, 8, 3, 1];
        $ages = [20, 30, 25, 19, 21, 35, 29, 18, 29, 37];
        $emails = ['shahed@gmail.com', 'dana@gmail.com', 'rama@gmail.com', 'yumna@gmail.com', 'rania@gmail.com',
                   'lana@gmail.com', 'rayan@gmail.com', 'mohammed@gmail.com', 'marwa@gmail.com', 'sawsan@gmail.com'];
        $phones = ['0977665542', '09777865542', '09790665542', '09887665542', '09776655491',
                   '0977654235', '0966554229', '0977655218', '0929665542', '0973765542'];
        $genders = [1, 1, 1, 1, 1, 1, 2, 2, 1, 1];
        $passwords = ['123456789shahed', '123456789dana', '123456789rama', '123456789yumna',
                      '123456789rania', '123456789lana', '123456789rayan', '123456789mohammed',
                      '123456789marwa', '123456789sawsan'];

        for ($i = 0; $i < 10; $i++) {
            $user = User::create([
                'role_id' => $donorRole->id,
                'name' => $names[$i],
                'nationality_id' => $nationalities[$i],
                'age' => $ages[$i],
                'email' => $emails[$i],
                'phone' => $phones[$i],
                'gender_id' => $genders[$i],
                'password' => Hash::make($passwords[$i])
            ]);

            $user->assignRole($donorRole);
        }
    }
}
