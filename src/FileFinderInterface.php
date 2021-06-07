<?php

declare(strict_types=1);

namespace AsceticSoft\Finder;

interface FileFinderInterface extends \IteratorAggregate
{
    public function addPath(string $path): static;
}
