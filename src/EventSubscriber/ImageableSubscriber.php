<?php

namespace App\EventSubscriber;

use App\Behavior\Imageable;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageableSubscriber implements EventSubscriber
{
    protected ?\Vich\UploaderBundle\Templating\Helper\UploaderHelper $uploaderHelper;
    protected ?CacheManager $imagineCacheManager;


    public function __construct (\Vich\UploaderBundle\Templating\Helper\UploaderHelper $uploadHelper, CacheManager $imagineCacheManager)
    {
        $this->uploaderHelper      = $uploadHelper;
        $this->imagineCacheManager = $imagineCacheManager;
    }


    public function getSubscribedEvents ()
    {
        return ['postLoad'];
    }


    public function postLoad (LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (in_array(
            Imageable::class,
            array_keys((new \ReflectionClass(ClassUtils::getClass($entity)))->getTraits())
        ))
        {
            /**
             * @var Imageable $entity
             */
            $entity->setImageUrl($this->uploaderHelper->asset($entity, 'imageFile'));
            if($entity->getImageUrl())
            {
                $thumbnailPath = $this->imagineCacheManager->getBrowserPath($entity->getImageUrl(), 'thumb');
                $entity->setThumbnailUrl($thumbnailPath);

                $thumbnails = [];

                    if(!empty($entity->getThumbnails()))
                    {
                        foreach($entity->getThumbnails() as $thumbnail)
                        {
                            $thumbnails[$thumbnail] = $this->imagineCacheManager->getBrowserPath($entity->getImageUrl(), $thumbnail);
                        }

                        $entity->setThumbnailUrls($thumbnails);
                    }
            }
        }
    }
}
