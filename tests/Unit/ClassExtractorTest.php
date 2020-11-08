<?php

declare(strict_types=1);

namespace AsceticSoft\Finder\Tests\Unit;

use AsceticSoft\Finder\ClassExtractor;
use AsceticSoft\Finder\Exception\ParseException;
use AsceticSoft\Finder\Tests\Samples\Bar;
use AsceticSoft\Finder\Tests\Samples\Foo;
use PHPUnit\Framework\TestCase;

class ClassExtractorTest extends TestCase
{
    public function testExtractor(): void
    {
        $extractor = new ClassExtractor();

        $this->assertEquals(Foo::class, $extractor->__invoke(__DIR__.'/../Samples/Foo.php'));
        $this->assertEquals(Bar::class, $extractor->__invoke(__DIR__.'/../Samples/Bar.php'));
        $this->assertNull($extractor->__invoke(__DIR__.'/../Samples/AbstractClass.php'));
        $this->assertNull($extractor->__invoke(__DIR__.'/../Samples/html.php'));
    }

    public function testParseError(): void
    {
        $extractor = new ClassExtractor();
        $this->expectException(ParseException::class);
        $extractor->__invoke(__DIR__.'/../Samples/InvalidClass.php');
    }
}
