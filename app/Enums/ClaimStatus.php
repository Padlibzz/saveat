<?php

namespace App\Enums;

enum ClaimStatus: string
{
    case PENDING = 'pending';
    case DIAMBIL = 'diambil';
    case BATAL = 'batal';
}
