<?php

namespace App\Enums;

enum LeadStatus: string
{
    case New = 'new';
    case Read = 'read';
    case Contacted = 'contacted';
}
