<?php

namespace App\Enums;

enum ResponseStatus: string
{
    case SUCCESS = 'success';
    case FAILURE = 'failure';
}