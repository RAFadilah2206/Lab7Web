# Tugas Praktikum 4-6

|Nama|NIM|Kelas|Mata Kuliah|
|----|---|-----|------|
|**Radityatama Nugraha**|**312310644**|**TI.23.A6**|**Pemrograman Web 2**|

# Praktikum 4: Framework Lanjutan (Modul Login)
## Tujuan
1. Mahasiswa mampu memahami konsep dasar Auth dan Filter.
2. Mahasiswa mampu memahami konsep dasar Login System.
3. Mahasaswa mampu membuat modul login menggunakan Framework Codeigniter 4.

## Instruksi Praktikum
1. Persiapkan text editor misalnya VSCode.
2. Buka kembali folder dengan nama lab7_php_ci_(2) pada docroot webserver (htdocs)
3. Ikuti langkah-langkah praktikum yang akan dijelaskan berikutnya.

## Membuat Model User
### Selanjutnya adalah membuat Model untuk memproses data Login. Buat file baru pada direktori app/Models dengan nama UserModel.php
```php
<?php
namespace App\Models;
use CodeIgniter\Model;
class UserModel extends Model
{
  protected $table = 'user';
  protected $primaryKey = 'id';
  protected $useAutoIncrement = true;
  protected $allowedFields = ['username', 'useremail', 'userpassword'];
}
```

## Membuat Controller User
### Buat Controller baru dengan nama User.php pada direktori app/Controllers. Kemudian tambahkan method index() untuk menampilkan daftar user, dan method login() untuk proses login.
```php
<?php

namespace App\Controllers;

use App\Models\UserModel;

class User extends BaseController
{
    public function index()
    {
        $title = 'Daftar User';
        $model = new UserModel();
        $users = $model->findAll();
        return view('user/index', compact('users', 'title'));
    }

    public function login()
    {
        helper(['form']);
        
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        if (!$email) {
            return view('user/login');
        }

        $session = session();
        $model = new UserModel();
        $login = $model->where('useremail', $email)->first();

        if ($login) {
            $pass = $login['userpassword'];

            if (password_verify($password, $pass)) {
                $login_data = [
                    'user_id'    => $login['id'],
                    'user_name'  => $login['username'],
                    'user_email' => $login['useremail'],
                    'logged_in'  => TRUE,
                ];

                $session->set($login_data);
                return redirect()->to('/dashboard'); 
            } else {
                $session->setFlashdata('error', 'Password salah');
                return redirect()->to('/user/login');
            }
        } else {
            $session->setFlashdata('error', 'Email tidak ditemukan');
            return redirect()->to('/user/login');
        }
    }
}
<?php

namespace App\Controllers;

use App\Models\UserModel;

class User extends BaseController
{
    public function login()
    {
        helper(['form']);

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (!$email) {
            return view('user/login');
        }

        $session = session();
        $model = new UserModel();
        $login = $model->where('useremail', $email)->first();

        if ($login) {
            $pass = $login['userpassword'];

            if (password_verify($password, $pass)) {
                $login_data = [
                    'user_id'    => $login['id'],
                    'user_name'  => $login['username'],
                    'user_email' => $login['useremail'],
                    'logged_in'  => TRUE,
                ];

                $session->set($login_data);
                return redirect('admin/artikel');
            } else {
                $session->setFlashdata("flash_msg", "Password salah.");
                return redirect()->to('/user/login');
            }
        } else {
            $session->setFlashdata("flash_msg", "Email tidak terdaftar.");
            return redirect()->to('/user/login');
        }
    }
}
```

## Membuat View Login
### Buat direktori baru dengan nama user pada direktori app/views, kemudian buat file baru dengan nama login.php.
```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="<?= base_url('/style.css');?>">
</head>
<body>
    <div id="login-wrapper">
        <h1>Sign In</h1>

        <?php if(session()->getFlashdata('flash_msg')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('flash_msg'); ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="mb-3">
                <label for="InputForEmail" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="InputForEmail" value="<?= set_value('email'); ?>">
            </div>

            <div class="mb-3">
                <label for="InputForPassword" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="InputForPassword">
            </div>

            <button type="submit" class="btn btn-primary">Sign In</button>
        </form>
    </div>
</body>
</html>
primary">Login</button>
    </form>
  </div>
</body>
</html>
```

## Membuat Database Seeder
### Database seeder digunakan untuk membuat data dummy. Untuk keperluan ujicoba modul login, kita perlu memasukkan data user dan password kedaalam database. Untuk itu buat database seeder untuk tabel user. Buka CLI, kemudian tulis perintah berikut:
```database
php spark make:seeder UserSeeder
```
### Selanjutnya, buka file UserSeeder.php yang berada di lokasi direktori /app/Database/Seeds/UserSeeder.php kemudian isi dengan kode berikut:
```php
<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Mendapatkan instance model UserModel
        $model = model('UserModel');

        $model->insert([
            'username'     => 'admin',
            'useremail'    => 'admin@email.com',
            'userpassword' => password_hash('admin123', PASSWORD_DEFAULT),
        ]);
    }
}
```
### Selanjutnya buka kembali CLI dan ketik perintah berikut:
```database
php spark db:seed UserSeeder
```

## Uji Coba Login
### Selanjutnya buka url http://localhost:8080/user/login seperti berikut:
![gambar](ss_gambar_pemrograman_web2-1/ssss1.png)

## Menambahkan Auth Filter
### Selanjutnya membuat filer untuk halaman admin. Buat file baru dengan nama Auth.php pada direktori app/Filters.
```php
<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('logged_in')) {
            // Redirect ke halaman login
            return redirect()->to('/user/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
```
### Selanjutnya buka file app/Config/Filters.php tambahkan kode berikut:
```php
'auth' => App\Filters\Auth::class
```
![gambar](ss_gambar_pemrograman_web2-1/ssss3.png)
### Selanjutnya buka file app/Config/Routes.php dan sesuaikan kodenya.
![gambar](ss_gambar_pemrograman_web2-1/ssss4.png)

## Percobaan Akses Menu Admin
### Buka url dengan alamat http://localhost:8080/admin/artikel ketika alamat tersebut diakses maka, akan dimuculkan halaman login.
![gambar](ss_gambar_pemrograman_web2-1/ssss2.png)

## Fungsi Logout
### Tambahkan method logout pada Controller User seperti berikut:
```php
  public function logout()
{
  session()->destroy();
  return redirect()->to('/user/login');
}
```









