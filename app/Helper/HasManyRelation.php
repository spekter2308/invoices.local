<?php

namespace App\Helper;

trait HasManyRelation {

    public function storeHasMany($relations)
    {
        $this->save();
        foreach($relations as $key => $items) {
            $newItems = [];
            foreach($items as $item) {
                $model = $this->{$key}()->getModel();
                $newItems[] = $model->fill($item);
            }
            // save
            $this->{$key}()->saveMany($newItems);
        }
    }

}