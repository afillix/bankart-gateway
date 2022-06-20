<?php


namespace App\Payments\src\Data\Result;

/**
 * Class ResultData
 *
 * @package App\Payments\src\Data\Result
 */
abstract class ResultData {

    /**
     * @return array
     */
    abstract public function toArray();

}
