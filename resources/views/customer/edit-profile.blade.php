<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - SaveEat</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
        body{
            font-family:'Poppins',sans-serif;
            background:#F8F8F3;
        }

        input:focus{
            outline:none;
            border-color:#545523;
            box-shadow:0 0 0 4px rgba(84,85,35,.1);
        }

        .card{
            border-radius:30px;
            box-shadow:
            0 15px 35px rgba(0,0,0,.05);
            border:1px solid #ececec;
        }

        .save-btn{
            transition:.25s;
        }

        .save-btn:hover{
            transform:translateY(-2px);
        }

        .profile-photo{
            transition:.25s;
        }

        .profile-photo:hover{
            transform:scale(1.02);
        }

        .camera-btn{
            transition:.25s;
        }

        .camera-btn:hover{
            transform:scale(1.08);
        }

        input{
            transition:.25s;
        }

        input:hover{
            border-color:#545523;
        }

        button{
            transition:.25s;
        }

        button:active{
            transform:scale(.98);
        }

        img{
            transition:.3s;
        }

        img:hover{
            transform:scale(1.03);
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="max-w-4xl mx-auto py-10 px-5">
    {{-- HEADER --}}
    <div class="flex items-center gap-4 mb-10">
        <a href="{{ route('profile') }}"
            class="w-11 h-11 rounded-full bg-white shadow flex items-center justify-center hover:bg-[#545523] hover:text-white duration-300">

            <i class="fa-solid fa-arrow-left"></i>
        </a>

        <div>
            <h1 class="text-3xl font-bold text-[#545523]">
                Edit Profil
            </h1>
            <p class="text-gray-500 mt-1">
                Perbarui informasi akun Anda.
            </p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 rounded-xl bg-green-100 border border-green-300 text-green-700 p-4">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 rounded-xl bg-red-100 border border-red-300 text-red-600 p-4">
        <ul class="list-disc ml-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm" class="card p-8">
        @csrf
        @method('PUT')

        <div class="flex flex-col items-center">
            {{-- FOTO --}}
            <div class="relative">
                <img id="preview-image" src="{{ $user->profil_image
                        ? asset('storage/'.$user->profil_image)
                        : asset('img/default-avatar.png') }}"
                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=F1F2CF&color=545523&size=256'"
                    class="profile-photo w-40 h-40 rounded-full object-cover border-4 border-white shadow-xl ring-4 ring-[#F1F2CF]">
                <label
                    for="profil_image"
                    class="camera-btn absolute bottom-2 right-2 w-11 h-11 rounded-full bg-[#545523] text-white flex items-center justify-center cursor-pointer shadow-lg">
                    <i class="fa-solid fa-camera"></i>
                </label>

                <input type="file" id="profil_image" name="profil_image" accept="image/*" class="hidden">
            </div>

            <h2 class="mt-5 text-xl font-semibold text-gray-700">
                {{ $user->name }}
            </h2>

            <p class="text-gray-500">
                {{ $user->email }}
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-7 mt-10">
            {{-- NAMA --}}
            <div>
                <label class="font-semibold text-gray-700">
                    Nama Lengkap
                </label>
                <div class="relative mt-2">
                    <i class="fa-solid fa-user absolute left-4 top-4 text-gray-400"></i>
                    <input type="text" name="name" value="{{ old('name',$user->name) }}" class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-300">
                </div>
                @error('name')
                <small class="text-red-500">
                    {{ $message }}
                </small>
                @enderror
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="font-semibold text-gray-700">
                    Alamat Email
                </label>
                <div class="relative mt-2">
                    <i class="fa-solid fa-envelope absolute left-4 top-4 text-gray-400"></i>
                    <input type="email" name="email" value="{{ old('email',$user->email) }}" class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-300">
                </div>
                @error('email')
                <small class="text-red-500">
                    {{ $message }}
                </small>
                @enderror
            </div>

            {{-- LOKASI --}}
            <div class="md:col-span-2">
                <label class="font-semibold text-gray-700">
                    Lokasi
                </label>
                <div class="relative mt-2">
                    <i class="fa-solid fa-location-dot absolute left-4 top-4 text-gray-400"></i>
                    <input type="text" name="alamat" value="{{ old('alamat',$profil->alamat ?? '') }}" placeholder="Masukkan alamat lengkap..." class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-300">
                </div>
                @error('alamat')
                <small class="text-red-500">
                    {{ $message }}
                </small>
                @enderror
            </div>
        </div>

        <div class="mt-10">
            <button id="saveButton" type="submit" class="save-btn w-full bg-[#545523] hover:bg-[#42441C] text-white py-4 rounded-xl font-semibold text-lg">
                <i class="fa-solid fa-floppy-disk mr-2"></i>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    // ==========================
    // Preview Foto Profil
    // ==========================

    const imageInput = document.getElementById('profil_image');
    const previewImage = document.getElementById('preview-image');

    imageInput.addEventListener('change', function (e) {

        const file = e.target.files[0];

        if (!file) return;

        const reader = new FileReader();

        reader.onload = function(event){

            previewImage.src = event.target.result;

        }

        reader.readAsDataURL(file);

    });



    // ==========================
    // Loading Button
    // ==========================

    const form = document.getElementById('profileForm');
    const button = document.getElementById('saveButton');

    form.addEventListener('submit', function(){

        button.disabled = true;

        button.innerHTML =
        `
            <i class="fa-solid fa-spinner fa-spin mr-2"></i>
            Menyimpan...
        `;

    });

</script>

@if(session('success'))

<script>

Swal.fire({

    icon:'success',

    title:'Berhasil',

    text:'{{ session("success") }}',

    confirmButtonColor:'#545523',

    confirmButtonText:'OK'

});

</script>

@endif

@if($errors->any())

<script>

Swal.fire({

    icon:'error',

    title:'Oops...',

    html:`
        @foreach($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
    `,

    confirmButtonColor:'#545523'

});

</script>

@endif