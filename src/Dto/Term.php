<?php

namespace Filtry\Dto;

class Term implements \JsonSerializable {
    public function __construct(
        public int $term_id,
        public string $taxonomy,
        public string $slug,
        public string $name,
        public int $count,
        public bool $is_selected = false
    ) { }

    public function jsonSerialize(): mixed {
        return [
            'term_id'  => $this->term_id,
            'taxonomy' => $this->taxonomy,
            'slug'     => $this->slug,
            'name'     => $this->name,
            'count'    => $this->count,
            'is_selected' => $this->is_selected
        ];
    }
}