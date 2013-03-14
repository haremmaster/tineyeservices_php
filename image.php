<?php

# Copyright (c) 2013 Idee Inc. All rights reserved worldwide.

//
// Class representing an image.
//
// Image on filesystem:
//     >>> require_once 'image.php';
//     >>> $image = new Image('/path/to/image.jpg', '', 'collection.jpg');
//
// Image URL:
//     >>> $image = new Image('', 'http://www.tineye.com/images/meloncat.jpg', 'collection.jpg');
//
// Image with metadata:
//     >>> $metadata = json_encode(array("keywords" => array("dolphin")));
//     >>> $image = new Image('/path/to/image.jpg', '', '', $metadata);
//
class Image
{
    function __construct($local_filepath='', $url='', $collection_filepath='', $metadata=null)
    {
        $this->data = null;
        $this->local_filepath = $local_filepath;
        $this->url = $url;
        $this->collection_filepath = '';

        # If a filepath is specified, read the image and use its path as the collection filepath.
        if ($local_filepath != '')
        {
            $fp = fopen($local_filepath, 'rb');
            $this->data = stream_get_contents($fp);
            fclose($fp);
            $this->local_filepath = $local_filepath;
        }

        # If no filepath but a URL is specified, use the basename of the URL
        # as the collection filepath.
        $this->url = $url;
        if (is_null($this->data) && $this->url != '')
            $this->collection_filepath = basename($this->url);

        # If user specified their own filepath, then use that instead.
        if ($collection_filepath != '')
            $this->collection_filepath = $collection_filepath;

        # Need to make sure there is at least a file or a URL.
        if (is_null($this->data) && $this->url == '')
            throw new TinEyeServiceError('Image object needs either data or a URL.');

        $this->metadata = $metadata;
    }

    function __toString()
    {
        return "Image(local_filepath=$this->local_filepath, url=$this->url, collection_filepath=$this->collection_filepath, metadata=$this->metadata)";
    }
}
?>