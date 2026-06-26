<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title', 'Dashboard') - SaveEat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body x-data="{ sidebarOpen: false }" class="bg-gradient-to-r from-[#CFD086] to-[#F1F2CF] min-h-screen font-poppins">

    <div class="flex min-h-screen overflow-x-hidden">
        
        @if(auth()->check())
            @if(auth()->user()->role === 'admin')
                @include('components.sidebar-admin')
                
            @elseif(auth()->user()->role === 'merchant')
                @include('components.sidebar-merchant')
                
            @else
                @include('components.sidebar-customer')
            @endif
        @endif

        {{-- Main Wrapper --}}
        <div class="flex-1 flex flex-col justify-between min-h-screen w-full lg:pl-64">
            
            <div>
                @if(auth()->check())
                    @if(auth()->user()->role === 'admin')
                        @include('components.navbar-admin')
                        
                    @elseif(auth()->user()->role === 'merchant')
                        @include('components.navbar-merchant')
                        
                    @else
                        @include('components.navbar-customer')
                    @endif
                @endif

                {{-- Main Content Slot --}}
                <main class="p-4 md:p-6 lg:p-8 flex justify-center">
                    <div class="w-full max-w-5xl space-y-6">
                        @yield('content')
                    </div>
                </main>
            </div>

            {{-- Footer Component --}}
            <x-footer />
        </div>
    </div>

</body>
</html>