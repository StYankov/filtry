<?php
/**
 * Load more button
 *
 * The button is a real link to the next /page/{n}/ archive so that crawlers and
 * visitors without JavaScript can reach every product. The script intercepts
 * the click and appends the next page instead.
 *
 * @var int|null    $next_page
 * @var string|null $next_url
 * @var string|null $prev_url
 */

defined( 'ABSPATH' ) || exit;

$next_page = $next_page ?? null;
$next_url  = $next_url ?? null;
$prev_url  = $prev_url ?? null;
?>
<?php if( $prev_url ) : ?>
    <div class="filtry-prev-page">
        <a href="<?php echo esc_url( $prev_url ); ?>"><?php esc_html_e( 'Previous page', 'filtry' ); ?></a>
    </div>
<?php endif; ?>
<?php if( $next_url ) : ?>
    <div class="filtry-infinity-load">
        <a class="button filtry-load-more" href="<?php echo esc_url( $next_url ); ?>" data-next-page="<?php echo esc_attr( $next_page ); ?>"><?php esc_html_e( 'Show more', 'filtry' ); ?></a>
    </div>
<?php endif; ?>
