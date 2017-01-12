<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\FFProbe\DataMapping;

class StreamCollection implements \Countable, \IteratorAggregate
{
    private $streams;

    public function __construct(array $streams = array())
    {
        $this->streams = array_values($streams);
    }

    /**
     * Returns the first stream of the collection, null if the collection is
     * empty.
     *
     * @return null|Stream
     */
    public function first()
    {
        $stream = reset($this->streams);

        return $stream ?: null;
    }

    /**
     * Adds a stream to the collection.
     *
     * @param Stream $stream
     *
     * @return StreamCollection
     */
    public function add(Stream $stream)
    {
        $this->streams[] = $stream;

        return $this;
    }

    /**
     * Returns a new StreamCollection with only video streams.
     *
     * @return StreamCollection
     */
    public function videos()
    {
        return new static(array_filter($this->streams, function (Stream $stream) {
            return $stream->isVideo();
        }));
    }

    /**
     * Returns a new StreamCollection with only audio streams.
     *
     * @return StreamCollection
     */
    public function audios()
    {
        return new static(array_filter($this->streams, function (Stream $stream) {
            return $stream->isAudio();
        }));
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->streams);
    }

    /**
     * Returns the array of contained streams.
     *
     * @return array
     */
    public function all()
    {
        return $this->streams;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->streams);
    }
}
