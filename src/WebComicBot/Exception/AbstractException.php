<?php
namespace WebComicBot\Exception;

use Exception;
use WebComicBot\WebComic\WebComicInterface;

class AbstractException extends \Exception
{
    /**
     * @var WebComicInterface
     */
    protected $webComic;

    /**
     * AbstractException constructor.
     * @param string $message
     * @param WebComicInterface $webComic
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(WebComicInterface $webComic, $message, $code = 0, Exception $previous = null)
    {
        $this->webComic = $webComic;
        parent::__construct($message, $code, $previous);
    }
}
