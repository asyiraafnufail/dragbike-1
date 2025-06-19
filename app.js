let editIndex = null; // Menyimpan indeks peserta yang sedang diedit

// ---------- Peserta State ----------
function getParticipants() {
  return JSON.parse(localStorage.getItem('participants') || '[]');
}

function saveParticipants(data) {
  localStorage.setItem('participants', JSON.stringify(data));
}

function renderParticipants() {
  const participantsList = getParticipants();
  const tbody = document.getElementById('participants-tbody');
  const tableContainer = document.getElementById('participants-table-container');
  const noData = document.getElementById('no-participants');
  const isLoggedIn = localStorage.getItem('admin_logged_in') === 'true';

  tbody.innerHTML = '';

  const aksiHeader = document.getElementById('aksi-header');
  if (aksiHeader) {
    aksiHeader.style.display = isLoggedIn ? 'table-cell' : 'none';
  }

  if (participantsList.length === 0) {
    tableContainer.style.display = 'none';
    noData.style.display = 'block';
    return;
  }

  tableContainer.style.display = 'block';
  noData.style.display = 'none';

  participantsList.forEach((p, index) => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${p.name}</td>
      <td>${p.email}</td>
      <td>${p.phone}</td>
      <td>${p.bike}</td>
      <td>${p.category}</td>
      <td style="display: ${isLoggedIn ? 'table-cell' : 'none'}">
        ${
          isLoggedIn
            ? `<button class="btn btn-sm btn-warning edit-btn" data-index="${index}">Edit</button>
               <button class="btn btn-sm btn-danger delete-btn" data-index="${index}">Hapus</button>`
            : ''
        }
      </td>
    `;
    tbody.appendChild(tr);
  });

  if (isLoggedIn) {
    document.querySelectorAll('.edit-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        const index = this.dataset.index;
        editParticipant(index);
      });
    });

    document.querySelectorAll('.delete-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        const index = this.dataset.index;
        deleteParticipant(index);
      });
    });
  }
}

function deleteParticipant(index) {
  if (confirm('Yakin ingin menghapus peserta ini?')) {
    const dataList = getParticipants();
    dataList.splice(index, 1);
    saveParticipants(dataList);
    renderParticipants();
  }
}

function editParticipant(index) {
  const dataList = getParticipants();
  const peserta = dataList[index];
  editIndex = index;

  // Isi form dengan data peserta
  document.getElementById('name').value = peserta.name;
  document.getElementById('email').value = peserta.email;
  document.getElementById('phone').value = peserta.phone;
  document.getElementById('bike').value = peserta.bike;
  document.getElementById('category').value = peserta.category;

  // Ubah tombol submit menjadi "Update"
  const submitBtn = document.querySelector('#register-form button[type="submit"]');
  if (submitBtn) submitBtn.textContent = 'Update';

  showView('register-view');
}

// ---------- View Controller ----------
function showView(viewId) {
  document.querySelectorAll('main > section').forEach(sec => {
    sec.style.display = 'none';
  });

  document.getElementById(viewId).style.display = 'block';

  document.querySelectorAll('.custom-nav .btn').forEach(btn => {
    btn.classList.remove('active');
  });

  switch (viewId) {
    case 'home-view':
      document.getElementById('nav-home')?.classList.add('active');
      break;
    case 'list-view':
      document.getElementById('nav-peserta')?.classList.add('active');
      renderParticipants();
      break;
    case 'login-admin-view':
      document.getElementById('nav-login-admin')?.classList.add('active');
      break;
    case 'signup-admin-view':
      document.getElementById('nav-signup-admin')?.classList.add('active');
      break;
  }

  toggleLogoutButton();
}

function toggleLogoutButton() {
  const isLoggedIn = localStorage.getItem('admin_logged_in') === 'true';
  const logoutBtn = document.getElementById('nav-logout-admin');
  if (logoutBtn) logoutBtn.style.display = isLoggedIn ? 'inline-block' : 'none';
}

// ---------- Event Binding ----------
document.getElementById('nav-home')?.addEventListener('click', () => showView('home-view'));
document.getElementById('nav-peserta')?.addEventListener('click', () => showView('list-view'));
document.getElementById('nav-login-admin')?.addEventListener('click', () => showView('login-admin-view'));
document.getElementById('nav-signup-admin')?.addEventListener('click', () => showView('signup-admin-view'));
document.getElementById('home-daftar-btn')?.addEventListener('click', () => {
  editIndex = null;
  document.getElementById('register-form').reset();
  const submitBtn = document.querySelector('#register-form button[type="submit"]');
  if (submitBtn) submitBtn.textContent = 'Daftar';
  showView('register-view');
});

document.getElementById('nav-logout-admin')?.addEventListener('click', () => {
  localStorage.removeItem('admin_logged_in');
  alert('Logout berhasil.');
  showView('home-view');
  renderParticipants();
});

// ---------- Register Form ----------
document.getElementById('register-form')?.addEventListener('submit', function (e) {
  e.preventDefault();

  const form = e.target;
  const data = {
    name: form.name.value.trim(),
    email: form.email.value.trim(),
    phone: form.phone.value.trim(),
    bike: form.bike.value.trim(),
    category: form.category.value
  };

  const current = getParticipants();

  if (editIndex !== null) {
    current[editIndex] = data;
    editIndex = null;
    alert('Data peserta berhasil diperbarui.');
  } else {
    if (current.some(p => p.email === data.email)) {
      alert('Email sudah terdaftar!');
      return;
    }
    current.push(data);
    alert('Pendaftaran berhasil!');
  }

  saveParticipants(current);

  form.reset();
  const submitBtn = document.querySelector('#register-form button[type="submit"]');
  if (submitBtn) submitBtn.textContent = 'Daftar';

  showView('list-view');
  renderParticipants();
});

// ---------- Admin Sign Up & Login ----------
document.getElementById('signup-admin-form')?.addEventListener('submit', function (e) {
  e.preventDefault();

  const form = e.target;
  const nama = form.nama.value.trim();
  const username = form.username.value.trim();
  const password = form.password.value.trim();

  if (!nama || !username || !password) {
    alert('Semua field wajib diisi!');
    return;
  }

  let admins = JSON.parse(localStorage.getItem('admins') || '[]');

  if (admins.some(a => a.username === username)) {
    alert('Username sudah digunakan!');
    return;
  }

  admins.push({ nama, username, password });
  localStorage.setItem('admins', JSON.stringify(admins));

  alert('Sign up berhasil! Silakan login.');
  showView('login-admin-view');
});


document.getElementById('login-admin-form')?.addEventListener('submit', function (e) {
  e.preventDefault();

  const form = e.target;
  const username = form.username.value.trim();
  const password = form.password.value.trim();

  const admins = JSON.parse(localStorage.getItem('admins') || '[]');
  const user = admins.find(a => a.username === username && a.password === password);

  if (user) {
    alert('Login berhasil!');
    localStorage.setItem('admin_logged_in', 'true');
    showView('list-view');
    renderParticipants();
  } else {
    alert('Username atau password salah!');
  }
});

// ---------- Init ----------
document.addEventListener('DOMContentLoaded', function () {
  showView('home-view');
  renderParticipants();
});
