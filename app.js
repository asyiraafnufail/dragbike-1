// app.js - FIXED VERSION
document.addEventListener('DOMContentLoaded', function () {
  // Global state
  let state = {
    isAdminLoggedIn: false,
    participants: [],
    editIndex: null,
  };

  // ---------- API Endpoints ----------
  const API = {
    GET_PESERTA: 'api_get_peserta.php',
    DAFTAR: 'api_daftar.php',
    HAPUS_PESERTA: 'api_hapus_peserta.php',
    LOGIN: 'api_login.php',
    LOGOUT: 'api_logout.php',
    SIGNUP: 'api_signup_admin.php',
    CHECK_SESSION: 'api_check_session.php',
  };

  // ---------- View Controller ----------
  function showView(viewId) {
    document.querySelectorAll('main > section').forEach(sec => (sec.style.display = 'none'));
    document.getElementById(viewId).style.display = 'block';
    updateNav(viewId);
  }

  function updateNav(activeView) {
    document.querySelectorAll('.custom-nav .btn').forEach(btn => btn.classList.remove('active'));
    const navMap = {
      'home-view': '#nav-home',
      'list-view': '#nav-peserta',
      'login-admin-view': '#nav-login-admin',
      'signup-admin-view': '#nav-signup-admin',
    };
    if (navMap[activeView]) {
      document.querySelector(navMap[activeView])?.classList.add('active');
    }
  }

  // ---------- UI Rendering ----------
  function renderUI() {
    renderParticipants();
    toggleAdminUI();
  }

  function toggleAdminUI() {
    const loginBtn = document.getElementById('nav-login-admin');
    const signupBtn = document.getElementById('nav-signup-admin');
    const logoutBtn = document.getElementById('nav-logout-admin');
    const aksiHeader = document.getElementById('aksi-header');

    if (state.isAdminLoggedIn) {
      loginBtn.style.display = 'none';
      signupBtn.style.display = 'none';
      logoutBtn.style.display = 'inline-block';
      aksiHeader.style.display = 'table-cell';
    } else {
      loginBtn.style.display = 'inline-block';
      signupBtn.style.display = 'inline-block';
      logoutBtn.style.display = 'none';
      aksiHeader.style.display = 'none';
    }
  }

  function renderParticipants() {
    const tbody = document.getElementById('participants-tbody');
    const tableContainer = document.getElementById('participants-table-container');
    const noData = document.getElementById('no-participants');

    tbody.innerHTML = '';

    // Pastikan participants adalah array
    if (!Array.isArray(state.participants) || state.participants.length === 0) {
      tableContainer.style.display = 'none';
      noData.style.display = 'block';
      return;
    }

    tableContainer.style.display = 'block';
    noData.style.display = 'none';

    state.participants.forEach((p, index) => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${p.nama}</td>
        <td>${p.email}</td>
        <td>${p.no_telp}</td>
        <td>${p.motor}</td>
        <td>${p.kategori}</td>
        <td style="display: ${state.isAdminLoggedIn ? 'table-cell' : 'none'}">
          ${
            state.isAdminLoggedIn
              ? `<button class="btn btn-sm btn-warning edit-btn" data-index="${index}">Edit</button>
                 <button class="btn btn-sm btn-danger delete-btn" data-id="${p.id}">Hapus</button>`
              : ''
          }
        </td>
      `;
      tbody.appendChild(tr);
    });

    // Re-bind events for new buttons
    document.querySelectorAll('.edit-btn').forEach(btn => btn.addEventListener('click', handleEditClick));
    document.querySelectorAll('.delete-btn').forEach(btn => btn.addEventListener('click', handleDeleteClick));
  }

  // ---------- Data Fetching ----------
  async function fetchParticipants() {
    try {
      const response = await fetch(API.GET_PESERTA);
      if (!response.ok) throw new Error('Gagal mengambil data peserta.');
      
      const data = await response.json();
      
      // Pastikan data yang diterima adalah array
      if (Array.isArray(data)) {
        state.participants = data;
      } else {
        console.error('Data yang diterima bukan array:', data);
        state.participants = [];
      }
      
      renderParticipants();
    } catch (error) {
      console.error('Error fetching participants:', error);
      state.participants = [];
      renderParticipants();
      alert('Gagal mengambil data peserta: ' + error.message);
    }
  }

  async function checkLoginStatus() {
    try {
      const response = await fetch(API.CHECK_SESSION);
      const data = await response.json();
      state.isAdminLoggedIn = data.loggedIn;
      renderUI();
    } catch (error) {
      console.error('Gagal memeriksa status session:', error);
      state.isAdminLoggedIn = false;
      renderUI();
    }
  }

  // ---------- Event Handlers ----------
  function handleEditClick(e) {
    const index = e.target.dataset.index;
    const participant = state.participants[index];
    state.editIndex = index;

    const form = document.getElementById('register-form');
    form.querySelector('#participant-id').value = participant.id;
    form.querySelector('#name').value = participant.nama;
    form.querySelector('#email').value = participant.email;
    form.querySelector('#phone').value = participant.no_telp;
    form.querySelector('#bike').value = participant.motor;
    form.querySelector('#category').value = participant.kategori;

    form.querySelector('button[type="submit"]').textContent = 'Update';
    showView('register-view');
  }

  async function handleDeleteClick(e) {
    const id = e.target.dataset.id;
    if (confirm('Yakin ingin menghapus peserta ini?')) {
      try {
        const formData = new FormData();
        formData.append('id', id);
        const response = await fetch(API.HAPUS_PESERTA, { method: 'POST', body: formData });
        const result = await response.json();
        if (!result.success) throw new Error(result.message);
        alert('Peserta berhasil dihapus.');
        await fetchParticipants(); // Refresh list
      } catch (error) {
        console.error(error);
        alert('Gagal menghapus peserta: ' + error.message);
      }
    }
  }

  document.getElementById('register-form').addEventListener('submit', async function (e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    
    submitBtn.disabled = true;
    submitBtn.textContent = 'Menyimpan...';

    try {
      const response = await fetch(API.DAFTAR, { method: 'POST', body: formData });
      const result = await response.json();
      if (!result.success) throw new Error(result.message);

      alert(state.editIndex !== null ? 'Data berhasil diperbarui!' : 'Pendaftaran berhasil!');
      form.reset();
      state.editIndex = null;
      showView('list-view');
      await fetchParticipants(); // Refresh list
    } catch (error) {
      console.error(error);
      alert(error.message);
    } finally {
      submitBtn.disabled = false;
      submitBtn.textContent = originalText;
    }
  });
  
  document.getElementById('login-admin-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    try {
      const response = await fetch(API.LOGIN, { method: 'POST', body: formData });
      const result = await response.json();
      if (!result.success) throw new Error(result.message);
      
      alert('Welcome back, Admin!');
      state.isAdminLoggedIn = true;
      form.reset();
      showView('list-view');
      renderUI();
      await fetchParticipants();
    } catch (error) {
      console.error(error);
      alert(error.message);
    }
  });
  
  document.getElementById('signup-admin-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
     try {
      const response = await fetch(API.SIGNUP, { method: 'POST', body: formData });
      const result = await response.json();
      if (!result.success) throw new Error(result.message);
      
      alert('Sign up berhasil! Silakan login.');
      form.reset();
      showView('login-admin-view');
    } catch (error) {
      console.error(error);
      alert(error.message);
    }
  });

  // ---------- Navigation Binding ----------
  document.getElementById('nav-home').addEventListener('click', () => showView('home-view'));
  document.getElementById('nav-peserta').addEventListener('click', () => {
    showView('list-view');
    fetchParticipants();
  });
  document.getElementById('nav-login-admin').addEventListener('click', () => showView('login-admin-view'));
  document.getElementById('nav-signup-admin').addEventListener('click', () => showView('signup-admin-view'));

  document.getElementById('home-daftar-btn').addEventListener('click', () => {
    state.editIndex = null;
    const form = document.getElementById('register-form');
    form.reset();
    form.querySelector('button[type="submit"]').textContent = 'Daftar';
    showView('register-view');
  });

  document.getElementById('nav-logout-admin').addEventListener('click', async () => {
    try {
      await fetch(API.LOGOUT, { method: 'POST' });
      state.isAdminLoggedIn = false;
      alert('Logout berhasil.');
      showView('home-view');
      renderUI();
    } catch (error) {
      console.error('Error during logout:', error);
      // Tetap logout meskipun ada error
      state.isAdminLoggedIn = false;
      showView('home-view');
      renderUI();
    }
  });

  // ---------- App Initialization ----------
  function init() {
    showView('home-view');
    checkLoginStatus();
    // Load participants data on start
    fetchParticipants();
  }

  init();
});