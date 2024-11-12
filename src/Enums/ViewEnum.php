<?php

namespace Filtry\Enums;

enum ViewEnum: string {
    case RADIO    = 'radio';
    case CHECKBOX = 'checkbox';
    case SELECT   = 'select';
    case LINKS    = 'links';
}