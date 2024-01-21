<?php
    /**
     * @var \Filtry\Dto\Filter $filter
     * @var \WP_Term $term
     * @var string $name
     * @var string $value
     */
?>
<input 
    class="filtry-radio"
    type="radio" 
    name="<?php echo $name; ?>[]" 
    value="<?php echo $value ?>" 
    data-taxonomy="<?php echo $filter->slug; ?>"
    <?php checked( true, in_array( $value, $filter->active_terms ) ) ?>
/>