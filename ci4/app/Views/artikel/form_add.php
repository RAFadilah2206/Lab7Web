<?= $this->include('template/admin_header'); ?>

<div class="container">
    <h2 class="form-title"><?= $title; ?></h2>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <input type="text" name="judul" id="judul" placeholder="Judul Artikel" required>
        </div>

        <div class="form-group">
            <textarea name="isi" id="isi" cols="50" rows="10" placeholder="Isi Artikel" required></textarea>
        </div>

        <div class="form-group">
            <input type="file" name="gambar" id="gambar">
        </div>

        <button type="submit" class="submit-btn">Kirim</button>
    </form>
</div>

<?= $this->include('template/admin_footer'); ?>