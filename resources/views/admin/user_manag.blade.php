<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Management – SavEat</title>
  <script src="https://cdn.tailwindcss.com/3.4.16"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            saveat: {
              dark:    '#2D4A1E',
              mid:     '#4A6B35',
              soft:    '#6B8F52',
              cream:   '#F5F0E8',
              light:   '#EBF0E4',
              accent:  '#C8D8B0',
              badge:   '#E85D3A',
              text:    '#1C2E12',
            }
          },
          fontFamily: {
            sans: ['Inter', 'system-ui', 'sans-serif'],
          }
        }
      }
    }
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    body { background-color: #F5F0E8; }

    .stat-card {
      background: white;
      border-radius: 14px;
      padding: 14px 18px;
    }

    @supports (padding-bottom: env(safe-area-inset-bottom)) {
      .bottom-nav { padding-bottom: env(safe-area-inset-bottom); }
    }

    /* scrollable filter tabs on mobile */
    .filter-tabs { scrollbar-width: none; }
    .filter-tabs::-webkit-scrollbar { display: none; }

    /* three-dot menu dropdown */
    .dropdown-menu { display: none; }
    .dropdown-menu.open { display: block; }
  </style>
</head>
<body class="font-sans min-h-screen bg-[#F5F0E8]">
<div class="flex min-h-screen">

  <!-- ===== MAIN CONTENT ===== -->
  <main class="flex-1 pb-24 lg:pb-0">
    <div class="w-full max-w-7xl mx-auto p-4 lg:p-8">

      <!-- Header -->
       <div class="flex items-center justify-between mb-6">
            <a href="index.html" class="w-9 h-9 rounded-full bg-white flex items-center justify-center shadow-sm">
              <svg width="18" height="18" fill="none" stroke="#2D4A1E" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
              </svg>
            </a>
        <span class="text-saveat-text text-[17px] font-semibold">User Management</span>
         <button class="w-9 h-9 rounded-full overflow-hidden shadow-sm">
                 <img src="img/foto.png" alt="Profil" class="w-full h-full object-cover">
            </button>
      </div>

      <!-- Subtitle -->
      <p class="text-center text-[13px] font-semibold mb-6" style="color:#555524">Kelola akun, peran dan akses platform</p>

      <!-- Stat Cards -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="stat-card border border-saveat-accent">
          <p class="text-saveat-soft text-[11px] font-semibold uppercase tracking-wider mb-1">Merchant Aktif</p>
          <p class="text-saveat-dark text-[28px] font-bold leading-none">842</p>
        </div>
        <div class="stat-card bg-saveat-dark">
          <p class="text-saveat-accent text-[11px] font-semibold uppercase tracking-wider mb-1">Makanan Terselamatkan</p>
          <p class="text-white text-[28px] font-bold leading-none">45.2k</p>
        </div>
        <div class="stat-card border border-saveat-accent">
          <p class="text-saveat-soft text-[11px] font-semibold uppercase tracking-wider mb-1">User Terdaftar</p>
          <p class="text-saveat-dark text-[28px] font-bold leading-none">18.7k</p>
        </div>
        <div class="stat-card border border-saveat-accent">
          <p class="text-saveat-soft text-[11px] font-semibold uppercase tracking-wider mb-1">Transaksi Hari Ini</p>
          <p class="text-saveat-dark text-[28px] font-bold leading-none">1,340</p>
        </div>
      </div>

      <!-- ===== USER LIST PANEL ===== -->
      <div class="bg-white rounded-3xl shadow-sm p-4 lg:p-6">

        <!-- Search Bar -->
        <div class="relative mb-4">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
          </svg>
          <input
            id="searchInput"
            type="text"
            placeholder="Cari dengan nama atau email..."
            class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-saveat-light bg-[#F5F0E8] text-[14px] text-saveat-text placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-saveat-accent"
          />
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs flex gap-2 overflow-x-auto pb-1 mb-5">
          <button onclick="setFilter('semua')" data-filter="semua"
            class="filter-btn flex-shrink-0 px-4 py-1.5 rounded-full text-[13px] font-semibold bg-saveat-dark text-white transition-colors">
            Semua
          </button>
          <button onclick="setFilter('pelanggan')" data-filter="pelanggan"
            class="filter-btn flex-shrink-0 px-4 py-1.5 rounded-full text-[13px] font-semibold bg-saveat-light text-saveat-text hover:bg-saveat-accent transition-colors">
            Pelanggan
          </button>
          <button onclick="setFilter('merchant')" data-filter="merchant"
            class="filter-btn flex-shrink-0 px-4 py-1.5 rounded-full text-[13px] font-semibold bg-saveat-light text-saveat-text hover:bg-saveat-accent transition-colors">
            Merchant
          </button>
          <button onclick="setFilter('admin')" data-filter="admin"
            class="filter-btn flex-shrink-0 px-4 py-1.5 rounded-full text-[13px] font-semibold bg-saveat-light text-saveat-text hover:bg-saveat-accent transition-colors">
            Admin
          </button>
        </div>

        <!-- ===== MOBILE: Card List (hidden on lg) ===== -->
        <div id="mobileList" class="flex flex-col gap-3 lg:hidden">
          <!-- Cards injected by JS -->
        </div>

        <!-- ===== DESKTOP: Table (hidden on mobile) ===== -->
        <div class="hidden lg:block overflow-x-auto">
          <table class="w-full text-left text-[14px]">
            <thead>
              <tr class="border-b border-saveat-light">
                <th class="pb-3 pr-4 text-saveat-soft text-[11px] font-semibold uppercase tracking-wider">Pengguna</th>
                <th class="pb-3 pr-4 text-saveat-soft text-[11px] font-semibold uppercase tracking-wider">Email</th>
                <th class="pb-3 pr-4 text-saveat-soft text-[11px] font-semibold uppercase tracking-wider">Peran</th>
                <th class="pb-3 pr-4 text-saveat-soft text-[11px] font-semibold uppercase tracking-wider">Status</th>
                <th class="pb-3 text-saveat-soft text-[11px] font-semibold uppercase tracking-wider">Bergabung</th>
                <th class="pb-3"></th>
              </tr>
            </thead>
            <tbody id="desktopTableBody">
              <!-- Rows injected by JS -->
            </tbody>
          </table>
        </div>

        <!-- Load More -->
        <div class="mt-6 text-center">
          <button id="loadMoreBtn" onclick="loadMore()"
            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-2xl border border-saveat-accent bg-white text-saveat-dark text-[14px] font-semibold hover:bg-saveat-light transition-colors">
            Tampilkan lebih banyak
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
          </button>
          <p id="countLabel" class="text-saveat-soft text-[12px] mt-2"></p>
        </div>

      </div>
      <!-- end panel -->

    </div>
  </main>

</div>

<script>
  // ===== DATA =====
  const allUsers = [
    { name: 'Tomohiro',        email: 'tomohiro.ya@saveat.com',   role: 'admin',     status: 'active',   joined: '12 Jan 2024', initials: 'T',  color: '#4A6B35' },
    { name: 'Dapur Roti Nusantara', email: 'dapuroti.n@gmail.com', role: 'merchant', status: 'active',   joined: '3 Mar 2024',  initials: null, color: '#8B6914', icon: 'merchant' },
    { name: 'Elena Rodriguez', email: 'elena.rod@gmail.com',      role: 'pelanggan', status: 'ditunda',  joined: '20 Apr 2024', initials: 'ER', color: '#6B7280' },
    { name: 'Jamal Umum',      email: 'Jamal.u@gmail.com',        role: 'pelanggan', status: 'pending',  joined: '2 Jun 2024',  initials: 'JU', color: '#2D4A1E' },
    { name: 'Green Garden Deli', email: 'ggarden@gmail.com',       role: 'merchant', status: 'verif_ditunda', joined: '5 Jun 2024', initials: null, color: '#C8A96E', icon: 'merchant2' },
    { name: 'Siti Rahayu',     email: 'siti.r@gmail.com',         role: 'pelanggan', status: 'active',   joined: '8 Jun 2024',  initials: 'SR', color: '#9B4DCA' },
    { name: 'Warung Pak Budi', email: 'pakbudi.w@gmail.com',      role: 'merchant',  status: 'active',   joined: '10 Jun 2024', initials: null, color: '#D97706', icon: 'merchant' },
    { name: 'Andika Pratama',  email: 'andika.p@gmail.com',       role: 'pelanggan', status: 'active',   joined: '11 Jun 2024', initials: 'AP', color: '#2563EB' },
    { name: 'Resto Bahari',    email: 'restobahari@gmail.com',    role: 'merchant',  status: 'ditunda',  joined: '11 Jun 2024', initials: null, color: '#0891B2', icon: 'merchant2' },
    { name: 'Jerome',          email: 'jeromeadmin@saveat.com',   role: 'admin',     status: 'active',   joined: '1 Jan 2024',  initials: 'J',  color: '#2D4A1E' },
    { name: 'Rina Kusuma',     email: 'rina.k@gmail.com',         role: 'pelanggan', status: 'active',   joined: '12 Jun 2024', initials: 'RK', color: '#BE185D' },
    { name: 'Toko Sehat',      email: 'tokosehat@gmail.com',      role: 'merchant',  status: 'active',   joined: '12 Jun 2024', initials: null, color: '#059669', icon: 'merchant' },
  ];

  const ROLE_STYLES = {
    admin:     { bg: '#DBEAFE', text: '#1E40AF', label: 'ADMIN' },
    merchant:  { bg: '#FEF9C3', text: '#854D0E', label: 'MERCHANT' },
    pelanggan: { bg: '#EBF0E4', text: '#4A6B35', label: 'PELANGGAN' },
  };

  const STATUS_STYLES = {
    active:        { dot: '#22C55E', label: 'Active' },
    ditunda:       { dot: '#EF4444', label: 'ditunda', icon: true },
    pending:       { dot: '#F59E0B', label: 'Gabung 2j lalu' },
    verif_ditunda: { dot: '#EF4444', label: 'Verifikasi ditunda', icon: true },
  };

  let currentFilter = 'semua';
  let visibleCount = 5;
  let searchQuery = '';

  function getFiltered() {
    return allUsers.filter(u => {
      const matchFilter = currentFilter === 'semua' || u.role === currentFilter;
      const matchSearch = !searchQuery ||
        u.name.toLowerCase().includes(searchQuery) ||
        u.email.toLowerCase().includes(searchQuery);
      return matchFilter && matchSearch;
    });
  }

  function setFilter(f) {
    currentFilter = f;
    visibleCount = 5;
    document.querySelectorAll('.filter-btn').forEach(btn => {
      const active = btn.dataset.filter === f;
      btn.className = `filter-btn flex-shrink-0 px-4 py-1.5 rounded-full text-[13px] font-semibold transition-colors ${
        active ? 'bg-saveat-dark text-white' : 'bg-saveat-light text-saveat-text hover:bg-saveat-accent'
      }`;
    });
    render();
  }

  // Avatar HTML
  function avatarHtml(u, size = 'w-10 h-10') {
    if (u.icon === 'merchant') {
      return `<div class="${size} rounded-full flex items-center justify-center" style="background:${u.color}20">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="${u.color}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
        </svg>
      </div>`;
    }
    if (u.icon === 'merchant2') {
      return `<div class="${size} rounded-full flex items-center justify-center" style="background:${u.color}20">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="${u.color}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/>
        </svg>
      </div>`;
    }
    return `<div class="${size} rounded-full flex items-center justify-center text-white text-sm font-bold" style="background:${u.color}">${u.initials}</div>`;
  }

  function roleBadge(role) {
    const s = ROLE_STYLES[role] || { bg: '#eee', text: '#333', label: role };
    return `<span class="px-2 py-0.5 rounded-full text-[11px] font-bold" style="background:${s.bg};color:${s.text}">${s.label}</span>`;
  }

  function statusBadge(status) {
    const s = STATUS_STYLES[status] || { dot: '#aaa', label: status };
    const icon = s.icon
      ? `<svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`
      : `<span class="w-1.5 h-1.5 rounded-full inline-block" style="background:${s.dot}"></span>`;
    const color = s.icon ? '#EF4444' : s.dot === '#22C55E' ? '#16A34A' : '#D97706';
    return `<span class="inline-flex items-center gap-1 text-[12px] font-medium" style="color:${color}">${icon} ${s.label}</span>`;
  }

  function threeDotMenu(idx) {
    return `<div class="relative">
      <button onclick="toggleMenu(event,${idx})" class="w-7 h-7 rounded-full hover:bg-saveat-light flex items-center justify-center transition-colors">
        <svg width="16" height="16" fill="#6B8F52" viewBox="0 0 24 24"><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/></svg>
      </button>
      <div id="menu-${idx}" class="dropdown-menu absolute right-0 top-8 bg-white rounded-xl shadow-lg border border-saveat-light z-20 min-w-[140px] py-1">
        <button class="w-full text-left px-4 py-2 text-[13px] text-saveat-text hover:bg-saveat-light">Lihat Detail</button>
        <button class="w-full text-left px-4 py-2 text-[13px] text-saveat-text hover:bg-saveat-light">Edit Peran</button>
        <button class="w-full text-left px-4 py-2 text-[13px] text-red-500 hover:bg-red-50">Tangguhkan</button>
      </div>
    </div>`;
  }

  function toggleMenu(e, idx) {
    e.stopPropagation();
    document.querySelectorAll('.dropdown-menu').forEach((m, i) => {
      if (i === idx) m.classList.toggle('open');
      else m.classList.remove('open');
    });
  }
  document.addEventListener('click', () => {
    document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('open'));
  });

  function render() {
    const filtered = getFiltered();
    const visible = filtered.slice(0, visibleCount);

    // --- Mobile cards ---
    const mobileList = document.getElementById('mobileList');
    mobileList.innerHTML = visible.map((u, i) => `
      <div class="flex items-center gap-3 bg-white rounded-2xl p-3 border border-saveat-light shadow-sm">
        <div class="flex-shrink-0">${avatarHtml(u)}</div>
        <div class="flex-1 min-w-0">
          <p class="text-saveat-text text-[14px] font-semibold truncate">${u.name}</p>
          <p class="text-saveat-soft text-[12px] truncate">${u.email}</p>
          <div class="flex items-center gap-2 mt-1 flex-wrap">
            ${roleBadge(u.role)}
            ${statusBadge(u.status)}
          </div>
        </div>
        <div class="flex-shrink-0">${threeDotMenu(i)}</div>
      </div>
    `).join('');

    // --- Desktop table rows ---
    const tbody = document.getElementById('desktopTableBody');
    tbody.innerHTML = visible.map((u, i) => `
      <tr class="border-b border-saveat-light last:border-0 hover:bg-[#F9FBF7] transition-colors">
        <td class="py-3.5 pr-4">
          <div class="flex items-center gap-3">
            ${avatarHtml(u, 'w-9 h-9')}
            <span class="text-saveat-text font-medium">${u.name}</span>
          </div>
        </td>
        <td class="py-3.5 pr-4 text-saveat-soft">${u.email}</td>
        <td class="py-3.5 pr-4">${roleBadge(u.role)}</td>
        <td class="py-3.5 pr-4">${statusBadge(u.status)}</td>
        <td class="py-3.5 pr-4 text-saveat-soft text-[13px]">${u.joined}</td>
        <td class="py-3.5">${threeDotMenu(100 + i)}</td>
      </tr>
    `).join('');

    // --- Count label ---
    document.getElementById('countLabel').textContent =
      `Menampilkan ${visible.length} dari ${filtered.length.toLocaleString('id')} Pengguna`;

    // --- Load more btn ---
    document.getElementById('loadMoreBtn').style.display =
      visibleCount >= filtered.length ? 'none' : 'inline-flex';
  }

  function loadMore() {
    visibleCount += 5;
    render();
  }

  // Search
  document.getElementById('searchInput').addEventListener('input', function() {
    searchQuery = this.value.toLowerCase().trim();
    visibleCount = 5;
    render();
  });

  // Init
  render();
</script>
</body>
</html>
