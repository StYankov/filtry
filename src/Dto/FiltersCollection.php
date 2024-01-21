<?php

namespace Filtry\Dto;

use ArrayObject;
use Filtry\Filtry;
use InvalidArgumentException;
use Filtry\Dto\Filter;
use Filtry\Enums\LogicEnum;
use Filtry\Enums\OrderEnum;
use Filtry\Enums\SortEnum;
use Filtry\Enums\TypeEnum;
use Filtry\Enums\ViewEnum;
use Iterator;

class FiltersCollection extends ArrayObject {
    /**
     * @param Filter[] $items
     */
    public function __construct( array $items = [] ) {
        foreach( $items as $item ) {
            $this->append( $item );
        }
    }

    public static function fromArray( array $items ): self {
        $collection = new self();
        $required_fields = ['id', 'label', 'type', 'view', 'order'];

        foreach( $items as $item ) {
            if( false === is_array( $item ) ) {
                continue;
            }

            /**
             * Check if all required fields are inside the array
             */
            if( count( array_diff( $required_fields, array_keys( $item ) ) ) > 0 ) {
                continue;
            }

            $filter = new Filter(
                enabled: isset( $item['enabled'] ) ? boolval( $item['enabled'] ) : false,
                id: $item['id'],
                slug: isset( $item['slug'] ) ? $item['slug'] : $item['id'],
                order: absint( $item['order'] ),
                label: $item['label'],
                type: TypeEnum::tryFrom( $item['type'] ),
                view: ViewEnum::tryFrom( $item['view'] ),
                logic: isset( $item['logic'] ) ? LogicEnum::tryFrom( $item['logic'] ) : LogicEnum::OR,
                sort: isset( $item['sort'] ) ? SortEnum::tryFrom( $item['sort'] ) : SortEnum::COUNT,
                sortOrder: isset( $item['sortOrder'] ) ? OrderEnum::tryFrom( $item['sortOrder'] ) : OrderEnum::DESC
            );

            $collection[ $filter->id ] = $filter;
        }

        return $collection;
    }

    /**
     * @param Filter $filter
     */
    public function append( mixed $filter ): void {
        $this->validate( $filter );

        parent::append( $filter );
    }

    /**
     * @param string $key
     * @param Filter $value
     * 
     * @return void
     */
    public function offsetSet( mixed $key, mixed $value ): void {
        $this->validate( $value );

        parent::offsetSet( $key, $value );
    }

    /**
     * @param string|int $key
     * 
     * @return Filter|null
     */
    public function offsetGet( mixed $key ): mixed {
        return parent::offsetGet( $key );
    }

    /**
     * @return Filter[]
     */
    public function getIterator(): Iterator {
        return parent::getIterator();
    }

    private function validate( mixed $value ): void {
        if( ! $value instanceof Filter ) {
            throw new InvalidArgumentException( __( 'Not an instance of a filter', Filtry::get_text_domain() ) );
        }
    }
}