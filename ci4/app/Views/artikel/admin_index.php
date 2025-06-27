<?= $this->include('template/admin_header'); ?>

<!-- CSS untuk form pencarian -->
<style>
    .form-search {
        display: flex;
        justify-content: flex-start;
        gap: 10px;
        margin-bottom: 20px;
        margin-top: 10px;
    }

    .form-search input[type="text"] {
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        width: 250px;
        transition: border-color 0.2s ease-in-out;
    }

    .form-search input[type="text"]:focus {
        border-color: #007bff;
        outline: none;
    }

    .form-search input[type="submit"] {
        padding: 8px 14px;
        background-color: #007bff;
        border: none;
        color: white;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.2s ease-in-out;
    }

    .form-search input[type="submit"]:hover {
        background-color: #0056b3;
    }
</style>

<!-- Form Pencarian -->
<form method="get" class="form-search">
    <input type="text" name="q" value="<?= $q; ?>" placeholder="Cari data">
    <input type="submit" value="Cari" class="btn btn-primary">
</form>

<!-- Menampilkan data artikel -->
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Judul</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($artikel): ?>
            <?php foreach ($artikel as $row): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><b><?= $row['judul']; ?></b><p><small><?= substr($row['isi'], 0, 50); ?></small></p></td>
                    <td><?= $row['status']; ?></td>
                    <td>
                        <a class="btn" href="<?= base_url('/admin/artikel/edit/' . $row['id']); ?>">Ubah</a>
                        <a class="btn btn-danger" onclick="return confirm('Yakin menghapus data?');" href="<?= base_url('/admin/artikel/delete/' . $row['id']); ?>">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Belum ada data.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Menampilkan kontrol pagination -->
<div>
    <?= $pager->only(['q'])->links(); ?>  <!-- Menampilkan kontrol pagination dengan pencarian -->
</div>


<!-- Menampilkan data artikel -->
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Judul</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($artikel): ?>
            <?php foreach ($artikel as $row): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td>
                        <b><?= $row['judul']; ?></b>
                        <p><small><?= substr($row['isi'], 0, 50); ?></small></p>
                    </td>
                    <td><?= $row['status']; ?></td>
                    <td>
                        <a class="btn" href="<?= base_url('/admin/artikel/edit/' . $row['id']); ?>">Ubah</a>
                        <a class="btn btn-danger" onclick="return confirm('Yakin menghapus data?');"
                            href="<?= base_url('/admin/artikel/delete/' . $row['id']); ?>">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Belum ada data.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Pagination -->
<div>
    <?= $pager->links(); ?> <!-- Menampilkan kontrol pagination dengan pencarian -->
</div>

<?= $this->include('template/admin_footer'); ?>