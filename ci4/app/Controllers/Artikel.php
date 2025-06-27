<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Artikel extends BaseController
{
    // Fungsi untuk menampilkan daftar artikel tanpa pagination
    public function index()
    {
        $title = 'Daftar Artikel';
        $model = new ArtikelModel();
        $artikel = $model->findAll();

        return view('artikel/index', compact('artikel', 'title'));
    }

    // Fungsi untuk melihat detail artikel berdasarkan slug
    public function view($slug)
    {
        $model = new ArtikelModel();
        $artikel = $model->where(['slug' => $slug])->first();

        if (!$artikel) {
            throw PageNotFoundException::forPageNotFound();
        }

        $title = $artikel['judul'];
        return view('artikel/detail', compact('artikel', 'title'));
    }

    // Fungsi untuk menampilkan daftar artikel dengan pagination
    public function admin_index()
    {
        $title = 'Daftar Artikel';
        $q = $this->request->getVar('q') ?? '';  // Mendapatkan kata kunci pencarian dari form
        $model = new ArtikelModel();

        // Menambahkan pagination dan pencarian
        $data = [
            'title'   => $title,
            'q'       => $q,  // Menyimpan nilai pencarian
            'artikel' => $model->like('judul', $q)->paginate(10),  // Membatasi 10 artikel per halaman dan memfilter berdasarkan 'judul'
            'pager'   => $model->pager,  // Menyediakan kontrol pagination
        ];

        return view('artikel/admin_index', $data);  // Menampilkan artikel dan pagination pada view
    }

    // Method untuk menambah artikel
    public function add()
    {
        $validation = \Config\Services::validation();
        $validation->setRules(['judul' => 'required']);
        $isDataValid = $validation->withRequest($this->request)->run();

        if ($isDataValid) {
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

    // Method untuk mengedit artikel
    public function edit($id)
    {
        $artikel = new ArtikelModel();
        $validation = \Config\Services::validation();
        $validation->setRules(['judul' => 'required']);
        $isDataValid = $validation->withRequest($this->request)->run();

        if ($isDataValid) {
            $artikel->update($id, [
                'judul' => $this->request->getPost('judul'),
                'isi' => $this->request->getPost('isi'),
            ]);
            return redirect('admin/artikel');
        }

        // Ambil data lama
        $data = $artikel->where('id', $id)->first();
        $title = "Edit Artikel";
        return view('artikel/form_edit', compact('title', 'data'));
    }

    // Method untuk menghapus artikel
    public function delete($id)
    {
        $artikel = new ArtikelModel();
        $artikel->delete($id);
        return redirect('admin/artikel');
    }
}
