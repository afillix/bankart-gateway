<?php

namespace App\Payments\src\Transaction\Base;
use App\Payments\src\Data\Item;

/**
 * Interface ItemsInterface
 *
 * @package App\Payments\src\Transaction\Base
 */
interface ItemsInterface {

    /**
     * @param Item[] $items
     * @return void
     */
    public function setItems($items);

    /**
     * @return Item[]
     */
    public function getItems();

    /**
     * @param Item $item
     * @return void
     */
    public function addItem($item);

}
