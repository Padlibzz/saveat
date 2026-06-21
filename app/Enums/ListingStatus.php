<?php

namespace App\Enums;

enum ListingStatus: string
{
    case AKTIF = 'aktif';
    case HAMPIR_HABIS = 'hampir_habis';
    case TUTUP = 'tutup';
    case DIARSIPKAN = 'diarsipkan';
    case DITOLAK = 'ditolak';
    case DIHAPUS = 'dihapus';
}