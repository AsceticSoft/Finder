<?php

declare(strict_types=1);

namespace AsceticSoft\Finder\Exception;

class FileReadException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(string $fileName)
    {
        parent::__construct(\sprintf('Could not read file: %s', $fileName));
    }
}
