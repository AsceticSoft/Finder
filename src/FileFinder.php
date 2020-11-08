<?php

declare(strict_types=1);

namespace AsceticSoft\Finder;

class FileFinder implements FinderInterface
{
    /**
     * @var string[]
     */
    private array $paths;

    private int $flags = \FilesystemIterator::FOLLOW_SYMLINKS | \FilesystemIterator::CURRENT_AS_FILEINFO;

    public function __construct(array $paths = [])
    {
        $this->paths = $paths;
    }

    public function addPath(string $path): FinderInterface
    {
        $this->paths[] = $path;

        return $this;
    }

    public function getIterator(): \Traversable
    {
        foreach ($this->paths as $path) {
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, $this->flags));

            /** @var \SplFileInfo $file */
            foreach ($iterator as $file) {
                if ($file->isDir()) {
                    continue;
                }
                if (false !== $file->getRealPath()) {
                    yield $file->getRealPath();
                }
            }
        }
    }
}
