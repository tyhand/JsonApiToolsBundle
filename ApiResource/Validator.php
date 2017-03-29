<?php

namespace TyHand\JsonApiToolsBundle\ApiResource;

class Validator
{
    /**
     * Validator method name
     * @var string
     */
    private $method;

    /**
     * Error title
     * @var string
     */
    private $errorTitle;

    /**
     * Error detail
     * @var string
     */
    private $errorDetail;

    /**
     * Error code
     * @var string
     */
    private $errorCode;

    /**
     * Constructor
     * @param string $method      Validator method name
     * @param string $errorTitle  Error Title
     * @param string $errorDetail Error Detail
     * @param string $errorCode   Error code
     */
    public function __construct($method, $errorTitle, $errorDetail = null, $errorCode = null)
    {
        $this->method = $method;
        $this->errorTitle = $errorTitle;
        $this->errorDetail = $errorDetail;
    }

    /**
     * Get the value of Validator method name
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set the value of Validator method name
     * @param string method
     * @return self
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Get the value of Error title
     * @return string
     */
    public function getErrorTitle()
    {
        return $this->errorTitle;
    }

    /**
     * Set the value of Error title
     * @param string errorTitle
     * @return self
     */
    public function setErrorTitle($errorTitle)
    {
        $this->errorTitle = $errorTitle;
        return $this;
    }

    /**
     * Get the value of Error detail
     * @return string
     */
    public function getErrorDetail()
    {
        return $this->errorDetail;
    }

    /**
     * Set the value of Error detail
     * @param string errorDetail
     * @return self
     */
    public function setErrorDetail($errorDetail)
    {
        $this->errorDetail = $errorDetail;
        return $this;
    }

    /**
     * Get the value of Error code
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Set the value of Error code
     * @param string errorCode
     * @return self
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
        return $this;
    }

}
