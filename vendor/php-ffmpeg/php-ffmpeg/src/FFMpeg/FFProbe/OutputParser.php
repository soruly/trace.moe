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

use FFMpeg\FFProbe;
use FFMpeg\Exception\InvalidArgumentException;

class OutputParser implements OutputParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse($type, $data)
    {
        switch ($type) {
            case FFProbe::TYPE_FORMAT:
                return $this->parseFormat($data);
                break;
            case FFProbe::TYPE_STREAMS:
                return $this->parseStreams($data);
                break;
            default:
                throw new InvalidArgumentException(sprintf('Unknown data type %s', $type));
        }
    }

    private function parseFormat($data)
    {
        $ret = array();

        foreach (explode(PHP_EOL, $data) as $line) {

            if (in_array($line, array('[FORMAT]', '[/FORMAT]'))) {
                continue;
            }

            $chunks = explode('=', $line);
            $key = array_shift($chunks);

            if ('' === trim($key)) {
                continue;
            }

            $value = trim(implode('=', $chunks));

            if ('nb_streams' === $key) {
                $value = (int) $value;
            }

            if (0 === strpos($key, 'TAG:')) {
                if (!isset($ret['tags'])) {
                    $ret['tags'] = array();
                }
                $ret['tags'][substr($key, 4)] = $value;
            } else {
                $ret[$key] = $value;
            }
        }

        return array('format' => $ret);
    }

    private function parseStreams($data)
    {
        $ret = array();
        $n = -1;

        foreach (explode(PHP_EOL, $data) as $line) {

            if ($line == '[STREAM]') {
                $n ++;
                $ret[$n] = array();
                continue;
            }
            if ($line == '[/STREAM]') {
                continue;
            }

            $chunks = explode('=', $line);
            $key = array_shift($chunks);

            if ('' === trim($key)) {
                continue;
            }

            $value = trim(implode('=', $chunks));

            if ('N/A' === $value) {
                continue;
            }
            if ('profile' === $key && 'unknown' === $value) {
                continue;
            }

            if (in_array($key, array('index', 'width', 'height', 'channels', 'bits_per_sample', 'has_b_frames', 'level', 'start_pts', 'duration_ts'))) {
                $value = (int) $value;
            }

            if (0 === strpos($key, 'TAG:')) {
                if (!isset($ret[$n]['tags'])) {
                    $ret[$n]['tags'] = array();
                }
                $ret[$n]['tags'][substr($key, 4)] = $value;
            } elseif (0 === strpos($key, 'DISPOSITION:')) {
                if (!isset($ret[$n]['disposition'])) {
                    $ret[$n]['disposition'] = array();
                }
                $ret[$n]['disposition'][substr($key, 12)] = $value;
            } else {
                $ret[$n][$key] = $value;
            }
        }

        return array('streams' => $ret);
    }
}
