<?php

namespace TyHand\JsonApiToolsBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Filter extends Annotation
{
    /**
     * @var string
     */
    public $name;

    /**
     * Get the name
     * @return string Name
     */
    public function getName()
    {
        return $this->name;
    }
}
