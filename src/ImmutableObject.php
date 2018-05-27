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
    /** @var Copier */
    private static $copier;

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
            $this->data = $this->copier()->copy($this->data($data));
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
        $from = $this->data($this->data);
        $to = $this->data($data);

        if (is_null($from)) {
            return new ImmutableObject($to);
        } else {
            return new ImmutableObject((object)array_replace((array)$from, (array)$to));
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

    protected function data($data) {
        return($data instanceof ImmutableObject) ? $data->data : $data;
    }
}