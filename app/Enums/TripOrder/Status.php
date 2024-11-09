<?php

namespace App\Enums\TripOrder;

use ArchTech\Enums\Values;

enum Status: string
{
    use Values;

    case APPROVED = 'approved';
    case CANCELED = 'canceled';
    case REQUESTED = 'requested';
}
