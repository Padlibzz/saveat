<?php

namespace App\Enums;

enum ListingStatus: string
{
    case AKTIF = 'aktif';
    case HAMPIR_HABIS = 'hampir_habis';
    case TUTUP = 'tutup';
}
