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
 * Thrown when the driver expects different kind of arguments
 */
class InvalidArgumentException extends \InvalidArgumentException implements ExceptionInterface
{
}
