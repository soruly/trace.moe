<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg;

use Alchemy\BinaryDriver\ConfigurationInterface;
use Alchemy\BinaryDriver\Exception\ExecutionFailureException;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use FFMpeg\Driver\FFProbeDriver;
use FFMpeg\FFProbe\DataMapping\Format;
use FFMpeg\FFProbe\Mapper;
use FFMpeg\FFProbe\MapperInterface;
use FFMpeg\FFProbe\OptionsTester;
use FFMpeg\FFProbe\OptionsTesterInterface;
use FFMpeg\FFProbe\OutputParser;
use FFMpeg\FFProbe\OutputParserInterface;
use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\FFProbe\DataMapping\StreamCollection;
use Psr\Log\LoggerInterface;

class FFProbe
{
    const TYPE_STREAMS = 'streams';
    const TYPE_FORMAT = 'format';

    /** @var Cache */
    private $cache;
    /** @var OptionsTesterInterface */
    private $optionsTester;
    /** @var OutputParserInterface */
    private $parser;
    /** @var FFProbeDriver */
    private $ffprobe;
    /** @var MapperInterface */
    private $mapper;

    public function __construct(FFProbeDriver $ffprobe, Cache $cache)
    {
        $this->ffprobe = $ffprobe;
        $this->optionsTester = new OptionsTester($ffprobe, $cache);
        $this->parser = new OutputParser();
        $this->mapper = new Mapper();
        $this->cache = $cache;
    }

    /**
     * @return OutputParserInterface
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     * @param OutputParserInterface $parser
     *
     * @return FFProbe
     */
    public function setParser(OutputParserInterface $parser)
    {
        $this->parser = $parser;

        return $this;
    }

    /**
     * @return FFProbeDriver
     */
    public function getFFProbeDriver()
    {
        return $this->ffprobe;
    }

    /**
     * @param FFProbeDriver $ffprobe
     *
     * @return FFProbe
     */
    public function setFFProbeDriver(FFProbeDriver $ffprobe)
    {
        $this->ffprobe = $ffprobe;

        return $this;
    }

    /**
     * @param OptionsTesterInterface $tester
     *
     * @return FFProbe
     */
    public function setOptionsTester(OptionsTesterInterface $tester)
    {
        $this->optionsTester = $tester;

        return $this;
    }

    /**
     * @return OptionsTesterInterface
     */
    public function getOptionsTester()
    {
        return $this->optionsTester;
    }

    /**
     * @param Cache $cache
     *
     * @return FFProbe
     */
    public function setCache(Cache $cache)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * @return Cache
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @return MapperInterface
     */
    public function getMapper()
    {
        return $this->mapper;
    }

    /**
     * @param MapperInterface $mapper
     *
     * @return FFProbe
     */
    public function setMapper(MapperInterface $mapper)
    {
        $this->mapper = $mapper;

        return $this;
    }

    /**
     * @api
     *
     * Probes the format of a given file.
     *
     * @param string $pathfile
     *
     * @return Format A Format object
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function format($pathfile)
    {
        return $this->probe($pathfile, '-show_format', static::TYPE_FORMAT);
    }

    /**
     * @api
     *
     * Probes the streams contained in a given file.
     *
     * @param string $pathfile
     *
     * @return StreamCollection A collection of streams
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function streams($pathfile)
    {
        return $this->probe($pathfile, '-show_streams', static::TYPE_STREAMS);
    }

    /**
     * @api
     *
     * Creates an FFProbe.
     *
     * @param array|ConfigurationInterface $configuration
     * @param LoggerInterface              $logger
     * @param Cache                        $cache
     *
     * @return FFProbe
     */
    public static function create($configuration = array(), LoggerInterface $logger = null, Cache $cache = null)
    {
        if (null === $cache) {
            $cache = new ArrayCache();
        }

        return new static(FFProbeDriver::create($configuration, $logger), $cache);
    }

    private function probe($pathfile, $command, $type, $allowJson = true)
    {
        $id = sprintf('%s-%s', $command, $pathfile);

        if ($this->cache->contains($id)) {
            return $this->cache->fetch($id);
        }

        if (!$this->optionsTester->has($command)) {
            throw new RuntimeException(sprintf(
                'This version of ffprobe is too old and '
                . 'does not support `%s` option, please upgrade', $command
            ));
        }

        $commands = array($pathfile, $command);

        $parseIsToDo = false;

        if ($allowJson && $this->optionsTester->has('-print_format')) {
            // allowed in latest PHP-FFmpeg version
            $commands[] = '-print_format';
            $commands[] = 'json';
        } elseif ($allowJson && $this->optionsTester->has('-of')) {
            // option has changed in avconv 9
            $commands[] = '-of';
            $commands[] = 'json';
        } else {
            $parseIsToDo = true;
        }

        try {
            $output = $this->ffprobe->command($commands);
        } catch (ExecutionFailureException $e) {
            throw new RuntimeException(sprintf('Unable to probe %s', $pathfile), $e->getCode(), $e);
        }

        if ($parseIsToDo) {
            $data = $this->parser->parse($type, $output);
        } else {
            try {
                // Malformed json may be retrieved
                $data = $this->parseJson($output);
            } catch (RuntimeException $e) {
                return $this->probe($pathfile, $command, $type, false);
            }
        }

        $ret = $this->mapper->map($type, $data);

        $this->cache->save($id, $ret);

        return $ret;
    }

    private function parseJson($data)
    {
        $ret = @json_decode($data, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new RuntimeException(sprintf('Unable to parse json %s', $ret));
        }

        return $ret;
    }
}
