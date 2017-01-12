<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\FFProbe;

use Alchemy\BinaryDriver\Exception\ExecutionFailureException;
use Doctrine\Common\Cache\Cache;
use FFMpeg\Driver\FFProbeDriver;
use FFMpeg\Exception\RuntimeException;

class OptionsTester implements OptionsTesterInterface
{
    /** @var FFProbeDriver */
    private $ffprobe;
    /** @var Cache */
    private $cache;

    public function __construct(FFProbeDriver $ffprobe, Cache $cache)
    {
        $this->ffprobe = $ffprobe;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        $id = sprintf('option-%s', $name);

        if ($this->cache->contains($id)) {
            return $this->cache->fetch($id);
        }

        $output = $this->retrieveHelpOutput();

        $ret = (Boolean) preg_match('/^'.$name.'/m', $output);

        $this->cache->save($id, $ret);

        return $ret;
    }

    private function retrieveHelpOutput()
    {
        $id = 'help';

        if ($this->cache->contains($id)) {
            return $this->cache->fetch($id);
        }

        try {
            $output = $this->ffprobe->command(array('-help', '-loglevel', 'quiet'));
        } catch (ExecutionFailureException $e) {
            throw new RuntimeException('Your FFProbe version is too old and does not support `-help` option, please upgrade.', $e->getCode(), $e);
        }

        $this->cache->save($id, $output);

        return $output;
    }
}
