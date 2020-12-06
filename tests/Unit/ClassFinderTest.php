<?php

declare(strict_types=1);

namespace AsceticSoft\Finder\Tests\Unit;

use AsceticSoft\Finder\ClassFinder;
use PHPUnit\Framework\TestCase;

class ClassFinderTest extends TestCase
{
    public function testClassFinder(): void
    {
        $classFinder = new ClassFinder();
        $classFinder->addPath(__DIR__ . '/../Samples');

        $this->assertEquals(2, iterator_count($classFinder));
    }
}
