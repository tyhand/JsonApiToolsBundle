<?php

namespace TyHand\JsonApiToolsBundle\ApiResource;

class Error
{
    /**
     * Error title
     * @var string
     */
    private $title;

    /**
     * Error details
     * @var string
     */
    private $detail;

    /**
     * Error code
     * @var string
     */
    private $code;

    /**
     * Constructor
     * @param string $title  Title
     * @param string $detail Detail
     * @param mixed  $code   (Optional) Code
     */
    public function __construct($title, $detail = null, $code = null)
    {
        $this->title = $title;
        $this->detail = $detail;
        $this->code = $code;
    }

    /**
     * Return a json hash form of this error object
     * @return array Json hash
     */
    public function toJson()
    {
        return [
            'title' => $this->title,
            'detail' => $this->detail,
            'code' => $this->code
        ];
    }
}
