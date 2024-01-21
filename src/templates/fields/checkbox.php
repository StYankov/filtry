<?php
    /**
     * @var \Filtry\Dto\Filter $filter
     * @var \WP_Term $term
     * @var string $name
     * @var string $value
     */
?>
<input 
    class="filtry-checkbox"
    type="checkbox" 
    name="<?php echo $name; ?>[]" 
    value="<?php echo $value ?>" 
    data-taxonomy="<?php echo $filter->slug; ?>"
    <?php checked( true, in_array( $value, $filter->active_terms ) ) ?>
/>