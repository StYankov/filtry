<?php
/**
 * @var \Filtry\Dto\FiltersCollection $filters
 * @var \Filtry\Dto\FiltersCollection $active_filters
 */
use Filtry\Enums\ViewEnum;
use Filtry\Utils\Template;
?>
<div class="filtry__filters">
    <?php do_action( 'filtry_pre_filters', $filters ); ?>

    <?php foreach( $filters as $filter ) : ?>
        <?php
            if( false === $filter->enabled ) { continue; }

            $classes = ['filtry__filter', 'filtry__filter-' . $filter->id, 'filtry__filter--' . $filter->view->value];

            if( $filter->collapsable ) {
                $classes[] = 'filtry__filter--collapsable';
            }
        ?>

        <div class="<?php echo implode( ' ', $classes ); ?>">
            <div class="filtry__filter-head">
                <h4 class="filtry__filter-title"><?php echo $filter->label; ?></h4>

                <?php if( $filter->collapsable ) : ?>
                    <button type="button" class="filtry__filter-collapse">
                        <span class="chevron"></span>
                    </button>
                <?php endif; ?>
            </div>
            
            <?php if( in_array( $filter->view, [ViewEnum::CHECKBOX, ViewEnum::RADIO] ) ) : ?>
                <?php Template::render( 'filters/toggleable.php', ['filter' => $filter, 'type' => $filter->view->value] ); ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
        
    <?php do_action( 'filtry_after_filters', $filters ); ?>
</div>