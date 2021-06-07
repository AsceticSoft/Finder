<?php

declare(strict_types=1);

namespace AsceticSoft\Finder;

use AsceticSoft\Finder\Exception\ParseException;

class ClassFinder implements ClassFinderInterface
{
    private FileFinderInterface $fileFinder;
    private ClassExtractorInterface $classExtractor;

    public function __construct(
        ?FileFinderInterface $fileFinder = null,
        ?ClassExtractorInterface $classExtractor = null
    ) {
        $this->fileFinder = $fileFinder ?? new FileFinder();
        $this->classExtractor = $classExtractor ?? new ClassExtractor();
    }

    public function addPath(string $path): static
    {
        $this->fileFinder->addPath($path);

        return $this;
    }

    /**
     * @return \Traversable<int, string>
     */
    public function getIterator(): \Traversable
    {
        foreach ($this->fileFinder as $fileName) {
            if ('.php' === substr($fileName, -4)) {
                try {
                    $class = $this->classExtractor->findClassName($fileName);
                    if ($class) {
                        yield $class;
                    }
                } catch (ParseException) {
                }
            }
        }
    }
}
