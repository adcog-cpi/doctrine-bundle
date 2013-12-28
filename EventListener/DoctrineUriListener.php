<?php

namespace EB\DoctrineBundle\EventListener;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use EB\DoctrineBundle\Entity\UriInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use EB\StringBundle\String\StringService;

/**
 * Class DoctrineUriListener
 *
 * @author "Emmanuel BALLERY" <emmanuel.ballery@gmail.com>
 */
class DoctrineUriListener
{
    /**
     * @var StringService
     */
    private $string;

    /**
     * @param StringService $string
     */
    public function __construct(StringService $string)
    {
        $this->string = $string;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof UriInterface) {
            $entity->setUri($this->string->uri($entity->getStringToUri()));
        }
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof UriInterface) {
            $entity->setUri($this->string->uri($entity->getStringToUri()));

            // Save new value
            $mdt = $args->getEntityManager()->getClassMetadata(get_class($entity));
            $args->getEntityManager()->getUnitOfWork()->recomputeSingleEntityChangeSet($mdt, $entity);
        }
    }
}