<?php

/*
 * This file is part of Alchemy\BinaryDriver.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\BinaryDriver;

interface ConfigurationAwareInterface
{
    /**
     * Returns the configuration
     *
     * @return ConfigurationInterface
     */
    public function getConfiguration();

    /**
     * Set the configuration
     *
     * @param ConfigurationInterface $configuration
     */
    public function setConfiguration(ConfigurationInterface $configuration);
}
