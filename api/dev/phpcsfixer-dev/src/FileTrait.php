<?php

declare(strict_types=1);

namespace Dev\PhpCsFixer\App;

use SplFileInfo;

trait FileTrait
{
    protected function isNotPhp(SplFileInfo $file): bool
    {
        return !$this->isPhp($file);
    }

    protected function isPhp(SplFileInfo $file): bool
    {
        return 'php' === $file->getExtension();
    }

    protected function isNotInterface(SplFileInfo $file): bool
    {
        return !$this->isInterface($file);
    }

    protected function isInterface(SplFileInfo $file): bool
    {
        return true === str_ends_with($file->getFilename(), 'Interface');
    }

    protected function isNotTest(SplFileInfo $file): bool
    {
        return !$this->isTest($file);
    }

    protected function isTest(SplFileInfo $file): bool
    {
        return true === str_ends_with($file->getBasename('.php'), 'Test');
    }
}
