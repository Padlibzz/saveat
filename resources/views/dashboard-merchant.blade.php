@section('page_title', 'Dashboard Merchant')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Merchant's Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>
<body
    x-data="{ sidebarOpen: false }"
    class="bg-gradient-to-r from-[#CFD086] to-[#F1F2CF] min-h-screen font-[Poppins]">

    <div class="flex min-h-screen">
        <x-sidebar />

        <!-- Main Content -->
        <div class="flex-1">

            <!-- Navbar -->
            <x-navbar />

            <section class="p-6 mb-2 lg:ml-64">
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif

            {{-- Cek jika ada session error --}}
            @if(session('error'))
                <x-alert type="error" :message="session('error')" />
            @endif
            

            </section>

            <x-footer/>
        </div>

    </div>

</body>
</html>