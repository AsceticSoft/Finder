<?php

declare(strict_types=1);

namespace AsceticSoft\Finder;

class FileFinder implements FileFinderInterface
{
    /**
     * @var string[]
     */
    private array $paths;

    private int $flags = \FilesystemIterator::FOLLOW_SYMLINKS | \FilesystemIterator::CURRENT_AS_FILEINFO;

    /**
     * @param string[] $paths
     */
    public function __construct(array $paths = [])
    {
        $this->paths = $paths;
    }

    public function addPath(string $path): static
    {
        $this->paths[] = $path;

        return $this;
    }

    /**
     * @return \Traversable<int, string>
     */
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
