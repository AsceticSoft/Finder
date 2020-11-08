<?php

declare(strict_types=1);

namespace AsceticSoft\Finder;

interface FinderInterface extends \IteratorAggregate
{
    public function addPath(string $path): self;
}
