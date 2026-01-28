<?php

namespace App\Enums;

enum PrayerStatus: string
{
    case Received = 'received';
    case Prayed = 'prayed';
    case Answered = 'answered';
}
