<?php

namespace FFMpeg\Filters\Audio;

use FFMpeg\Media\Audio;
use FFMpeg\Format\AudioInterface;

class SimpleFilter implements AudioFilterInterface
{
    private $params;
    private $priority;

    public function __construct(array $params, $priority = 0)
    {
        $this->params = $params;
        $this->priority = $priority;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Audio $audio, AudioInterface $format)
    {
        return $this->params;
    }
}
