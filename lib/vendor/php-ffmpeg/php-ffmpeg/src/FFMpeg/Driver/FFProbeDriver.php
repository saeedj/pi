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

/**
 * Driver responsible for FFProbe
 */
class FFProbeDriver extends AbstractBinary
{

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'ffprobe';
    }

    /**
     * Creates an FFProbeDriver.
     *
     * @param array|ConfigurationInterface $configuration
     * @param LoggerInterface              $logger
     *
     * @return FFProbeDriver
     */
    public static function create($configuration, LoggerInterface $logger = null) : FFProbeDriver
    {
        if (!($configuration instanceof ConfigurationInterface)) {
            $configuration = new Configuration($configuration);
        }

        $binaries = $configuration->get('ffprobe.binaries', ['ffprobe']);

        try {
            return static::load($binaries, $logger, $configuration);
        } catch (BinaryDriverExecutableNotFound $e) {
            throw new ExecutableNotFoundException('Unable to load FFProbe', $e->getCode(), $e);
        }
    }
}
