<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_employee = new Role();
        $role_employee->name = 'admin';
        $role_employee->description = 'Adiministrador';
        $role_employee->save();

        $role_manager = new Role();
        $role_manager->name = 'vendedor';
        $role_manager->description = 'Vendedor';
        $role_manager->save();
  }
}
