<?php

namespace App\EventSubscriber;

use App\Behavior\Blamable;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BlamableSubscriber implements EventSubscriber
{
    private TokenStorageInterface $storage;
    private LoggerInterface $logger;


    /**
     * CourseModifyingUserListener constructor.
     * @param $storage
     */
    public function __construct (TokenStorageInterface $storage, LoggerInterface $logger)
    {
        $this->storage = $storage;
        $this->logger  = $logger;
    }


    public function getSubscribedEvents ()
    {
        return [
            Events::prePersist,
            Events::preRemove,
            Events::preUpdate,
        ];
    }


    public function prePersist (LifecycleEventArgs $event)
    {
        $entity = $event->getObject();

        if (in_array(
            Blamable::class,
            array_keys((new \ReflectionClass(ClassUtils::getClass($entity)))->getTraits())
        ))
        {
            /**
             * @var Blamable $entity
             */
            $entity->setModifyingUserByAction($this->storage, 'create');
            $entity->setModifyingUserByAction($this->storage, 'update');
        }
    }


    public function preUpdate (LifecycleEventArgs $event)
    {
        $entity = $event->getObject();

        if (in_array(
            Blamable::class,
            array_keys((new \ReflectionClass(ClassUtils::getClass($entity)))->getTraits())
        ))
        {
            /**
             * @var Blamable $entity
             */
            if (method_exists($entity, 'setModifyingUserByAction'))
            {
                if (!$entity->getDeletedBy())
                {
                    $entity->setModifyingUserByAction($this->storage, 'update');
                }
            }
        }
    }


    public function preRemove (LifecycleEventArgs $event)
    {
        $entity = $event->getObject();

        if (in_array(
            Blamable::class,
            array_keys((new \ReflectionClass(ClassUtils::getClass($entity)))->getTraits())
        ))
        {
            $entity->setModifyingUserByAction($this->storage, 'delete');
        }
    }
}
