<?php

namespace TyHand\JsonApiToolsBundle\ApiResource;

class DateFormatter extends Formatter
{
    /**
     * Convert php value to json output
     * @param  mixed $value Value to convert
     * @return mixed        Converted value
     */
    public function toJson($value)
    {
        if ($value) {
            return $value->format('c');
        } else {
            return null;
        }
    }

    /**
     * Convert json input value to php value
     * @param  mixed $value Value to convert
     * @return mixed        Converted value
     */
    public function toEntity($value)
    {
        if ($value) {
            return new \DateTime($value);
        } else {
            return null;
        }
    }

    /**
     * Get the unique name of the formatter
     * @return string Formatter name
     */
    public function getName()
    {
        return 'datetime';
    }
}

