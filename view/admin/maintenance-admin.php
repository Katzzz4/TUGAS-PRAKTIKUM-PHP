<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Admin</title>
  <link rel="stylesheet" href="../../assets/css/admin-style.css">
</head>
<body>

  <div class="container">
    <h2 style="text-align: center; margin-bottom: 0px;">🧑‍💼 Tambah Admin Baru</h2>

    <form class="admin-form" action="../../controllers/admin/proses-tambah-admin.php" method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="text" name="nama_admin" placeholder="Nama Admin" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Simpan Admin</button>
      <a href="login.php" class="btn-secondary">Kembali ke Login</a>
    </form>
  </div>

</body>
</html>
