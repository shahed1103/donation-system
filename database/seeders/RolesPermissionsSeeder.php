<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

use Illuminate\Support\Facades\Hash;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //create roles

         $superAdminRole = Role::create(['name' => 'superAdmin']);
        $clientRole = Role::create(['name' => 'client']);

        //define permissions

        $permissions = ['countAssociations'];

        foreach($permissions as $permissionName){
            Permission::findOrCreate($permissionName , 'web');
        }


        $clientRole->syncPermissions();

        $superAdminRole->syncPermissions($permissions);


      //create users and assign roles

//clients
    $clientUser = User::factory()->create([
        'role_id' => $clientRole->id,
        'gender_id' => '1',
        'phone' => '0954411753',
        'nationality_id' => '1',
        'age' => '20',
        'name' => 'Client',
        'email' => 'client@example.com',
        'password' => bcrypt('password')
    ]);

    $clientUser->assignRole($clientRole);

    //assign permissions with the role to the user
    $permissions = $clientRole->permissions()->pluck('name')->toArray();
    $clientUser->givePermissionTo ($permissions);


    $clientUser2 = User::factory()->create([
        'role_id' => $clientRole->id,
        'gender_id' => '2',
        'phone' => '09544117593',
        'nationality_id' => '1',
        'age' => '20',
        'name' => 'Client2',
        'email' => 'client2@example.com',
        'password' => bcrypt('password')
    ]);

    $clientUser2->assignRole($clientRole);

    //assign permissions with the role to the user
    $permissions = $clientRole->permissions()->pluck('name')->toArray();
    $clientUser2->givePermissionTo ($permissions);


    $name = ['shahed' , 'dana' , 'rama' , 'yumna' , 'rania' , 'lana' , 'rayan' , 'mohammed' , 'marwa' , 'sawsan'];
    $nationality_id = ['1' , '2' , '3' , '4' , '5' , '6' , '7' , '8' , '3' , '1'];
    $age = ['20' , '30' , '25' , '19' , '21' , '35' , '29' , '18' , '29' , '37'];
    $email = ['shahed@gamil.com' , 'dana@gamil.com' , 'rama@gamil.com' , 'yumna@gamil.com' , 'rania@gamil.com' , 'lana@gamil.com' , 'rayan@gamil.com' , 'mohammed@gamil.com' , 'marwa@gamil.com' , 'sawsan@gamil.com'];
    $phone = ['0977665542' , '09777865542' , '09790665542' , '09887665542' , '09776655491' , '0977654235' , '0966554229' , '0977655218' , '0929665542' , '0973765542'];
    $gender_id = ['1' , '1' , '1' ,'1' ,'1' , '1' , '2' , '2' , '1' , '1'];
    $passwordWOH = ['123456789shahed' , '123456789dana' , '123456789rama' , '123456789yumna' , '123456789rania' , '123456789lana' , '123456789rayan' , '123456789mohammed' , '123456789marwa' , '123456789sawsan'];

// foreach ($passwordWOH as $passwordWOH) {
// $password [] = Hash::make($passwordWOH);
// }

   for ($i=0; $i < 10 ; $i++) {

    $clientUser = User::create([
           'role_id' => $clientRole->id,
           'name' =>  $name[$i],
           'nationality_id' => $nationality_id[$i],
           'age' =>$age[$i],
           'email' =>  $email[$i],
           'phone' =>  $phone[$i],
           'gender_id' => $gender_id[$i],
           'password' => $passwordWOH[$i]
       ]);

       $clientUser->assignRole($clientRole);

       //assign permissions with the role to the user
       $permissions = $clientRole->permissions()->pluck('name')->toArray();
       $clientUser->givePermissionTo ($permissions);

    }

  }
}
