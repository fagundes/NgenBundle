<?php

/*
 * This file is part of the Ngen - CSIRT Incident Report System.
 *
 * (c) CERT UNLP <support@cert.unlp.edu.ar>
 *
 * This source file is subject to the GPL v3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CertUnlp\NgenBundle\Services\Api\Handler\Incident\Host;

use CertUnlp\NgenBundle\Entity\Incident\Host\Host;
use CertUnlp\NgenBundle\Services\Api\Handler\Handler;
use CertUnlp\NgenBundle\Services\Api\Handler\Network\NetworkHandler;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;

class HostHandler extends Handler
{

    private $network_handler;

    public function __construct(ObjectManager $om, string $entityClass, string $entityType, FormFactoryInterface $formFactory, NetworkHandler $network_handler)
    {
        $this->network_handler = $network_handler;
        parent::__construct($om, $entityClass, $entityType, $formFactory);
    }

    /**
     * @param array $parameters
     * @return Host
     */
    public function createEntityInstance($parameters = []): Host
    {
        $host = parent::createEntityInstance($parameters);
        $host->setNetwork($this->network_handler->get($parameters));
        return $host;
    }

    /**
     * @param Host $host
     * @param array|null $parameters
     * @return Host
     */
    public function prepareToDeletion($host, array $parameters = null)
    {
        return $host->setIsActive(FALSE);
    }

    /**
     * @param Host $host
     * @param $method
     * @return Host|null|object
     */
    protected function checkIfExists($host, $method)
    {
        $hostDB = $this->repository->findOneBy(['isClosed' => false, 'ip' => $host->getIp(), 'type' => $host->getType()]);
        if ($hostDB && $method == 'POST') {
            $host = $hostDB;
            $host->setLastTimeDetected(new \DateTime('now'));
        }
        return $host;
    }

}
