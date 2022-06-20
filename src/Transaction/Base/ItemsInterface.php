<?php

namespace Afillix\BankartGateway\Transaction\Base;
use Afillix\BankartGateway\Data\Item;

/**
 * Interface ItemsInterface
 *
 * @package Afillix\BankartGateway\Transaction\Base
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
