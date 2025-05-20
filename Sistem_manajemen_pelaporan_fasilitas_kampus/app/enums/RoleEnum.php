<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case TEKNISI = 'teknisi';
    case PELAPOR = 'pelapor';
    case SARPRAS = 'sarpras';
    case SUPER_ADMIN = 'superadmin';
}