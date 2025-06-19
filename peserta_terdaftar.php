<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Peserta Terdaftar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

  <h2 class="mb-4">Daftar Peserta Terdaftar</h2>

  <div id="participants-table-container" class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Nama</th>
          <th>Email</th>
          <th>No Telepon</th>
          <th>Motor</th>
          <th>Kategori</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="participants-tbody">
        <!-- Data peserta akan ditampilkan di sini -->
      </tbody>
    </table>
  </div>

  <div id="no-participants" class="alert alert-info" style="display:none;">
    Belum ada peserta yang terdaftar.
  </div>

  <script>
    function loadPesertaFromServer() {
      fetch('get_peserta.php')
        .then(response => response.json())
        .then(data => {
          const tbody = document.getElementById('participants-tbody');
          const container = document.getElementById('participants-table-container');
          const noData = document.getElementById('no-participants');

          tbody.innerHTML = '';

          if (!data || data.length === 0) {
            container.style.display = 'none';
            noData.style.display = 'block';
            return;
          }

          container.style.display = 'block';
          noData.style.display = 'none';

          data.forEach(p => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
              <td>${p.nama}</td>
              <td>${p.email}</td>
              <td>${p.no_telp}</td>
              <td>${p.motor}</td>
              <td>${p.kategori}</td>
              <td>
                <a href="edit_peserta.php?id=${p.id}" class="btn btn-sm btn-warning">Edit</a>
                <a href="hapus_peserta.php?id=${p.id}" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus peserta ini?')">Hapus</a>
              </td>
            `;
            tbody.appendChild(tr);
          });
        })
        .catch(err => {
          alert('Gagal memuat data peserta');
          console.error(err);
        });
    }

    document.addEventListener('DOMContentLoaded', loadPesertaFromServer);
  </script>
</body>
</html>
