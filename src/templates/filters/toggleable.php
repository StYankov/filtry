<?php
    /**
     * @var \Filtry\Dto\Filter $filter
     * @var string $type
     */

use Filtry\Utils\Template;

?>
<div class="filtry__list">
    <?php foreach( $filter->terms as $term ) : ?>
        <?php if( $term->count === 0 ) { continue; } ?>
        <label class="filtry-toggleable">
            <?php
                Template::render( "fields/{$type}.php", [
                    'name'   => $term->name,
                    'value'  => $term->slug,
                    'filter' => $filter,
                    'term'   => $term
                ] );
            ?>

            <?php echo $term->name; ?>

            <?php if( ! $term->is_selected ) : ?>
                <span class="filtry-count"><?php echo $term->count; ?></span>
            <?php endif; ?>
        </label>
    <?php endforeach; ?>
</div>