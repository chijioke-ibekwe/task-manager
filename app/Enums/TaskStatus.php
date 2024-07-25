<?php

namespace App\Enums;

enum TaskStatus: string
{
    case INCOMPLETE = 'incomplete';
    case COMPLETE = 'complete';
}