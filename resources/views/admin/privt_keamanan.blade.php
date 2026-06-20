<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Privasi & Keamanan – SavEat</title>
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

    .menu-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 16px 0;
      border-bottom: 1px solid #EBF0E4;
      cursor: pointer;
      transition: background 0.15s;
    }
    .menu-row:last-child { border-bottom: none; }
    .menu-row:hover { background: #f7faf4; border-radius: 10px; }

    /* Toggle switch */
    .toggle-switch {
      position: relative;
      width: 44px;
      height: 24px;
      flex-shrink: 0;
    }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-slider {
      position: absolute;
      cursor: pointer;
      top: 0; left: 0; right: 0; bottom: 0;
      background-color: #D1D5DB;
      border-radius: 9999px;
      transition: 0.25s;
    }
    .toggle-slider::before {
      content: "";
      position: absolute;
      height: 18px; width: 18px;
      left: 3px; bottom: 3px;
      background-color: white;
      border-radius: 50%;
      transition: 0.25s;
      box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    .toggle-switch input:checked + .toggle-slider {
      background-color: #2D4A1E;
    }
    .toggle-switch input:checked + .toggle-slider::before {
      transform: translateX(20px);
    }
  </style>
</head>
<body class="font-sans min-h-screen bg-whitesmoke">
<div class="flex min-h-screen">

  <!-- ===== MAIN CONTENT ===== -->
  <main class="w-full pb-24 lg:pb-0">
      <div class="w-full max-w-7xl mx-auto p-4 lg:p-8">
        

      <!-- Header -->
      <div class="flex items-center justify-between mb-6 lg:justify-start lg:gap-4">
        <a href="index.html" class="w-9 h-9 rounded-full bg-white flex items-center justify-center shadow-sm">
          <svg width="18" height="18" fill="none" stroke="#2D4A1E" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M19 12H5M12 5l-7 7 7 7"/>
          </svg>
        </a>
        <span class="text-saveat-text text-[17px] font-semibold lg:text-[20px] lg:font-bold">Privasi & Keamanan</span>
        <div class="lg:hidden w-9"></div> <!-- spacer for mobile centering -->
      </div>

      <!-- ===== KEAMANAN AKUN ===== -->
      <div class="bg-white rounded-2xl border border-saveat-light shadow-sm px-5 py-2 mb-4">
        <div class="flex items-center gap-2 pt-3 pb-1">
          <svg width="16" height="16" fill="none" stroke="#4A6B35" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          <span class="text-saveat-mid text-[13px] font-bold uppercase tracking-wide">Keamanan Akun</span>
        </div>

        <!-- Ubah Password -->
        <div class="menu-row">
          <div class="pr-4">
            <p class="text-saveat-text text-[15px] font-semibold">Ubah Password</p>
            <p class="text-saveat-soft text-[13px] mt-0.5">Perbarui kata sandi akun Anda secara berkala.</p>
          </div>
          <svg width="18" height="18" fill="none" stroke="#9CA3AF" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" class="flex-shrink-0"><path d="M9 18l6-6-6-6"/></svg>
        </div>
      </div>

      <!-- ===== STATUS IZIN ===== -->
      <div class="bg-white rounded-2xl border border-saveat-light shadow-sm px-5 py-2 mb-6">
        <div class="flex items-center gap-2 pt-3 pb-1">
          <svg width="16" height="16" fill="none" stroke="#4A6B35" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
          <span class="text-saveat-mid text-[13px] font-bold uppercase tracking-wide">Status Izin</span>
        </div>

        <!-- Layanan Lokasi -->
        <div class="menu-row">
          <div class="pr-4">
            <p class="text-saveat-text text-[15px] font-semibold">Layanan Lokasi</p>
            <p class="text-saveat-soft text-[13px] mt-0.5">Izinkan Saveat untuk menemukan peluang penyelamatan makanan di dekat Anda</p>
          </div>
          <label class="toggle-switch">
            <input type="checkbox" id="toggleLokasi" checked onchange="onToggleChange('toggleLokasi', this.checked)" />
            <span class="toggle-slider"></span>
          </label>
        </div>

        <!-- Notifikasi -->
        <div class="menu-row">
          <div class="pr-4">
            <p class="text-saveat-text text-[15px] font-semibold">Notifikasi</p>
            <p class="text-saveat-soft text-[13px] mt-0.5">Pemberitahuan untuk penawaran "Hampir Habis" dan pengingat pengambilan Makanan</p>
          </div>
          <label class="toggle-switch">
            <input type="checkbox" id="toggleNotif" onchange="onToggleChange('toggleNotif', this.checked)" />
            <span class="toggle-slider"></span>
          </label>
        </div>
      </div>

      <!-- ===== INFO BOX ===== -->
      <div class="rounded-2xl p-5" style="background:#E5F5E9; border:1px solid #C9EAD1">
        <div class="flex items-start gap-3 mb-3">
          <div class="flex-shrink-0 mt-0.5">
            <svg width="20" height="20" fill="none" stroke="#1F8A3D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
          </div>
          <p class="text-[#1F5C2E] text-[15px] font-bold">Data Anda aman bersama Kami</p>
        </div>
        <p class="text-[#2D5A3D] text-[13px] leading-relaxed">
          Data Anda aman bersama kami Saveat menggunakan enkripsi AES-256 standar industri untuk melindungi informasi pribadi Anda. Kami tidak pernah menjual data Anda kepada pihak ketiga.
        </p>
        <p class="text-[#2D5A3D] text-[13px] leading-relaxed mt-3">
          Misi kami adalah menyelamatkan makanan, bukan mengorbankan privasi Anda.
        </p>
      </div>

    </div>
  </main>

</div>

<script>
  // ===== TOGGLE STATE =====
  // Backend nantinya bisa ambil/simpan state dari sini
  const permissionState = {
    toggleLokasi: true,
    toggleNotif: false,
  };

  function onToggleChange(id, checked) {
    permissionState[id] = checked;
    console.log('Permission updated:', id, '=', checked, permissionState);

    // TODO: kirim ke backend, contoh:
    // fetch('/api/settings/permission', {
    //   method: 'POST',
    //   headers: { 'Content-Type': 'application/json' },
    //   body: JSON.stringify({ key: id, value: checked })
    // });
  }

  // ===== UBAH PASSWORD CLICK =====
  document.querySelector('.menu-row').addEventListener('click', () => {
    // TODO: arahkan ke halaman ubah password atau buka modal
    console.log('Buka form ubah password');
    // window.location.href = 'ubah_password.html';
  });
</script>
</body>
</html>
