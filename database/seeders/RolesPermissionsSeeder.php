<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Wallet;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Storage;


class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {


        // 1. Create roles
        $superAdminRole = Role::create(['name' => 'superAdmin']);
        $volunteerRole = Role::create(['name' => 'Volunteer']);
        $donorRole = Role::create(['name' => 'Donor']);
        $adminRole = Role::create(['name' => 'Admin']);
        $leaderRole = Role::create(['name' => 'Leader']);

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
        $adminRole->syncPermissions($permissions);
        $leaderRole->syncPermissions($permissions);

         $sourcePath = public_path('uploads/seeder_photos/defualtProfilePhoto.png');
         $targetPath = 'uploads/det/defualtProfilePhoto.png';

    Storage::disk('public')->put($targetPath, File::get($sourcePath));
    
        // 4. Create users for each role
        $donorUser = User::factory()->create([
            'role_id' => $donorRole->id,
            'gender_id' => 1,
            'phone' => '0954411753',
            'city_id' => 1,
            'age' => '20',
            'name' => 'Donor',
            'email' => 'Donor@example.com',
            'password' => bcrypt('password') ,
            'photo' => url(Storage::url($targetPath))
        ]);

        $wallet = Wallet::create([
          'user_id' => $donorUser->id,
          'wallet_value' => 1000000,
          'wallet_password' => bcrypt('password') ,
        ]);

        $donorUser->assignRole($donorRole);

        //assign permissions with the role to the user
        $permissions = $donorRole->permissions()->pluck('name')->toArray();
        $donorUser->givePermissionTo ($permissions);

        $volunteerUser = User::factory()->create([
            'role_id' => $volunteerRole->id,
            'gender_id' => 2,
            'phone' => '09544117593',
            'city_id' => 1,
            'age' => '20',
            'name' => 'Volunteer',
            'email' => 'Volunteer@example.com',
            'password' => bcrypt('password'),
            'photo' => url(Storage::url($targetPath))

        ]);

       $wallet = Wallet::create([
          'user_id' => $volunteerUser->id,
          'wallet_value' => 1000000,
          'wallet_password' => bcrypt('password') ,
        ]);
        $volunteerUser->assignRole($volunteerRole);

        //assign permissions with the role to the user
        $permissions = $volunteerRole->permissions()->pluck('name')->toArray();
        $volunteerUser->givePermissionTo ($permissions);

        
       $admin = User::factory()->create([
            'role_id' => $adminRole->id,
            'gender_id' => 2,
            'phone' => '09544117593',
            'city_id' => 1,
            'age' => '20',
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'photo' => url(Storage::url($targetPath))

        ]);

       $wallet = Wallet::create([
          'user_id' => $admin->id,
          'wallet_value' => 1000000,
          'wallet_password' => bcrypt('password') ,
        ]);
        $admin->assignRole($adminRole);

        //assign permissions with the role to the user
        $permissions = $adminRole->permissions()->pluck('name')->toArray();
        $admin->givePermissionTo ($permissions);




       $leader = User::factory()->create([
            'role_id' => $leaderRole->id,
            'gender_id' => 2,
            'phone' => '09544117593',
            'city_id' => 1,
            'age' => '20',
            'name' => 'leader',
            'email' => 'leader@example.com',
            'password' => bcrypt('password'),
            'photo' => url(Storage::url($targetPath))
        ]);

       $wallet = Wallet::create([
          'user_id' => $leader->id,
          'wallet_value' => 1000000,
          'wallet_password' => bcrypt('password') ,
        ]);
        $leader->assignRole($leaderRole);

        //assign permissions with the role to the user
        $permissions = $leaderRole->permissions()->pluck('name')->toArray();
        $leader->givePermissionTo ($permissions);


        
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
                'city_id' => $nationalities[$i],
                'age' => $ages[$i],
                'email' => $emails[$i],
                'phone' => $phones[$i],
                'gender_id' => $genders[$i],
                'password' => Hash::make($passwords[$i]),
                'photo' => url(Storage::url($targetPath))

            ]);

            $wallet = Wallet::create([
                'user_id' => $user->id,
                'wallet_value' => 1000000,
                'wallet_password' => Hash::make('password') ,
                ]);


            $user->assignRole($donorRole);

            //assign permissions with the role to the user
            $permissions = $donorRole->permissions()->pluck('name')->toArray();
            $user->givePermissionTo ($permissions);
        }
    }
}
