<?php

/*
 * This file is part of the Ngen - CSIRT Incident Report System.
 *
 * (c) CERT UNLP <support@cert.unlp.edu.ar>
 *
 * This source file is subject to the GPL v3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CertUnlp\NgenBundle\Entity\Incident\Host\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use CertUnlp\NgenBundle\Model\IncidentInterface;
use Doctrine\ORM\Mapping as ORM;
use CertUnlp\NgenBundle\Services\Delegator\DelegatorChain;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class HostExternalListener implements ContainerAwareInterface {

    public $delegator_chain;

    public function __construct(DelegatorChain $delegator_chain, ContainerInterface $container) {
        $this->delegator_chain = $delegator_chain;
        $this->setContainer($container);
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    /** @ORM\PrePersist */
    public function prePersistHandler(IncidentInterface $incident, LifecycleEventArgs $event) {
        $this->delegator_chain->prePersistDelegation($incident);
    }

//    /** @ORM\PreUpdate */
//    public function preUpdateHandler(IncidentInterface $incident, PreUpdateEventArgs $event) {
//        $this->incidentPrePersistUpdate($incident, $event);
//        $this->delegator_chain->preUpdateDelegation($incident);
//    }
//
//    /** @ORM\PostPersist */
//    public function postPersistHandler(IncidentInterface $incident, LifecycleEventArgs $event) {
//        $this->delegator_chain->postPersistDelegation($incident);
//        $this->commentThreadUpdate($incident, $event);
//    }
//
//    /** @ORM\PostUpdate */
//    public function postUpdateHandler(IncidentInterface $incident, LifecycleEventArgs $event) {
//        $this->delegator_chain->postUpdateDelegation($incident);
//    }
//
//    /** @PostRemove */
//    public function postRemoveHandler(IncidentInterface $incident, LifecycleEventArgs $event) {
//        
//    }
//
//    /** @PreRemove */
//    public function preRemoveHandler(IncidentInterface $incident, LifecycleEventArgs $event) {
//        
//    }
//
//
//    /** @PostLoad */
//    public function postLoadHandler(IncidentInterface $incident, LifecycleEventArgs $event) {
//        
//    }
//    public function commentThreadUpdate($incident, $event) {
//        $id = $incident->getId();
//        $thread = $this->container->get('fos_comment.manager.thread')->findThreadById($id);
//        if (null === $thread) {
//            $thread = $this->container->get('fos_comment.manager.thread')->createThread();
//            $thread->setId($id);
//            $incident->setCommentThread($thread);
//            $thread->setIncident($incident);
//            $uri = $this->container->get('router')->generate('cert_unlp_ngen_incident_frontend_edit_incident', array(
//                'ip' => $incident->getIp(),
//                'date' => $incident->getDate()->format('Y-m-d'),
//                'type' => $incident->getType()->getSlug()
//            ));
//            $thread->setPermalink($uri);
//
//            // Add the thread
//            $this->container->get('fos_comment.manager.thread')->saveThread($thread);
//        }
//    }
}
