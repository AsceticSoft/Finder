<?php

declare(strict_types=1);

namespace AsceticSoft\Finder;

interface ClassExtractorInterface
{
    public function findClassName(string $filename): ?string;
}
