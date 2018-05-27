<?php
namespace Immutability;


use DeepCopy\DeepCopy;
use DeepCopy\Filter\ReplaceFilter;
use DeepCopy\Matcher\PropertyTypeMatcher;

class Copier
{
    /** @var DeepCopy */
    private $deepCopy;

    public function __construct()
    {
        $this->deepCopy = new DeepCopy(true);

        $this->deepCopy->addFilter(
            new ReplaceFilter(function (ImmutableObject $value) {
                return $value;
            }), new PropertyTypeMatcher(ImmutableObject::class)
        );

        $this->deepCopy->addFilter(
            new ReplaceFilter(function ($value) {
                return new ImmutableObject($value);
            }), new PropertyTypeMatcher('stdClass')
        );
    }

    public function copy($data) {
        return $this->deepCopy->copy($data);
    }
}