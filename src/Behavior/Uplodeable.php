<?php

namespace App\Behavior;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

trait Uplodeable
{

    private $uploadsFolder;
    private $url;


    public function getUploadsFolder ()
    {
        return $this->uploadsFolder;
    }


    public function setUploadsFolder ($uploadsFolder): self
    {
        $this->uploadsFolder = $uploadsFolder;

        return $this;
    }


    public function getUrl ()
    {
        return $this->url;
    }


    public function setUrl ($url): self
    {
        $this->url = $url;
        return $this;
    }

}
