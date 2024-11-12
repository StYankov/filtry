import { useEffect, useState } from 'react';
import {
  DndContext, 
  closestCenter,
  KeyboardSensor,
  PointerSensor,
  useSensor,
  useSensors,
} from '@dnd-kit/core';
import {
  arrayMove,
  SortableContext,
  sortableKeyboardCoordinates,
  verticalListSortingStrategy,
} from '@dnd-kit/sortable';
import useSettingsStore from '../../store/settingsStore';
import Filter from '../Filters/Filter';
import FilterType from '../../types/Filter';

export default function Filters() {
    const [filters, setFilters] = useSettingsStore(state => [state.filters, state.setFilters]);
    const [items, setItems] = useState(Object.values(filters));

    const sensors = useSensors(
      useSensor(PointerSensor),
      useSensor(KeyboardSensor, {
        coordinateGetter: sortableKeyboardCoordinates,
      })
    );

    function handleDragEnd(event) {
      const { active, over } = event;

      if(!over) {
        return;
      }
      
      if (active.id !== over.id) {
        setItems((items) => {
          const oldIndex = items.findIndex(filter => filter.id === active.id);
          const newIndex = items.findIndex(filter => filter.id === over.id);
      
          const newItems = arrayMove(items, oldIndex, newIndex);

          updateFilters(newItems);

          return newItems;
        });
      }
    }

    function updateFilters(items: FilterType[]) {
      const obj = {};
      let order = 0;

      for(const item of items) {
        obj[item.id] = item;
        obj[item.id].order = order++;
      }

      setFilters(obj);
    }

    useEffect(() => {
      setItems(Object.values(filters));
    }, Object.values(filters));

    return (
      <>
        <DndContext 
          sensors={sensors}
          collisionDetection={closestCenter}
          onDragEnd={handleDragEnd}
        >
          <SortableContext 
            items={ items }
            strategy={verticalListSortingStrategy}
          >
            { items.map(filter => <Filter key={ filter.id } id={ filter.id } filter={ filter } />) }
          </SortableContext>
        </DndContext>
      </>
    );
}