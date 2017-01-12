<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Driver;

use Alchemy\BinaryDriver\AbstractBinary;
use Alchemy\BinaryDriver\Configuration;
use Alchemy\BinaryDriver\ConfigurationInterface;
use Alchemy\BinaryDriver\Exception\ExecutableNotFoundException as BinaryDriverExecutableNotFound;
use FFMpeg\Exception\ExecutableNotFoundException;
use Psr\Log\LoggerInterface;

class FFMpegDriver extends AbstractBinary
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ffmpeg';
    }

    /**
     * Creates an FFMpegDriver.
     *
     * @param LoggerInterface     $logger
     * @param array|Configuration $configuration
     *
     * @return FFMpegDriver
     */
    public static function create(LoggerInterface $logger = null, $configuration = array())
    {
        if (!$configuration instanceof ConfigurationInterface) {
            $configuration = new Configuration($configuration);
        }

        $binaries = $configuration->get('ffmpeg.binaries', array('avconv', 'ffmpeg'));

        if (!$configuration->has('timeout')) {
            $configuration->set('timeout', 300);
        }

        try {
            return static::load($binaries, $logger, $configuration);
        } catch (BinaryDriverExecutableNotFound $e) {
            throw new ExecutableNotFoundException('Unable to load FFMpeg', $e->getCode(), $e);
        }
    }
}
