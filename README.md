# Tugas Praktikum 4-6

|Nama|NIM|Kelas|Mata Kuliah|
|----|---|-----|------|
|**RA. Fadilah Amalia**|**312310298**|**TI.23.C1**|**Pemrograman Web 2**|

# Praktikum 4 : Framework Lanjutan (Modul Login)
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
![gambar]![Cuplikan layar 2025-06-27 101027](https://github.com/user-attachments/assets/547717ce-ca78-41f8-8904-5da95bc13632)


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
### Selanjutnya buka file app/Config/Routes.php dan sesuaikan kodenya.
```
```
### Buka url dengan alamat http://localhost:8080/admin/artikel ketika alamat tersebut diakses maka, akan dimuculkan halaman login.
![gambar]![Cuplikan layar 2025-06-27 101027](https://github.com/user-attachments/assets/790e72e1-b5c4-4e2e-80c1-dd365b3a36c5)
)
```
## Fungsi Logout
### Tambahkan method logout pada Controller User seperti berikut:
```php
  public function logout()
{
  session()->destroy();
  return redirect()->to('/user/login');
}
```

# Praktikum 5 : Pagination dan Pencarian
## Langkah-langkah
## 1. Membuat Pagination
### Pagination adalah proses untuk membagi data yang banyak ke dalam beberapa halaman, sehingga tampilan data menjadi lebih ringkas dan mudah dibaca. Fitur ini sangat berguna ketika kita menampilkan data dalam jumlah besar di sebuah website. Di CodeIgniter 4, pagination sudah disediakan oleh Library bawaan, sehingga implementasinya menjadi cukup mudah.
Langkah-langkah:
Buka kembali file Artikel.php pada folder Controller.
Kemudian modifikasi method admin_index seperti berikut agar mendukung pagination:
```php
public function admin_index()
 {
 $title = 'Daftar Artikel';
 $model = new ArtikelModel();
 $data = [
 'title' => $title,
 'artikel' => $model->paginate(10), #data dibatasi 10 record
per halaman
 'pager' => $model->pager,
 ];
 return view('artikel/admin_index', $data);
 }
```

### Setelah Anda menambahkan pagination di Controller, langkah selanjutnya adalah menampilkan navigasi pagination di file tampilan.
```php
<?= $pager->links(); ?>
```

### 2. Membuat Fitur Pencarian di CodeIgniter 4
Fitur pencarian data digunakan untuk memfilter data berdasarkan kata kunci tertentu. Misalnya, mencari artikel berdasarkan judul atau isi.
```php
public function admin_index()
 {
 $title = 'Daftar Artikel';
 $q = $this->request->getVar('q') ?? '';
 $model = new ArtikelModel();
 $data = [
 'title' => $title,
 'q' => $q,
 'artikel' => $model->like('judul', $q)->paginate(10), # data
dibatasi 10 record per halaman
 'pager' => $model->pager,
 ];
 return view('artikel/admin_index', $data);
 }
```

### Setelah mengatur logika pencarian di Controller, langkah selanjutnya adalah menambahkan form pencarian di tampilan (view).
```php
<form method="get" class="form-search">
 <input type="text" name="q" value="<?= $q; ?>" placeholder="Cari data">
 <input type="submit" value="Cari" class="btn btn-primary">
</form>
```

### ubah link pager seperti berikut.
```php
<?= $pager->only(['q'])->links(); ?>
```

## Hasil output akhir
![Cuplikan layar 2025-06-27 101049](https://github.com/user-attachments/assets/bc17de6a-cfdc-43f2-ab00-1d730b8cc983)

# Praktikum 6 Upload File Gambar
## langkah-langkah Upload gambar pada artikel
### Buka kembali file Controller: app/Controllers/Artikel.php Ubah method add() menjadi seperti berikut:
```php
     public function add()
     {
     // validasi data.
     $validation = \Config\Services::validation();
     $validation->setRules(['judul' => 'required']);
     $isDataValid = $validation->withRequest($this->request)->run();

     if ($isDataValid)
     {
         $file = $this->request->getFile('gambar');
         $file->move(ROOTPATH . 'public/gambar');

         $artikel = new ArtikelModel();
         $artikel->insert([
             'judul' => $this->request->getPost('judul'),
             'isi' => $this->request->getPost('isi'),
             'slug' => url_title($this->request->getPost('judul')),
             'gambar' => $file->getName(),
         ]);
         return redirect('admin/artikel');
     }
     $title = "Tambah Artikel";
     return view('artikel/form_add', compact('title'));
     }
```

### Buka file view: app/Views/artikel/form_add.php
Tambahkan input file berikut di dalam tag <form>, tepatnya di antara input judul dan isi atau di bagian yang sesuai:
```php
<p>
  <input type="file" name="gambar" />
</p>
```

### Agar form dapat mengunggah file (misalnya gambar artikel), Anda harus menambahkan atribut encrypt type
Ubah tag form menjadi seperti ini:
```php
<form action="" method="post" enctype="multipart/form-data"></form>
```
### Lakukan pengujian upload file dengan mengakses halaman tambah artikel.
![Cuplikan layar 2025-06-27 101240](https://github.com/user-attachments/assets/0658496d-237a-4248-b5b9-d34ec3271336)

### Terima Kasih

