<?php

namespace Filtry\Enums;

enum DesignSettingsEnum: string {
    case DISABLE_STYLES  = 'disable_styles';
    case PRIMART_COLOR   = 'primary_color';
    case SECONDARY_COLOR = 'secondary_color';
    case ACCENT_COLOR    = 'accent_color';
    case FLOATING_BUTTON_POSITION = 'floating_button_position';
}