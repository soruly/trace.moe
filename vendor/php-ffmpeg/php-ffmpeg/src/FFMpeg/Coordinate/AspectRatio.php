<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Coordinate;

use FFMpeg\Exception\InvalidArgumentException;

// see http://en.wikipedia.org/wiki/List_of_common_resolutions
class AspectRatio
{
    // named 4:3 or 1.33:1 Traditional TV
    const AR_4_3 = '4/3';
    // named 16:9 or 1.77:1 HD video standard
    const AR_16_9 = '16/9';

    // named 3:2 or 1.5:1 see http://en.wikipedia.org/wiki/135_film
    const AR_3_2 = '3/2';
    // named 5:3 or 1.66:1 see http://en.wikipedia.org/wiki/Super_16_mm
    const AR_5_3 = '5/3';

    // mostly used in Photography
    const AR_5_4 = '5/4';
    const AR_1_1 = '1/1';

    // 1.85:1 US widescreen cinema standard see http://en.wikipedia.org/wiki/Widescreen#Film
    const AR_1_DOT_85_1 = '1.85:1';
    // 2.39:1 or 2.40:1 Current widescreen cinema standard see http://en.wikipedia.org/wiki/Anamorphic_format
    const AR_2_DOT_39_1 = '2.39:1';

    // Rotated constants

    // Rotated 4:3
    const AR_ROTATED_3_4 = '3/4';
    // Rotated 16:9
    const AR_ROTATED_9_16 = '9/16';

    // Rotated 3:2
    const AR_ROTATED_2_3 = '2/3';
    // Rotated 5:3
    const AR_ROTATED_3_5 = '3/5';

    // Rotated 5:4
    const AR_ROTATED_4_5 = '4/5';

    // Rotated 1.85
    const AR_ROTATED_1_DOT_85 = '1/1.85';
    // Rotated 2.39
    const AR_ROTATED_2_DOT_39 = '1/2.39';

    /** @var float */
    private $ratio;

    public function __construct($ratio)
    {
        $this->ratio = $ratio;
    }

    /**
     * Returns the value of the ratio.
     *
     * @return float
     */
    public function getValue()
    {
        return $this->ratio;
    }

    /**
     * Computes the best width for given height and modulus.
     *
     * @param Integer $height
     * @param Integer $modulus
     *
     * @return Integer
     */
    public function calculateWidth($height, $modulus = 1)
    {
        $maxPossibleWidth = $this->getMultipleUp(ceil($this->ratio * $height), $modulus);
        $minPossibleWidth = $this->getMultipleDown(floor($this->ratio * $height), $modulus);

        $maxRatioDiff = abs($this->ratio - ($maxPossibleWidth / $height));
        $minRatioDiff = abs($this->ratio - ($minPossibleWidth / $height));

        return $maxRatioDiff < $minRatioDiff ? $maxPossibleWidth : $minPossibleWidth;
    }

    /**
     * Computes the best height for given width and modulus.
     *
     * @param Integer $width
     * @param Integer $modulus
     *
     * @return Integer
     */
    public function calculateHeight($width, $modulus = 1)
    {
        $maxPossibleHeight = $this->getMultipleUp(ceil($width / $this->ratio), $modulus);
        $minPossibleHeight = $this->getMultipleDown(floor($width / $this->ratio), $modulus);

        $maxRatioDiff = abs($this->ratio - ($width / $maxPossibleHeight));
        $minRatioDiff = abs($this->ratio - ($width / $minPossibleHeight));

        return $maxRatioDiff < $minRatioDiff ? $maxPossibleHeight : $minPossibleHeight;
    }

    private function getMultipleUp($value, $multiple)
    {
        while (0 !== $value % $multiple) {
            $value++;
        }

        return $value;
    }

    private function getMultipleDown($value, $multiple)
    {
        while (0 !== $value % $multiple) {
            $value--;
        }

        return $value;
    }

