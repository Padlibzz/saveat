@if(auth()->check())
    @if(auth()->user()->peran === 'admin')
        <x-sidebar-admin />
    @elseif(auth()->user()->peran === 'merchant')
        <x-sidebar-merchant />
    @else
        <x-sidebar-customer />
    @endif
@else
    <x-sidebar-customer />
@endif
