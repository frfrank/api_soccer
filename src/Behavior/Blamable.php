<?php

namespace App\Behavior;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @ORM\HasLifecycleCallbacks()
 */
trait Blamable
{

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $updatedBy;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $deletedBy;


    public function getCreatedBy (): ?User
    {
        return $this->createdBy;
    }


    public function setCreatedBy (?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }


    public function getUpdatedBy (): ?User
    {
        return $this->updatedBy;
    }


    public function setUpdatedBy (?User $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }


    public function getDeletedBy (): ?User
    {
        return $this->deletedBy;
    }


    public function setDeletedBy (?User $deletedBy): self
    {
        $this->deletedBy = $deletedBy;

        return $this;
    }


    public function setModifyingUserByAction (TokenStorageInterface $storage, string $action)
    {
        $function = 'set' . ucfirst($action) . 'dBy';

        $user = $storage->getToken()->getUser();

        $this->$function($user);
    }
}