    /**
     * Creates a ratio based on Dimension.
     *
     * The strategy parameter forces by default to use standardized ratios. If
     * custom ratio need to be used, disable it.
     *
     * @param Dimension $dimension
     * @param Boolean   $forceStandards Whether to force or not standard ratios
     *
     * @return AspectRatio
     *
     * @throws InvalidArgumentException
     */
    public static function create(Dimension $dimension, $forceStandards = true)
    {
        $incoming = $dimension->getWidth() / $dimension->getHeight();

        if ($forceStandards) {
            return new static(static::nearestStrategy($incoming));
        } else {
            return new static(static::customStrategy($incoming));
        }
    }

    private static function valueFromName($name)
    {
        switch ($name) {
            case static::AR_4_3:
                return 4 / 3;
            case static::AR_16_9:
                return 16 / 9;
            case static::AR_1_1:
                return 1 / 1;
            case static::AR_1_DOT_85_1:
                return 1.85;
            case static::AR_2_DOT_39_1:
                return 2.39;
            case static::AR_3_2:
                return 3 / 2;
            case static::AR_5_3:
                return 5 / 3;
            case static::AR_5_4:
                return 5 / 4;
            case static::AR_ROTATED_3_4:
                return 3 / 4;
            case static::AR_ROTATED_9_16:
                return 9 / 16;
            case static::AR_ROTATED_2_3:
                return 2 / 3;
            case static::AR_ROTATED_3_5:
                return 3 / 5;
            case static::AR_ROTATED_4_5:
                return 4 / 5;
            case static::AR_ROTATED_1_DOT_85:
                return 1 / 1.85;
            case static::AR_ROTATED_2_DOT_39:
                return 1 / 2.39;
            default:
                throw new InvalidArgumentException(sprintf('Unable to find value for %s', $name));
        }
    }

    private static function customStrategy($incoming)
    {
        $try = static::nearestStrategy($incoming);

        if (abs($try - $incoming) < $try * 0.05) {
            return $try;
        }

        return $incoming;
    }

    private static function nearestStrategy($incoming)
    {
        $availables = array(
            static::AR_4_3 => static::valueFromName(static::AR_4_3),
            static::AR_16_9 => static::valueFromName(static::AR_16_9),
            static::AR_1_1 => static::valueFromName(static::AR_1_1),
            static::AR_1_DOT_85_1 => static::valueFromName(static::AR_1_DOT_85_1),
            static::AR_2_DOT_39_1 => static::valueFromName(static::AR_2_DOT_39_1),
            static::AR_3_2 => static::valueFromName(static::AR_3_2),
            static::AR_5_3 => static::valueFromName(static::AR_5_3),
            static::AR_5_4 => static::valueFromName(static::AR_5_4),

            // Rotated
            static::AR_ROTATED_4_5 => static::valueFromName(static::AR_ROTATED_4_5),
            static::AR_ROTATED_9_16 => static::valueFromName(static::AR_ROTATED_9_16),
            static::AR_ROTATED_2_3 => static::valueFromName(static::AR_ROTATED_2_3),
            static::AR_ROTATED_3_5 => static::valueFromName(static::AR_ROTATED_3_5),
            static::AR_ROTATED_3_4 => static::valueFromName(static::AR_ROTATED_3_4),
            static::AR_ROTATED_1_DOT_85 => static::valueFromName(static::AR_ROTATED_1_DOT_85),
            static::AR_ROTATED_2_DOT_39 => static::valueFromName(static::AR_ROTATED_2_DOT_39),
        );
        asort($availables);

        $previous = $current = null;

        foreach ($availables as $name => $value) {
            $current = $value;
            if ($incoming <= $value) {
                break;
            }
            $previous = $value;
        }

        if (null === $previous) {
            return $current;
        }

        if (($current - $incoming) < ($incoming - $previous)) {
            return $current;
        }

        return $previous;
    }
}
