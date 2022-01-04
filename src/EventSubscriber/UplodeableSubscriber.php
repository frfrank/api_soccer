<?php
namespace App\EventSubscriber;

use App\Behavior\Uplodeable;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UplodeableSubscriber implements EventSubscriber
{
    protected $params;


    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }


    public function getSubscribedEvents ()
    {
        return ['postLoad'];
    }


    public function postLoad (LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $entityClass = ClassUtils::getClass($entity);

        if (in_array(
            Uplodeable::class,
            array_keys((new \ReflectionClass($entityClass))->getTraits()))
        )
        {
            $key = 'app.' . strtolower((new \ReflectionClass($entityClass))->getShortName()) .'_uploads_path';

            if(!$this->params->has($key))
            {
                $key = strtolower((new \ReflectionClass($entityClass))->getShortName()) .'_uploads_path';
            }

            if($this->params->has($key) and $this->params->get($key))
               {
                   $folder = $this->params->get('kernel.project_dir')
                       . DIRECTORY_SEPARATOR
                       . 'public'
                       . DIRECTORY_SEPARATOR
                       . $this->params->get($key);

                   $entity->setUploadsFolder($folder);
               }
        }
    }
}
