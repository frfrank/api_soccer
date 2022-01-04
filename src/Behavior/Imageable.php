<?php

namespace App\Behavior;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Trait Imageable
 * @package App\Entity\Behaviors
 *
 * @Vich\Uploadable
 */
trait Imageable
{
    use Uplodeable;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="null", fileNameProperty="image")
     */
    private $imageFile;

    /**
     * @Groups({"imageable"})
     * @SerializedName ("image")
     */
    private $imageUrl;


    /**
     * @Groups({"imageable"})
     * @SerializedName ("thumbnail")
     */
    private $thumbnailUrl;


    private array $thumbnails = [];

    /**
     * @Groups({"imageable"})
     * @SerializedName ("thumbnails")
     */
    private $thumbnailUrls = [];

    /**
     * @param mixed $imageFile
     * @return mixed
     */
    public function setImageFile ($imageFile): self
    {
        $this->imageFile = $imageFile;

        if ($imageFile)
        {
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }


    public function getImageFile ()
    {
        return $this->imageFile;
    }


    public function setImage ($image): self
    {
        $this->image = $image;
        return $this;
    }


    public function getImage ()
    {
        return $this->image;
    }


    public function getImageUrl ()
    {
        return $this->imageUrl;
    }


    public function setImageUrl ($imageUrl): self
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }


    public function getThumbnailUrl ()
    {
        return $this->thumbnailUrl;
    }


    public function setThumbnailUrl ($thumbnailUrl): self
    {
        $this->thumbnailUrl = $thumbnailUrl;
        return $this;
    }


    public function getThumbnails():array
    {
        return $this->thumbnails;
    }

    public function setThumbnails(array $thumbnails): self
    {
        $this->thumbnails = $thumbnails;
        return $this;
    }


    public function getThumbnailUrls (): array
    {
        return $this->thumbnailUrls;
    }


    public function setThumbnailUrls (array $thumbnailUrls): self
    {
        $this->thumbnailUrls = $thumbnailUrls;
        return $this;
    }

}
