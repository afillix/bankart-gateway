<?php


namespace Afillix\BankartGateway\Data\Result;

/**
 * Class ResultData
 *
 * @package Afillix\BankartGateway\Data\Result
 */
abstract class ResultData {

    /**
     * @return array
     */
    abstract public function toArray();

}
