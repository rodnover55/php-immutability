<?php
/**
 * Created by PhpStorm.
 * User: rnr
 * Date: 25.05.18
 * Time: 8:21
 */

namespace Immutability;


use Immutability\Exceptions\CannotModifyException;
use ArrayAccess;

final class ImmutableObject implements ArrayAccess
{
    /** @var Copier */
    private static $copier;

    private $init = false;

    /** @var object */
    private $data;

    /**
     * ImmutableObject constructor.
     *
     * @param mixed $data
     *
     * @throws CannotModifyException
     */
    public function __construct($data = null) {
        if ($this->init) {
            $this->prohibitChange();
        }

        if (isset($data)) {
            $this->data = $this->copier()->copy($this->data($data));
        }

        $this->init = true;
    }

    public function __get($name)
    {
        // TODO: Кидать исключение если в данных не объект
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
        $from = $this->data($this->data);
        $to = $this->data($data);

        if (is_null($from)) {
            return new ImmutableObject($to);
        } elseif (is_array($from) && is_array($to)) {
            return new ImmutableObject(array_replace($from, $to));
        } elseif (is_object($from) && is_object($to)) {
            return new ImmutableObject((object)array_replace((array)$from, (array)$to));
        } else {
            // TODO: Разобраться с коллизией объектов и массивов. Кидать исключение о несовместимости типов
        }
    }

    private function prohibitChange() {
        throw new CannotModifyException('Cannot change immutable object');
    }

    protected function copier(): Copier {
        if (is_null(self::$copier)) {
            self::$copier = new Copier();
        }

        return self::$copier;
    }

    public function offsetExists($offset)
    {
        // TODO: Кидать исключение, если в данных не массив
        return isset($this->data->{$offset});
    }

    public function offsetGet($offset)
    {
        // TODO: Кидать исключение, если в данных не массив
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        // TODO: Кидать исключение о несовместимости форматов, если в данных не массив
        $this->prohibitChange();
    }

    public function offsetUnset($offset)
    {
        $this->prohibitChange();
    }

    protected function data($data) {
        return($data instanceof ImmutableObject) ? $data->data : $data;
    }
}