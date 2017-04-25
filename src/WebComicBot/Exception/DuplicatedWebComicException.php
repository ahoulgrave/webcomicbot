<?php
namespace WebComicBot\Exception;

use WebComicBot\WebComic\WebComicInterface;

class DuplicatedWebComicException extends AbstractException
{
    /**
     * @inheritdoc
     */
    public function __construct(WebComicInterface $webComic, $message = null, $code = 0, $previous = null)
    {
        parent::__construct($webComic, $message, $code, $previous);
        $this->message = sprintf(
            'There is already a comic registered with the tag %s',
            $this->webComic->getTag()
        );
    }
}
