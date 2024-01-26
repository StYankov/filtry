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
        <label class="filtry__toggleable">
            <?php
                Template::render( "fields/{$type}.php", [
                    'name'   => $term->name,
                    'value'  => $term->slug,
                    'filter' => $filter,
                    'term'   => $term
                ] );
            ?>
            <span class="filter__term-name">
                <?php echo $term->name; ?>
            </span>

            <?php if( ! $term->is_selected ) : ?>
                <span class="filtry__term-count"><?php echo $term->count; ?></span>
            <?php endif; ?>
        </label>
    <?php endforeach; ?>
</div>