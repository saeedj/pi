<?php
/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FFMpeg\Media;

use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Coordinate\Dimension;

class Video extends AbstractVideo
{

    /**
     * Gets the frame at timecode.
     *
     * @param  TimeCode $at
     * @return Frame
     */
    public function frame(TimeCode $at) : Frame
    {
        return new Frame($this, $this->driver, $this->ffprobe, $at);
    }

    /**
     * Extracts a gif from a sequence of the video.
     *
     * @param  TimeCode  $at
     * @param  Dimension $dimension
     * @param  integer   $duration
     * @return Gif
     */
    public function gif(TimeCode $at, Dimension $dimension, $duration = null) : Gif
    {
        return new Gif($this, $this->driver, $this->ffprobe, $at, $dimension, $duration);
    }

    /**
     * Concatenates a list of videos into one unique video.
     *
     * @param  array $sources
     * @return Concat
     */
    public function concat($sources) : Concat
    {
        return new Concat($sources, $this->driver, $this->ffprobe);
    }

    /**
     * Clips the video at the given time(s).
     *
     * @param TimeCode $start Start time
     * @param TimeCode $duration Duration
     * @return \FFMpeg\Media\Clip
     */
    public function clip(TimeCode $start, TimeCode $duration = null)
    {
        return new Clip($this, $this->driver, $this->ffprobe, $start, $duration);
    }
}
