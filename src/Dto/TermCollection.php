<?php

namespace Filtry\Dto;

use ArrayObject;
use Filtry\Filtry;
use InvalidArgumentException;
use Iterator;

class TermCollection extends ArrayObject {
    /**
     * @param Term[] $items
     */
    public function __construct( array $items = [] ) {
        foreach( $items as $item ) {
            $this->append( $item );
        }
    }

    /**
     * @param Term $item
     */
    public function append( mixed $item ): void {
        $this->validate( $item );

        parent::append( $item );
    }

    /**
     * @param string $key
     * @param Term $value
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
     * @return Term|null
     */
    public function offsetGet( mixed $key ): mixed {
        return parent::offsetGet( $key );
    }

    /**
     * @return Term[]
     */
    public function getIterator(): Iterator {
        return parent::getIterator();
    }

    private function validate( mixed $value ): void {
        if( ! $value instanceof Term ) {
            throw new InvalidArgumentException( __( 'Not an instance of a Term', 'filtry' ) );
        }
    }
}