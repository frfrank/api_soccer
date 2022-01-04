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
trait Fileable
{
    use Uplodeable;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @Vich\UploadableField(mapping="null", fileNameProperty="filename", originalName="originalName", size="fileSize", mimeType="mimeType")
     */
    private $filenameFile;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"fileable"})
     * @SerializedName ("fileOriginalName")
     */
    private $originalName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"fileable"})
     * @SerializedName ("fileSize")
     */
    private $fileSize;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     * @Groups({"fileable"})
     * @SerializedName ("fileMimeType")
     */
    private $mimeType;


    public function getFilename (): ?string
    {
        return $this->filename;
    }


    public function setFilename (string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }


    public function getOriginalName (): ?string
    {
        return $this->originalName;
    }


    public function setOriginalName (?string $originalName): self
    {
        $this->originalName = $originalName;

        return $this;
    }


    public function getFileSize (): ?int
    {
        return $this->fileSize;
    }


    public function setFileSize (?int $fileSize): self
    {
        $this->fileSize = $fileSize;

        return $this;
    }


    public function getMimeType (): ?string
    {
        return $this->mimeType;
    }


    public function setMimeType (?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }


    /**
     * @param mixed $filenameFile
     * @return mixed
     */
    public function setFilenameFile ($filenameFile): self
    {
        $this->filenameFile = $filenameFile;

        if ($filenameFile)
        {
            $this->fileSize  = filesize($filenameFile);
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }


    public function getFilenameFile ()
    {
        return $this->filenameFile;
    }

}
