<?php

namespace duncan3dc\Sonos;

use duncan3dc\Sonos\Tracks\Track;

/**
 * Representation of the current state of a controller.
 */
class ControllerState
{
    /**
     * @var int One of the Controller STATE_ constants
     */
    public $state;

    /**
     * @var int $track The zero-based number of the track in the queue.
     */
    public $track;

    /**
     * @var string $position The position of the currently active track (hh:mm:ss).
     */
    public $position;

    /**
     * @var bool $repeat Whether repeat mode is currently active.
     */
    public $repeat;

    /**
     * @var bool $shuffle Whether shuffle is currently active.
     */
    public $shuffle;

    /**
     * @var bool $crossfade Whether crossfade is currently active.
     */
    public $crossfade;

    /**
     * @var Track[] $tracks An array of tracks from the queue.
     */
    public $tracks;

    /**
     * Create a ControllerState object.
     *
     * @param Controller $controller The Controller to grab the state of
     */
    public function __construct(Controller $controller)
    {
        $this->state = $controller->getState();

        $details = $controller->getStateDetails();
        $this->track = $details->queueNumber;
        $this->position = $details->position;

        $mode = $controller->getMode();
        $this->repeat = $mode["repeat"];
        $this->shuffle = $mode["shuffle"];

        $this->crossfade = $controller->getCrossfade();

        $this->tracks = $controller->getQueue()->getTracks();
    }
}
