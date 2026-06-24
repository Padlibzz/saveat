@if(auth()->check())
    @if(auth()->user()->peran === 'admin')
        <x-navbar-admin />
    @elseif(auth()->user()->peran === 'merchant')
        <x-navbar-merchant />
    @else
        <x-navbar-customer />
    @endif
@else
    <x-navbar-customer />
@endif
