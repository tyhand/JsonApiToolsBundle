<?php

namespace TyHand\JsonApiToolsBundle\ApiResource;

class Formatter
{
    /**
     * Convert php value to json output
     * @param  mixed $value Value to convert
     * @return mixed        Converted value
     */
    public function toJson($value)
    {
        return $value;
    }

    /**
     * Convert json input value to php value
     * @param  mixed $value Value to convert
     * @return mixed        Converted value
     */
    public function toEntity($value)
    {
        return $value;
    }

    /**
     * Get the unique name of the formatter
     * @return string Formatter name
     */
    public function getName()
    {
        return 'default';
    }
}
