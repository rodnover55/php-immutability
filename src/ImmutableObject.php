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

    /** @var object */
    private $data;

    /**
     * ImmutableObject constructor.
     *
     * @param object $data
     *
     * @throws CannotModifyException
     */
    public function __construct($data = null) {
        if ($this->init) {
            $this->prohibitChange();
        }

        if (isset($data)) {
            $this->data = clone $data;
        }

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