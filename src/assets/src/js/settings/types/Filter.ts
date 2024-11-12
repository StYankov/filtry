type Filter = {
    enabled: boolean,
    id: string,
    slug: string,
    order: number,
    label: string,
    type: 'taxonomy' | 'price',
    view: 'checkbox' | 'radio' | 'select' | 'links',
    collapsable: boolean,
    logic: 'and' | 'or',
    sort: 'count' | 'title' | 'id',
    sortOrder: 'asc' | 'desc'
};

export default Filter;