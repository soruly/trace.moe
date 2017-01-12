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

use Symfony\Component\Filesystem\Exception\IOException;

interface TemporaryFilesystemInterface
{
    /**
     * Creates a temporary directory.
     *
     * @param octal   $mode   The directory mode
     * @param integer $maxTry The maximum number of trials
     * @param string  $prefix The directory prefix
     *
     * @return string The name of the created directory
     *
     * @throws IOException In case the directory could not be created
     */
    public function createTemporaryDirectory($mode = 0777, $maxTry = 65536, $prefix = null);

    /**
     * Creates an array of temporary files.
     *
     * Temporary files are created inside the system temporary folder. You must
     * removed them manually at the end of use.
     *
     * @param integer $quantity  The quantity of temporary files requested
     * @param string  $prefix    The prefix of the files
     * @param string  $suffix    The suffix of the files
     * @param string  $extension The extension of the files
     * @param integer $maxTry    The maximum number of trials to create one temporary file
     *
     * @return array An array of filenames
     *
     * @throws \InvalidArgumentException In case you provide a wrong argument
     * @throws IOException               In case of failure
     */
    public function createTemporaryFiles($quantity = 1, $prefix = null, $suffix = null, $extension = null, $maxTry = 65536);

    /**
     * Creates a temporary file.
     *
     * Temporary files are created inside the system temporary folder. You must
     * removed them manually at the end of use.
     *
     * @param string  $prefix    The prefix of the files
     * @param string  $suffix    The suffix of the files
     * @param string  $extension The extension of the files
     * @param integer $maxTry    The maximum number of trials to create one temporary file
     *
     * @return array An array of filenames
     *
     * @throws \InvalidArgumentException In case you provide a wrong argument
     * @throws IOException               In case of failure
     */
    public function createTemporaryFile($prefix = null, $suffix = null, $extension = null, $maxTry = 65536);

    /**
     * Create an empty file in the specified directory.
     *
     * The new file is created in the requested directory and will fit the
     * the given parameters. Please note that the filename contains some
     * random caracters.
     *
     * @param string  $basePath  The directory where to create the file
     * @param string  $prefix    The prefix of the file
     * @param string  $suffix    The suffix of the file
     * @param string  $extension The extension of the file
     * @param integer $maxTry    The maximum number of trials to create the file
     *
     * @return string The path of the created file
     *
     * @throws IOException in case of failure
     */
    public function createEmptyFile($basePath, $prefix = null, $suffix = null, $extension = null, $maxTry = 65536);
}
