<?php

namespace duncan3dc\Sonos\Tracks;

use duncan3dc\DomParser\XmlWriter;

/**
 * Representation of a stream.
 */
class Stream implements UriInterface
{
    /**
     * @var string $uri The uri of the stream.
     */
    protected $uri = "";

    /**
     * @var string $title The title of the stream.
     */
    protected $title = "";


    /**
     * Create a Stream object.
     *
     * @param string $uri The URI of the stream
     */
    public function __construct($uri, $title = "")
    {
        $this->uri = (string) $uri;
        $this->title = (string) $title;
    }


    /**
     * Get the URI for this stream.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }


    /**
     * Get the title for this stream.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * Get the metadata xml for this stream.
     *
     * @return string
     */
    public function getMetaData()
    {
        $xml = XmlWriter::createXml([
            "DIDL-Lite" =>  [
                "_attributes"   =>  [
                    "xmlns:dc"      =>  "http://purl.org/dc/elements/1.1/",
                    "xmlns:upnp"    =>  "urn:schemas-upnp-org:metadata-1-0/upnp/",
                    "xmlns:r"       =>  "urn:schemas-rinconnetworks-com:metadata-1-0/",
                    "xmlns"         =>  "urn:schemas-upnp-org:metadata-1-0/DIDL-Lite/",
                ],
                "item"  =>  [
                    "_attributes"   =>  [
                        "id"            =>  "-1",
                        "parentID"      =>  "-1",
                        "restricted"    =>  "true",
                    ],
                    "dc:title"          =>  $this->getTitle() ?: "Stream",
                    "upnp:class"        =>  "object.item.audioItem.audioBroadcast",
                    "desc"              =>  [
                        "_attributes"       =>  [
                            "id"        =>  "cdudn",
                            "nameSpace" =>  "urn:schemas-rinconnetworks-com:metadata-1-0/",
                        ],
                        "_value"            =>  "SA_RINCON65031_",
                    ],
                ],
            ]
        ]);

        # Get rid of the xml header as only the DIDL-Lite element is required
        $meta = explode("\n", $xml)[1];

        return $meta;
    }
}
