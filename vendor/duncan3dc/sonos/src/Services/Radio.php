<?php

namespace duncan3dc\Sonos\Services;

use duncan3dc\DomParser\XmlParser;
use duncan3dc\Sonos\Controller;
use duncan3dc\Sonos\Tracks\Stream;

/**
 * Handle radio streams using TuneIn.
 */
class Radio
{
    /**
     * @var int The key for station types.
     */
    const STATIONS = 0;

    /**
     * @var int The key for show types.
     */
    const SHOWS = 1;

    /**
     * @var Controller $controller The Controller instance to send commands to.
     */
    protected $controller;


    /**
     * Create a new instance.
     *
     * @param Controller $controller A Controller instance to send commands to
     */
    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
    }


    /**
     * Get the favourite radio shows/stations.
     *
     * @param int $type One of the class constants for either shows or stations
     *
     * @return Stream[]
     */
    protected function getFavourites($type)
    {
        $items = [];

        $result = $this->controller->soap("ContentDirectory", "Browse", [
            "ObjectID"          =>  "R:0/{$type}",
            "BrowseFlag"        =>  "BrowseDirectChildren",
            "Filter"            =>  "*",
            "StartingIndex"     =>  0,
            "RequestedCount"    =>  100,
            "SortCriteria"      =>  "",
        ]);
        $parser = new XmlParser($result["Result"]);

        $tagName = ($type === self::STATIONS) ? "item" : "container";
        foreach ($parser->getTags($tagName) as $tag) {
            $title = $tag->getTag("title")->nodeValue;
            $uri = $tag->getTag("res")->nodeValue;
            $items[] = new Stream($uri, $title);
        }

        return $items;
    }


    /**
     * Get the favourite radio stations.
     *
     * @return Stream[]
     */
    public function getStations()
    {
        return $this->getFavourites(self::STATIONS);
    }


    /**
     * Get the favourite radio shows.
     *
     * @return Stream[]
     */
    public function getShows()
    {
        return $this->getFavourites(self::SHOWS);
    }
}
