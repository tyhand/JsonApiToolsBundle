<?php

namespace TyHand\JsonApiToolsBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Validator extends Annotation
{
    /**
     * @var string
     *
     * @Required
     */
    public $errorTitle;

    /**
     * @var string
     */
    public $errorDetail;

    /**
     * @var string
     */
    public $errorCode;

    /**
     * Get the value of Error Title
     *
     * @return string
     */
    public function getErrorTitle()
    {
        return $this->errorTitle;
    }

    /**
     * Get the value of Error Detail
     *
     * @return string
     */
    public function getErrorDetail()
    {
        return $this->errorDetail;
    }

    /**
     * Get the value of Error Code
     *
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

}
