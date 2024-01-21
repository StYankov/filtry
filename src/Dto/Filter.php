<?php

namespace Filtry\Dto;

use Filtry\Enums\LogicEnum;
use Filtry\Enums\OrderEnum;
use Filtry\Enums\SortEnum;
use Filtry\Enums\TypeEnum;
use Filtry\Enums\ViewEnum;
use OutOfRangeException;
use Traversable;

class Filter implements \JsonSerializable, \IteratorAggregate, \ArrayAccess {
    /**
     * @param bool $enabled If the filter is enabled for showing in the frontend
     * @param string $id The taxonomy slug for taxonomy filters or 'price' for the price filter
     * @param string $slug The rewrite slug (if any) for the taxonomy or 'price' for the price filter
     * @param int $order The order of appearance of the filter in the frontend
     * @param string $label The name of the filter in the frontend. Defaults to taxonomy name
     * @param TypeEnum $type The type of the filter 'taxonomy' or 'price'
     * @param ViewEnum $view The view used to display the filter. 
     * @param LogicEnum $logic Determines how multiple terms of the same filter will be combined when searching for products
     * @param SortEnum $sort Which property should be used to sort the terms in the filter box
     * @param OrderEnum $sortOrder The order in which the terms should be sorted in the filter box (ASC or DESC)
     * @param bool $collapsable Whether the filter box can be collapsed or not. In collapsed state only the title is visible
     * @param array $active_terms List of term slugs that are active for the current query. (The active filters)
     * @param TermCollection|null $terms The terms in the current filter 
     */
    public function __construct(
        public bool $enabled,
        public string $id,
        public string $slug,
        public int $order,
        public string $label,
        public TypeEnum $type,
        public ViewEnum $view,
        public LogicEnum $logic = LogicEnum::OR,
        public SortEnum $sort = SortEnum::COUNT,
        public OrderEnum $sortOrder = OrderEnum::DESC,
        public bool $collapsable = true,
        public array $active_terms = [],
        public ?TermCollection $terms = null,
    ) { }

    public function jsonSerialize(): mixed {
        return [
            'enabled'     => $this->enabled,
            'id'          => $this->id,
            'slug'        => $this->slug,
            'order'       => $this->order,
            'label'       => $this->label,
            'type'        => $this->type->value,
            'view'        => $this->view->value,
            'collapsable' => $this->collapsable,
            'logic'       => $this->logic->value,
            'sort'        => $this->sort->value,
            'sortOrder'   => $this->sortOrder->value
        ];
    }

    public function getIterator(): Traversable {
        return $this->jsonSerialize();
    }

    public function offsetExists( mixed $offset ): bool {
        return property_exists( $this, $offset );
    }

    public function offsetGet( mixed $offset ): mixed {
        if( property_exists( $this, $offset ) ) {
            return $this->{ $offset };
        }

        throw new OutOfRangeException();
    }

    public function offsetSet( mixed $offset, mixed $value ): void {
        if( false === property_exists( $this, $offset ) ) {
            throw new OutOfRangeException();
        }

        $this->{ $offset } = $value;
    }

    public function offsetUnset( mixed $offset ): void {
        if( property_exists( $this, $offset ) ) {
            $this->{ $offset } = null;
        }
    }
}