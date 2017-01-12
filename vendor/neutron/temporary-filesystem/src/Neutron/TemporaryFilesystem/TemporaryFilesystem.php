<?php

/*
 * This file is part of TemporaryFilesystem.
 *
 * (c) Romain Neutron <imprec@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Neutron\TemporaryFilesystem;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException as SfIOException;

class TemporaryFilesystem implements TemporaryFilesystemInterface
{
    /** @var Filesystem */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function createTemporaryDirectory($mode = 0777, $maxTry = 65536, $prefix = null)
    {
        $basePath = sys_get_temp_dir();

        while ($maxTry > 0) {
            $dir = $basePath . DIRECTORY_SEPARATOR
                . $prefix . base_convert(mt_rand(0x19A100, 0x39AA3FF), 10, 36);

            if (false === file_exists($dir)) {
                try {
                    $this->filesystem->mkdir($dir, $mode);
                } catch (SfIOException $e) {
                    throw new IOException('Unable to make directory', $e->getCode(), $e);
                }

                return $dir;
            }

            $maxTry --;
        }

        throw new IOException('Unable to generate a temporary directory');
    }

    /**
     * {@inheritdoc}
     */
    public function createTemporaryFiles($quantity = 1, $prefix = null, $suffix = null, $extension = null, $maxTry = 65536)
    {
        if ($quantity < 1) {
            throw new \InvalidArgumentException('Invalid temporary files quantity');
        }

        $files = array();

        while ($quantity > 0) {
            $files[] = $this->createEmptyFile(sys_get_temp_dir(), $prefix, $suffix, $extension, $maxTry);
            $quantity --;
        }

        return $files;
    }

    /**
     * {@inheritdoc}
     */
    public function createTemporaryFile($prefix = null, $suffix = null, $extension = null, $maxTry = 65536)
    {
        $files = $this->createTemporaryFiles(1, $prefix, $suffix, $extension, $maxTry);

        return array_pop($files);
    }

    /**
     * {@inheritdoc}
     */
    public function createEmptyFile($basePath, $prefix = null, $suffix = null, $extension = null, $maxTry = 65536)
    {
        if (false === is_dir($basePath) || false === is_writeable($basePath)) {
            throw new IOException(sprintf('`%s` should be a writeable directory', $basePath));
        }

        if ($suffix === null && $extension === null) {
            if (false === $file = @tempnam($basePath, $prefix)) {
                throw new IOException('Unable to generate a temporary filename');
            }

            return $file;
        }

        while ($maxTry > 0) {
            $file = $basePath . DIRECTORY_SEPARATOR
                . $prefix . base_convert(mt_rand(0x19A100, 0x39AA3FF), 10, 36) . $suffix
                . ( $extension ? '.' . $extension : '');

            if (false === file_exists($file)) {
                try {
                    $this->filesystem->touch($file);
                } catch (SfIOException $e) {
                    throw new IOException('Unable to touch file', $e->getCode(), $e);
                }

                return $file;
            }

            $maxTry --;
        }

        throw new IOException('Unable to generate a temporary filename');
    }

    /**
     * Creates a TemporaryFilesystem
     *
     * @return TemporaryFilesystem
     */
    public static function create()
    {
        return new static(new Filesystem());
    }
}
