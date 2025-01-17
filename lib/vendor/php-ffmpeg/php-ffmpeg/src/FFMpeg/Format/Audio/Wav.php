<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FFMpeg\Format\Audio;

/**
 * The WAV audio format
 */
class Wav extends DefaultAudio
{

    /**
     * @inheritDoc
     */
    protected $audioCodec = 'pcm_s16le';

    /**
     * @inheritDoc
     */
    public function getAvailableAudioCodecs(): array
    {
        return ['pcm_s16le'];
    }
}
