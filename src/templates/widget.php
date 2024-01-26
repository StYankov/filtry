<?php
    /**
     * @var string $title
     * @var bool $mobileFilters
     */
?>
<div class="filtry<?php echo ( $mobileFilters ? ' filtry--mobile' : '' ); ?>">
    <div class="filtry__head">
        <?php if( ! empty( $title ) ) : ?>
            <h4><?php echo esc_html( $title ); ?></h4>
        <?php endif; ?>

        <button type="button" class="filtry__popup-close" title="<?php _e( 'Close filters', 'filtry' ); ?>">
        </button>
    </div>

    <?php \Filtry\Utils\View::render_filters(); ?>

    <?php do_action( 'filtry_widget_footer' ); ?>
</div>