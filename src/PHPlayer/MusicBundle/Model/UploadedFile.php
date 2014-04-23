<?php

namespace PHPlayer\MusicBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;

class UploadedFile {

	/**
     * @Assert\File(
     *     maxSize = "50M",
     *     mimeTypes = {
     *     		"audio/mpeg", 
     *     		"audio/x-mpeg-3", 
     *     		"audio/ogg",
     *     		"image/jpeg",
     *     		"image/pjpeg",
     *     		"image/png",
     *     		"image/gif"
     *     },
     *     mimeTypesMessage = "Please upload a valid image or audio file"
     * )
     */
	public $myfile;

	public $artist;

	public $album;
}