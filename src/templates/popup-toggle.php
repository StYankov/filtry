<?php

    use Filtry\Admin\Settings;
    use Filtry\Enums\DesignSettingsEnum;

    $position = Settings::get_option( DesignSettingsEnum::FLOATING_BUTTON_POSITION, 'none' );

    if( $position === 'none' ) {
        return;
    }
?>

<button type="button" class="filtry__popup-toggle filtry__popup-toggle--<?php echo $position; ?>">
    <img 
        src="<?php echo \Filtry\Filtry::plugin_url() . 'src/assets/images/filters.svg'; ?>" 
        alt="<?php __( 'Toggle filters', 'filtry' ); ?>"
    />
</button>