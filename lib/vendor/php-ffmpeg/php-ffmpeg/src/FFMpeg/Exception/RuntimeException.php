<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Exception;

/**
 * Thrown when an error occured at runtime
 */
class RuntimeException extends \RuntimeException implements ExceptionInterface
{
}
