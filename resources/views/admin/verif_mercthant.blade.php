<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Verifikasi Merchant – SavEat</title>
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
     body { background-color: whitesmoke; }

    @supports (padding-bottom: env(safe-area-inset-bottom)) {
      .bottom-nav { padding-bottom: env(safe-area-inset-bottom); }
    }

    .merchant-img {
      width: 100%;
      height: 160px;
      object-fit: cover;
      border-radius: 14px 14px 0 0;
      background: #C8D8B0;
      display: block;
    }

    .merchant-img-placeholder {
      width: 100%;
      height: 160px;
      border-radius: 14px 14px 0 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* filter dropdown */
    .filter-dropdown { display: none; }
    .filter-dropdown.open { display: block; }

    /* pagination active */
    .page-btn.active {
      background: #2D4A1E;
      color: white;
    }
  </style>
</head>
<body class="font-sans min-h-screen bg-whitesmoke">
  <div class="flex min-h-screen">

    <!-- ===== MAIN CONTENT ===== -->
    <main class="w-full pb-24 lg:pb-0">
      <div class="w-full max-w-7xl mx-auto p-4 lg:p-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <a href="index.html" class="w-9 h-9 rounded-full bg-white flex items-center justify-center shadow-sm">
              <svg width="18" height="18" fill="none" stroke="#2D4A1E" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
              </svg>
            </a>

          <span class="text-saveat-text text-[17px] font-semibold">Verifikasi Merchant</span>
            <button class="w-9 h-9 rounded-full overflow-hidden shadow-sm">
                 <img src="img/foto.png" alt="Profil" class="w-full h-full object-cover">
            </button>
          </div>

         <!-- ===== STAT CARDS ===== -->
         <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

        <!-- Verifikasi Ditunda -->
        <div class="rounded-2xl p-5 text-white relative overflow-hidden" style="background: linear-gradient(135deg, #3a6b2a 0%, #4e8a3a 100%);">
          <!-- dashed border decoration -->
          <div class="absolute right-4 top-4 w-20 h-20 rounded-xl border-2 border-dashed border-white/30"></div>
          <p class="text-white/80 text-[13px] font-semibold mb-1">Verifikasi ditunda</p>
          <p class="text-[42px] font-extrabold leading-none mb-2">24</p>
          <div class="flex items-center gap-1 text-[12px] text-white/80">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            +12% from last week
          </div>
         </div>

        <!-- Disetujui Minggu Ini -->
        <div class="bg-white rounded-2xl p-5 border border-saveat-light">
          <p class="text-saveat-soft text-[13px] font-semibold mb-1">Disetujui Minggu ini</p>
          <p class="text-saveat-dark text-[42px] font-extrabold leading-none mb-3">142</p>
          <div class="w-full bg-saveat-light rounded-full h-2">
            <div class="bg-saveat-mid h-2 rounded-full" style="width: 85%"></div>
          </div>
          <p class="text-saveat-soft text-[11px] mt-1 text-right">85%</p>
        </div>

        <!-- Ditolak Minggu Ini -->
        <div class="bg-white rounded-2xl p-5 border border-saveat-light">
          <p class="text-saveat-soft text-[13px] font-semibold mb-1">Ditolak Minggu ini</p>
          <p class="text-saveat-dark text-[42px] font-extrabold leading-none mb-2">8</p>
          <p class="text-saveat-soft text-[12px] leading-relaxed">Terutama karena sertifikasi kebersihan yang tidak valid.</p>
        </div>
        </div>
        <!-- ===== SEARCH + FILTER ===== -->
      <div class="flex gap-3 mb-5 relative">
        <div class="relative flex-1">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
          </svg>
          <input id="searchInput" type="text" placeholder="Cari Merchant..."
            class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-saveat-light bg-white text-[14px] text-saveat-text placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-saveat-accent shadow-sm"
          />
        </div>
        <!-- Filter button -->
        <div class="relative">
          <button onclick="toggleFilter()" class="w-10 h-10 rounded-xl bg-white border border-saveat-light flex items-center justify-center shadow-sm hover:bg-saveat-light transition-colors">
            <svg width="16" height="16" fill="none" stroke="#4A6B35" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/>
            </svg>
          </button>
          <!-- Dropdown -->
          <div id="filterDropdown" class="filter-dropdown absolute right-0 top-12 bg-white rounded-2xl shadow-lg border border-saveat-light z-20 min-w-[160px] p-2">
            <p class="text-[11px] text-saveat-soft font-semibold uppercase px-3 py-1 tracking-wider">Filter Status</p>
            <button onclick="setFilter('semua')" data-fval="semua" class="fval-btn w-full text-left px-3 py-2 rounded-xl text-[13px] font-medium text-saveat-dark bg-saveat-light mb-0.5">Semua</button>
            <button onclick="setFilter('baru')" data-fval="baru" class="fval-btn w-full text-left px-3 py-2 rounded-xl text-[13px] font-medium text-saveat-text hover:bg-saveat-light mb-0.5">Baru</button>
            <button onclick="setFilter('ditunda')" data-fval="ditunda" class="fval-btn w-full text-left px-3 py-2 rounded-xl text-[13px] font-medium text-saveat-text hover:bg-saveat-light mb-0.5">Ditunda</button>
            <button onclick="setFilter('pending')" data-fval="pending" class="fval-btn w-full text-left px-3 py-2 rounded-xl text-[13px] font-medium text-saveat-text hover:bg-saveat-light">Pending</button>
          </div>
        </div>
      </div>
 <!-- ===== MERCHANT LIST ===== -->
      <!-- Mobile: single col | Desktop: 2-3 col grid -->
      <div id="merchantGrid" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
        <!-- Injected by JS -->
      </div>

      <!-- Empty state -->
      <div id="emptyState" class="hidden text-center py-16 text-saveat-soft">
        <svg class="mx-auto mb-3" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        <p class="text-[15px] font-semibold">Tidak ada merchant ditemukan</p>
      </div>

      <!-- ===== PAGINATION ===== -->
      <div id="pagination" class="flex items-center justify-center gap-2 pb-4">
        <!-- Injected by JS -->
      </div>
      </div>
    </main>
    <script>
  // ===== DATA =====
  const merchants = [
    {
      id: 1,
      name: 'The Green Crust',
      category: 'Bakery & Cafe',
      submitted: 'Oct 24, 2023',
      location: 'San Francisco, CA',
      status: 'baru',
      verified: true,
      img: 'img/cafe.png',
      imgColor: '#A8C896',
    },
    {
      id: 2,
      name: 'Earthly Eats Bistro',
      category: 'Restaurant',
      submitted: 'Oct 25, 2023',
      location: 'Portland, OR',
      status: 'ditunda',
      verified: false,
      img: '',
      imgColor: '#8BB87A',
    },
    {
      id: 3,
      name: 'Pure Press Juicery',
      category: 'Beverages',
      submitted: 'Oct 26, 2023',
      location: 'Seattle, WA',
      status: 'pending',
      verified: false,
      img: '',
      imgColor: '#B8D4A8',
    },
    {
      id: 4,
      name: 'Warung Pak Hasan',
      category: 'Warung Makan',
      submitted: 'Oct 27, 2023',
      location: 'Jakarta, ID',
      status: 'baru',
      verified: false,
      img: '',
      imgColor: '#7BAE6A',
    },
    {
      id: 5,
      name: 'Sari Rasa Kitchen',
      category: 'Restaurant',
      submitted: 'Oct 28, 2023',
      location: 'Bandung, ID',
      status: 'ditunda',
      verified: false,
      img: '',
      imgColor: '#6B9E5A',
    },
    {
      id: 6,
      name: 'Nusa Bali Delights',
      category: 'Bakery & Cafe',
      submitted: 'Oct 29, 2023',
      location: 'Bali, ID',
      status: 'baru',
      verified: true,
      img: '',
      imgColor: '#9DC88D',
    },
    {
      id: 7,
      name: 'Healthy Corner',
      category: 'Health Food',
      submitted: 'Oct 30, 2023',
      location: 'Surabaya, ID',
      status: 'pending',
      verified: false,
      img: '',
      imgColor: '#C4DDB4',
    },
    {
      id: 8,
      name: 'Mie Ayam Berkah',
      category: 'Warung Makan',
      submitted: 'Oct 31, 2023',
      location: 'Yogyakarta, ID',
      status: 'baru',
      verified: false,
      img: '',
      imgColor: '#5A9448',
    },
    {
      id: 9,
      name: 'The Bread Box',
      category: 'Bakery & Cafe',
      submitted: 'Nov 1, 2023',
      location: 'Medan, ID',
      status: 'ditunda',
      verified: false,
      img: '',
      imgColor: '#A0C490',
    },
  ];

  const STATUS_BADGE = {
    baru:    { label: 'Baru',   bg: '#22C55E', text: 'white' },
    ditunda: { label: 'Ditunda', bg: '#E85D3A', text: 'white' },
    pending: { label: 'Pending', bg: '#F59E0B', text: 'white' },
  };

  const ITEMS_PER_PAGE = 4;
  let currentPage = 1;
  let currentFilter = 'semua';
  let searchQuery = '';

  function getFiltered() {
    return merchants.filter(m => {
      const matchFilter = currentFilter === 'semua' || m.status === currentFilter;
      const matchSearch = !searchQuery ||
        m.name.toLowerCase().includes(searchQuery) ||
        m.category.toLowerCase().includes(searchQuery) ||
        m.location.toLowerCase().includes(searchQuery);
      return matchFilter && matchSearch;
    });
  }

  function setFilter(f) {
    currentFilter = f;
    currentPage = 1;
    document.querySelectorAll('.fval-btn').forEach(btn => {
      const active = btn.dataset.fval === f;
      btn.className = `fval-btn w-full text-left px-3 py-2 rounded-xl text-[13px] font-medium mb-0.5 ${
        active ? 'bg-saveat-light text-saveat-dark font-semibold' : 'text-saveat-text hover:bg-saveat-light'
      }`;
    });
    document.getElementById('filterDropdown').classList.remove('open');
    render();
  }

  function toggleFilter() {
    document.getElementById('filterDropdown').classList.toggle('open');
  }
  document.addEventListener('click', e => {
    if (!e.target.closest('.relative')) {
      document.getElementById('filterDropdown').classList.remove('open');
    }
  });

  // Placeholder SVG images per color
  function placeholderImg(color, name) {
    // Inline SVG as data URL for placeholder
    return `data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='160'%3E%3Crect width='400' height='160' fill='${encodeURIComponent(color)}'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='Inter,sans-serif' font-size='18' fill='white' opacity='0.7'%3E${encodeURIComponent(name)}%3C/text%3E%3C/svg%3E`;
  }

  function merchantCard(m) {
    const badge = STATUS_BADGE[m.status] || {};
    const imgSrc = m.img || placeholderImg(m.imgColor, m.name);

    return `
    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-saveat-light flex flex-col">
      <!-- Image -->
      <div class="relative">
        <img src="${imgSrc}" alt="${m.name}" class="merchant-img" />
        <!-- Status badge -->
        <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-[12px] font-bold" style="background:${badge.bg};color:${badge.text}">
          ${badge.label}
        </span>
      </div>

      <!-- Info -->
      <div class="p-4 flex-1 flex flex-col gap-3">
        <div class="flex items-start justify-between gap-2">
          <div>
            <p class="text-saveat-text text-[15px] font-bold leading-tight">${m.name}</p>
            <p class="text-saveat-soft text-[13px]">${m.category}</p>
          </div>
          <!-- verified shield or hourglass -->
          <div class="flex-shrink-0 mt-0.5">
            ${m.verified
              ? `<svg width="20" height="20" fill="none" stroke="#4A6B35" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>`
              : m.status === 'pending'
              ? `<svg width="18" height="18" fill="none" stroke="#F59E0B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 22h14M5 2h14M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22M17 2v4.172a2 2 0 0 1-.586 1.414L12 12 7.586 7.586A2 2 0 0 1 7 6.172V2"/></svg>`
              : `<svg width="18" height="18" fill="none" stroke="#6B7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>`
            }
          </div>
        </div>

        <div class="flex flex-col gap-1.5 text-[12px] text-saveat-soft">
          <div class="flex items-center gap-1.5">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Submitted: ${m.submitted}
          </div>
          <div class="flex items-center gap-1.5">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            ${m.location}
          </div>
        </div>

        <!-- CTA Button -->
        <a href="#" class="mt-auto w-full flex items-center justify-center gap-2 py-2.5 rounded-xl font-semibold text-[14px] text-white transition-colors hover:opacity-90" style="background:#2D4A1E">
          Lihat Merchant
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
      </div>
    </div>`;
  }

  function renderPagination(total) {
    const totalPages = Math.ceil(total / ITEMS_PER_PAGE);
    const pg = document.getElementById('pagination');
    if (totalPages <= 1) { pg.innerHTML = ''; return; }

    let html = '';

    // Prev
    html += `<button onclick="goPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}
      class="w-8 h-8 rounded-lg flex items-center justify-center border border-saveat-light bg-white text-saveat-soft disabled:opacity-30 hover:bg-saveat-light transition-colors">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
    </button>`;

    for (let i = 1; i <= totalPages; i++) {
      html += `<button onclick="goPage(${i})" class="page-btn w-8 h-8 rounded-lg text-[13px] font-semibold border border-saveat-light bg-white text-saveat-text hover:bg-saveat-light transition-colors ${i === currentPage ? 'active' : ''}">${i}</button>`;
    }

    // Next
    html += `<button onclick="goPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}
      class="w-8 h-8 rounded-lg flex items-center justify-center border border-saveat-light bg-white text-saveat-soft disabled:opacity-30 hover:bg-saveat-light transition-colors">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
    </button>`;

    pg.innerHTML = html;
  }

  function goPage(p) {
  const filtered = getFiltered();
  const totalPages = Math.ceil(filtered.length / ITEMS_PER_PAGE);
  if (p < 1 || p > totalPages) return;
  currentPage = p;
  render();
  document.getElementById('merchantGrid').scrollIntoView({ behavior: 'smooth', block: 'start' });
}
  function render() {
    const filtered = getFiltered();
    const start = (currentPage - 1) * ITEMS_PER_PAGE;
    const paged = filtered.slice(start, start + ITEMS_PER_PAGE);

    const grid = document.getElementById('merchantGrid');
    const empty = document.getElementById('emptyState');

    if (paged.length === 0) {
      grid.innerHTML = '';
      empty.classList.remove('hidden');
    } else {
      empty.classList.add('hidden');
      grid.innerHTML = paged.map(merchantCard).join('');
    }

    renderPagination(filtered.length);
  }

  // Search
  document.getElementById('searchInput').addEventListener('input', function () {
    searchQuery = this.value.toLowerCase().trim();
    currentPage = 1;
    render();
  });

  render();
</script>
</body>
</html>
