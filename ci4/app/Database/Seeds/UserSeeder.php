<?php
namespace App\Database\Seeds;
use CodeIgniter\Database\Seeder;
class UserSeeder extends Seeder
{
    public function run()
    {
        $model = model('UserModel');
        $model->insert([
            'username' => 'fadilah',
            'useremail' => 'r.afadilah22@gmail.com',
            'userpassword' => password_hash('fadilah22', PASSWORD_DEFAULT),
        ]);
    }
}