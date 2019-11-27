<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $role_admin = Role::where('name', 'admin')->first();
        $role_vendedor  = Role::where('name', 'vendedor')->first();



        $admin = new User();
        $admin->name = 'Admin Name';
        $admin->email = 'admin@example.com';
        $admin->password = bcrypt('secret');
        $admin->save();
        $admin->roles()->attach($role_admin);



        $vendedor = new User();
        $vendedor->name = 'Vendedor Name';
        $vendedor->email = 'vendedor@example.com';
        $vendedor->password = bcrypt('secret');
        $vendedor->save();
        $vendedor->roles()->attach($role_vendedor);
        $vendedor->distribuidor()->create(
            [
                'razao_social'  => 'Razão social - vendedor test',
                'nome_fantasia' => 'Nome Fantasia - vendedor  teste',
                'cnpj'          => '16.994.458/0001-43'
            ]
        );

//        factory(User::class, 1)->create();
    }
}
