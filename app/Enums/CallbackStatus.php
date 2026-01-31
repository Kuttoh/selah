<?php

namespace App\Enums;

enum CallbackStatus: string
{
    case Pending = 'pending';
    case Called = 'called';
    case NoAnswer = 'no_answer';
    case FollowUp = 'follow_up';
    case Completed = 'completed';
    case Closed = 'closed';
}
