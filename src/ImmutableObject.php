<?php
/**
 * Created by PhpStorm.
 * User: rnr
 * Date: 25.05.18
 * Time: 8:21
 */

namespace Immutability;


use Immutability\Exceptions\CannotModifyException;

final class ImmutableObject
{
    private $init = false;

    /** @var mixed */
    private $data;

    public function __construct($data) {
        if ($this->init) {
            $this->prohibitChange();
        }

        $this->data = clone $data;

        $this->init = true;
    }

    public function __get($name)
    {
        return $this->data->{$name};
    }

    public function __set($name, $value)
    {
        $this->prohibitChange();
    }

    public function __unset($name)
    {
        $this->prohibitChange();
    }


    public function with($data) {
        return new ImmutableObject((object)array_replace((array)$this->data, (array)$data));
    }

    private function prohibitChange() {
        throw new CannotModifyException('Cannot change immutable object');
    }
}