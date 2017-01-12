<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Format\ProgressListener;

use Alchemy\BinaryDriver\Listeners\ListenerInterface;
use Evenement\EventEmitter;
use FFMpeg\FFProbe;
use FFMpeg\Exception\RuntimeException;

/**
 * @author Robert Gruendler <r.gruendler@gmail.com>
 */
abstract class AbstractProgressListener extends EventEmitter implements ListenerInterface
{
    /** @var integer */
    private $duration;

    /** @var integer */
    private $totalSize;

    /** @var integer */
    private $currentSize;

    /** @var integer */
    private $currentTime;

    /** @var double */
    private $lastOutput = null;

    /** @var FFProbe */
    private $ffprobe;

    /** @var string */
    private $pathfile;

    /** @var Boolean */
    private $initialized = false;

    /** @var integer */
    private $currentPass;

    /** @var integer */
    private $totalPass;

    /**
     * Transcoding rate in kb/s
     *
     * @var integer
     */
    private $rate;

    /**
     * Percentage of transcoding progress (0 - 100)
     *
     * @var integer
     */
    private $percent = 0;

    /**
     * Time remaining (seconds)
     *
     * @var integer
     */
    private $remaining = null;

    /**
     * @param FFProbe $ffprobe
     * @param string  $pathfile
     * @param integer $currentPass The cureent pass number
     * @param integer $totalPass   The total number of passes
     *
     * @throws RuntimeException
     */
    public function __construct(FFProbe $ffprobe, $pathfile, $currentPass, $totalPass)
    {
        $this->ffprobe = $ffprobe;
        $this->pathfile = $pathfile;
        $this->currentPass = $currentPass;
        $this->totalPass = $totalPass;
    }

    /**
     * @return FFProbe
     */
    public function getFFProbe()
    {
        return $this->ffprobe;
    }

    /**
     * @return string
     */
    public function getPathfile()
    {
        return $this->pathfile;
    }

    /**
     * @return integer
     */
    public function getCurrentPass()
    {
        return $this->currentPass;
    }

    /**
     * @return integer
     */
    public function getTotalPass()
    {
        return $this->totalPass;
    }

    /**
     * @return int
     */
    public function getCurrentTime()
    {
        return $this->currentTime;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($type, $data)
    {
        if (null !== $progress = $this->parseProgress($data)) {
            $this->emit('progress', array_values($progress));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function forwardedEvents()
    {
        return array();
    }

    /**
     * Get the regex pattern to match a ffmpeg stderr status line
     */
    abstract protected function getPattern();

    /**
     * @param string $progress A ffmpeg stderr progress output
     *
     * @return array the progressinfo array or null if there's no progress available yet.
     */
    private function parseProgress($progress)
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        if (null === $this->totalSize || null === $this->duration) {
            return;
        }

        $matches = array();

        if (preg_match($this->getPattern(), $progress, $matches) !== 1) {
            return null;
        }

        $currentDuration = $this->convertDuration($matches[2]);
        $currentTime = microtime(true);
        $currentSize = trim(str_replace('kb', '', strtolower(($matches[1]))));
        $percent = max(0, min(1, $currentDuration / $this->duration));

        if ($this->lastOutput !== null) {
            $delta = $currentTime - $this->lastOutput;
            $deltaSize = $currentSize - $this->currentSize;
            $rate = $deltaSize * $delta;
            if ($rate > 0) {
                $totalDuration = $this->totalSize / $rate;
                $this->remaining = floor($totalDuration - ($totalDuration * $percent));
                $this->rate = floor($rate);
            } else {
                $this->remaining = 0;
                $this->rate = 0;
            }
        }

        $percent = $percent / $this->totalPass + ($this->currentPass - 1) / $this->totalPass;

        $this->percent = floor($percent * 100);
        $this->lastOutput = $currentTime;
        $this->currentSize = (int) $currentSize;
        $this->currentTime = $currentDuration;

        return $this->getProgressInfo();
    }

    /**
     *
     * @param  string $rawDuration in the format 00:00:00.00
     * @return number
     */
    private function convertDuration($rawDuration)
    {
        $ar = array_reverse(explode(":", $rawDuration));
        $duration = floatval($ar[0]);
        if (!empty($ar[1])) {
            $duration += intval($ar[1]) * 60;
        }
        if (!empty($ar[2])) {
            $duration += intval($ar[2]) * 60 * 60;
        }

        return $duration;
    }

    /**
     * @return array
     */
    private function getProgressInfo()
    {
        if ($this->remaining === null) {
            return null;
        }

        return array(
            'percent'   => $this->percent,
            'remaining' => $this->remaining,
            'rate'      => $this->rate
        );
    }

    private function initialize()
    {
        try {
            $format = $this->ffprobe->format($this->pathfile);
        } catch (RuntimeException $e) {
            return;
        }

        if (false === $format->has('size') || false === $format->has('duration')) {
            return;
        }

        $this->totalSize = $format->get('size') / 1024;
        $this->duration = $format->get('duration');

        $this->initialized = true;
    }
}
