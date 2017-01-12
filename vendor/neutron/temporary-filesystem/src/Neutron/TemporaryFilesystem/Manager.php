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

class Manager implements TemporaryFilesystemInterface
{
    /** @var Filesystem */
    private $fs;
    /** @var TemporaryFilesystem */
    private $tmpFs;
    /** @var array */
    private $files = array();

    const DEFAULT_SCOPE = '_tmp_fs_';

    public function __construct(TemporaryFilesystemInterface $tmpFs, Filesystem $fs)
    {
        $this->fs = $fs;
        $this->tmpFs = $tmpFs;

        register_shutdown_function(array($this, 'clean'), null, false);
    }

    /**
     * {@inheritdoc}
     */
    public function createEmptyFile($basePath, $prefix = self::DEFAULT_SCOPE, $suffix = null, $extension = null, $maxTry = 65536)
    {
        $file = $this->tmpFs->createEmptyFile($basePath, $prefix, $suffix, $extension, $maxTry);
        $this->add($file, $prefix);

        return $file;
    }

    /**
     * {@inheritdoc}
     */
    public function createTemporaryDirectory($mode = 0777, $maxTry = 65536, $prefix = self::DEFAULT_SCOPE)
    {
        $dir = $this->tmpFs->createTemporaryDirectory($mode, $maxTry, $prefix);
        $this->add($dir, $prefix);

        return $dir;
    }

    /**
     * {@inheritdoc}
     */
    public function createTemporaryFile($prefix = self::DEFAULT_SCOPE, $suffix = null, $extension = null, $maxTry = 65536)
    {
        $file = $this->tmpFs->createTemporaryFile($prefix, $suffix, $extension, $maxTry);
        $this->add($file, $prefix);

        return $file;
    }

    /**
     * {@inheritdoc}
     */
    public function createTemporaryFiles($quantity = 1, $prefix = self::DEFAULT_SCOPE, $suffix = null, $extension = null, $maxTry = 65536)
    {
        $files = $this->tmpFs->createTemporaryFiles($quantity, $prefix, $suffix, $extension, $maxTry);
        $this->add($files, $prefix);

        return $files;
    }

    /**
     * Adds file to be handled by the manager.
     *
     * @param string|array $files
     * @param string       $scope
     *
     * @return Manager
     */
    public function add($files, $scope = self::DEFAULT_SCOPE)
    {
        if (!is_array($files)) {
            $files = array($files);
        }
        if ('' === trim($scope)) {
            $scope = self::DEFAULT_SCOPE;
        }
        if (!isset($this->files[$scope])) {
            $this->files[$scope] = array();
        }

        $this->files[$scope] = array_merge($this->files[$scope], $files);

        return $this;
    }

    /**
     * Removes all managed files in a scope. If no scope provided, all scopes
     * are cleared.
     *
     * @param string       $scope
     *
     * @return Manager
     *
     * @throws IOException
     */
    public function clean($scope = null, $throwException = true)
    {
        if (null !== $scope) {
            $this->cleanScope($scope, $throwException);
        } else {
            foreach ($this->files as $scope => $files) {
                $this->cleanScope($scope, $throwException);
            }
        }

        return $this;
    }

    /**
     * Factory for the Manager
     *
     * @return Manager
     */
    public static function create()
    {
        $fs = new Filesystem();

        return new static(new TemporaryFilesystem($fs), $fs);
    }

    private function cleanScope($scope, $throwException)
    {
        if (!isset($this->files[$scope])) {
            return;
        }

        try {
            $this->fs->remove($this->files[$scope]);
            unset($this->files[$scope]);
        } catch (SfIOException $e) {
            unset($this->files[$scope]);
            if ($throwException) {
                throw new IOException('Unable to remove all the files', $e->getCode(), $e);
            }
        }
    }
}
