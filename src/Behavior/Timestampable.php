<?php

namespace App\Behavior;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
trait Timestampable
{
   

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="delete")
     */
    private $deletedAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }


    public function getCreatedAt (): ?\DateTimeInterface
    {
        return $this->createdAt;
    }


    /**
     * @param \DateTimeInterface $createdAt
     * @return $this
     */
    public function setCreatedAt (\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    /**
     * @ORM\PrePersist()
     */
    public function setCreatedAtValue (): void
    {
        $this->createdAt = new \DateTime();
    }


    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setUpdateddAtValue (): void
    {
        $this->updatedAt = new \DateTime();
    }


    public function getUpdatedAt (): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }


    public function setUpdatedAt (\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


    public function getDeletedAt (): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }


    public function setDeletedAt (?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }


}
