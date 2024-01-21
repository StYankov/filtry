<?php
    /**
     * @var \Filtry\Dto\FiltersCollection $filters
     * @var \Filtry\Dto\FiltersCollection $active_filters
     * @var bool $autosubmit
     */

use Filtry\Enums\ViewEnum;
use Filtry\Utils\Template;

?>
<div class="filtry">
    <?php if( $active_filters->count() > 0 ) : ?>
        <button type="button" class="button filtry-reset"><?php _e( 'Reset', 'filtry' ); ?></button>
    <?php endif; ?>
    <?php foreach( $filters as $filter ) : ?>
        <?php
            $classes = ['filtry__container', 'filtry-' . $filter->id, 'filtry--' . $filter->view->value];

            if( $filter->collapsable ) {
                $classes[] = 'filtry--collapsable';
            }
        ?>

        <div class="<?php echo implode( ' ', $classes ); ?>">
            <div class="filtry__head">
                <h4 class="filtry__filter-title"><?php echo $filter->label; ?></h4>
                <?php if( $filter->collapsable ) : ?>
                    <button type="button" class="filtry-colapse">
                        <span class="chevron"></span>
                    </button>
                <?php endif; ?>
            </div>
            
            <?php if( in_array( $filter->view, [ViewEnum::CHECKBOX, ViewEnum::RADIO] ) ) : ?>
                <?php Template::render( 'filters/toggleable.php', ['filter' => $filter, 'type' => $filter->view->value] ); ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
        
    <?php if( $autosubmit === false ) : ?>
        <?php Template::render( 'submit-button.php' ) ?>
    <?php endif; ?>
</div>