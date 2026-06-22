<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SavEat – Dashboard Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            olive:   { DEFAULT: '#4a5c2f', dark: '#3a4a22', light: '#6b7c45' },
            cream:   { DEFAULT: '#f0f2dc', light: '#f8f9ee', dark: '#e2e4c8' },
            sage:    { DEFAULT: '#c8d5b3', light: '#dce8cc' },
            charcoal:'#2c2c2c',
          },
          fontFamily: {
            display:['"Playfair Display"','serif'],
            body:   ['"DM Sans"','sans-serif'],
          },
        },
      },
    }
  </script>
  <style>
    body { font-family:'DM Sans',sans-serif; background:#f0f2dc; }

    /* Sidebar */
    #sidebar { transition: transform .3s ease; }
    #sidebar.open { transform: translateX(0); }

    /* Card fade-in */
    @keyframes fadeUp {
      from { opacity:0; transform:translateY(20px); }
      to   { opacity:1; transform:translateY(0); }
    }
    .stat-card { animation: fadeUp .5s ease both; }
    .stat-card:nth-child(1){ animation-delay:.05s }
    .stat-card:nth-child(2){ animation-delay:.1s }
    .stat-card:nth-child(3){ animation-delay:.15s }
    .stat-card:nth-child(4){ animation-delay:.2s }
    .stat-card:nth-child(5){ animation-delay:.25s }

    /* Left accent bar */
    .accent-green { border-left:4px solid #4a5c2f; }
    .accent-red   { border-left:4px solid #dc3545; }
    .accent-none  { border-left:4px solid transparent; }
  </style>
</head>
<body class="min-h-screen bg-cream">

<!-- ═══════════════════════════════════
     SIDEBAR (hidden on mobile by default)
════════════════════════════════════ -->
<aside id="sidebar"
  class="fixed inset-y-0 left-0 z-40 w-64 bg-olive text-cream flex flex-col
         -translate-x-full md:translate-x-0 shadow-2xl">

  <!-- Brand -->
<div class="flex items-center gap-3 px-5 py-5 border-b border-[#5a7a2a]">
        <div class="w-9 h-9 bg-[#8faa3e] rounded-xl flex items-center justify-center">
          <img src="../img/Logo.png" alt="SavEat Logo" class="w-9 h-9 object-contain rounded-xl" />
            <path d="M8 1.5C5.2 1.5 3 3.7 3 6.5c0 3.8 5 8.5 5 8.5s5-4.7 5-8.5C13 3.7 10.8 1.5 8 1.5z" fill="white"/>
            <circle cx="8" cy="6.5" r="1.8" fill="#4a6a1a"/>
          
        </div>
         <span class="font-display text-xl font-bold">SavEat</span>
</div>
  <!-- Nav -->
  <nav class="flex-1 px-4 py-6 space-y-1 text-sm font-medium">
    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-cream/15 text-cream">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
        <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
      </svg>
      Dashboard
    </a>
    <a href="profil.html" class="flex items-center gap-3 px-4 py-3 rounded-xl text-cream/70 hover:bg-cream/10 hover:text-cream transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
      </svg>
      Profil Admin
    </a>
    <a href="user_manag.html" class="flex items-center gap-3 px-4 py-3 rounded-xl text-cream/70 hover:bg-cream/10 hover:text-cream transition-colors">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
    </svg>
    User Management
    </a>
    <a href="verif_mercthant.html" class="flex items-center gap-3 px-4 py-3 rounded-xl text-cream/70 hover:bg-cream/10 hover:text-cream transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
      </svg>
      Verifikasi Merchant
    </a>
    <a href="analisis_pen.html" class="flex items-center gap-3 px-4 py-3 rounded-xl text-cream/70 hover:bg-cream/10 hover:text-cream transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
      </svg>
      Analisis Penjualan
    </a>
    <a href="privt_keamanan.html" class="flex items-center gap-3 px-4 py-3 rounded-xl text-cream/70 hover:bg-cream/10 hover:text-cream transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
      </svg>
      Privasi & Keamanan
      <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">12</span>
    </a>
  </nav>
</aside>

<!-- Overlay for mobile sidebar -->
<div id="overlay" class="fixed inset-0 z-30 bg-black/40 hidden md:hidden" onclick="toggleSidebar()"></div>

<!-- ═══════════════════════════════════
     MAIN CONTENT
════════════════════════════════════ -->
<div class="md:ml-64 min-h-screen flex flex-col">

  <!-- TOP NAVBAR -->
  <header class="sticky top-0 z-20 bg-white border-b border-gray-100 shadow-sm px-5 py-4 flex items-center gap-4">
    <!-- Hamburger (mobile) -->
    <button onclick="toggleSidebar()" class="md:hidden w-9 h-9 flex flex-col justify-center gap-1.5 items-center">
      <span class="w-5 h-0.5 rounded" style="background-color:#6c6d2d"></span>
      <span class="w-5 h-0.5 rounded" style="background-color:#6c6d2d"></span>
      <span class="w-5 h-0.5 rounded" style="background-color:#6c6d2d"></span>
    </button>
    <h1 class="font-display text-lg font-bold" style="color:#6c6d2d">Dashboard Admin</h1>
  </header>

  <!-- PAGE BODY -->
  <main class="flex-1 px-5 py-6 space-y-6">

    <!-- Page title -->
    <div>
      <h2 class="font-display text-3xl font-extrabold leading-tight" style="color:#555524">Ringkasan Platform</h2>
      <p class="text-xs text-charcoal/50 mt-1">Minggu, 7 Juni 2026</p>
    </div>

    <!-- STAT CARDS -->
    <div class="space-y-3 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div class="stat-card accent-green bg-white rounded-2xl px-5 py-4 shadow-sm">
        <p class="text-xs font-medium uppercase tracking-wide">Total Merchant Aktif</p>
        <p class="font-display text-3xl font-extrabold mt-1">{{ $totalMerchantAktif }}</p>
      </div>
      <div class="stat-card accent-green bg-white rounded-2xl px-5 py-4 shadow-sm">
        <p class="text-xs font-medium uppercase tracking-wide">Total Konsumen Aktif</p>
        <p class="font-display text-3xl font-extrabold mt-1">{{ $totalKonsumenAktif }}</p>
      </div>
      <div class="stat-card accent-green bg-white rounded-2xl px-5 py-4 shadow-sm">
        <p class="text-xs font-medium uppercase tracking-wide">Total Listing</p>
        <p class="font-display text-3xl font-extrabold mt-1">{{ $totalListing }}</p>
      </div>
      <div class="stat-card accent-green bg-white rounded-2xl px-5 py-4 shadow-sm">
        <p class="text-xs font-medium uppercase tracking-wide">Total Klaim</p>
        <p class="font-display text-3xl font-extrabold mt-1">{{ $totalKlaim }}</p>
      </div>
      <div class="stat-card accent-green bg-white rounded-2xl px-5 py-4 shadow-sm">
        <p class="text-xs font-medium uppercase tracking-wide">Makanan Terselamatkan</p>
        <p class="font-display text-3xl font-extrabold mt-1">{{ $totalMakananTerselamatkan }}</p>
      </div>
    </div>

    <!-- ── BAR CHART ── -->
    <div class="bg-white rounded-2xl p-5 shadow-sm">
      <div class="relative h-56">
        <canvas id="summaryChart"></canvas>
      </div>
    </div>

  </main>

  <!-- FOOTER -->
  <footer class="text-center text-xs text-charcoal/40 py-6 border-t border-sage/30" style="color:#555524">
    © 2026 Saveat. All rights reserved.
  </footer>
</div>

<!-- ═══════════════════════════════════
     JS
════════════════════════════════════ -->
<script>
  // Sidebar toggle
  function toggleSidebar() {
    const s = document.getElementById('sidebar');
    const o = document.getElementById('overlay');
    s.classList.toggle('open');
    o.classList.toggle('hidden');
  }

  // Bar Chart
  const ctx = document.getElementById('summaryChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Pengguna','Aktif','Baru Daftar','Pendapatan','dilaporkan'],
      datasets: [
        {
          data: [85, 50, 45, 70, 47],
          backgroundColor: ['#4a5c2f','#c8d5b3','#c8d5b3','#4a5c2f','#c8d5b3'],
          borderRadius: 0,
          barPercentage: 0.6,
          categoryPercentage: 0.7,
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#fff',
          titleColor: '#555524',
          bodyColor: '#555524',
          borderColor: '#c8d5b3',
          borderWidth: 1,
        }
      },
      scales: {
        x: {
          grid: { display: false },
          ticks: { font: { family: 'DM Sans', size: 10 }, color: '#55552499' }
        },
        y: {
          grid: { color: '#c8d5b340' },
          ticks: { font: { family: 'DM Sans', size: 10 }, color: '#55552499' },
          beginAtZero: true,
          max: 100,
        }
      }
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: 'top',
          labels: {
            font: { family: 'DM Sans', size: 11 },
            color: '#2c2c2c',
            boxWidth: 12,
            boxHeight: 12,
            borderRadius: 4,
          }
        },
        tooltip: {
          backgroundColor: '#fff',
          titleColor: '#2c2c2c',
          bodyColor: '#4a5c2f',
          borderColor: '#c8d5b3',
          borderWidth: 1,
          titleFont: { family: 'DM Sans', weight: '600' },
          bodyFont: { family: 'DM Sans' },
          padding: 10,
        }
      },
      scales: {
        x: {
          grid: { display: false },
          ticks: { font: { family: 'DM Sans', size: 10 }, color: '#2c2c2c99' }
        },
        y: {
          grid: { color: '#c8d5b320' },
          ticks: { font: { family: 'DM Sans', size: 10 }, color: '#2c2c2c99' },
          beginAtZero: true,
          max: 100,
        }
      }
    }
  });
</script>
</body>
</html>
