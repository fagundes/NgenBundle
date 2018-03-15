<?php

/*
 * This file is part of the Ngen - CSIRT Incident Report System.
 *
 * (c) CERT UNLP <support@cert.unlp.edu.ar>
 *
 * This source file is subject to the GPL v3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CertUnlp\NgenBundle\Services\Api\Handler\Network;

use CertUnlp\NgenBundle\Entity\Network\Network;
use CertUnlp\NgenBundle\Services\Api\Handler\Handler;
use CertUnlp\NgenBundle\Services\Network\NetworkExternalRdapClient;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;

class NetworkExternalHandler extends Handler
{
    private $rdap_client;

    public function __construct(ObjectManager $om, string $entityClass, string $entityType, FormFactoryInterface $formFactory, NetworkExternalRdapClient $rdap_client)
    {
        parent::__construct($om, $entityClass, $entityType, $formFactory);
        $this->rdap_client = $rdap_client;
    }

    /**
     * @param array $parameters
     * @return \CertUnlp\NgenBundle\Entity\Network\NetworkExternal|null|object
     * @throws \Exception
     */
    public function createEntityInstance($parameters = [])
    {
        $network = parent::createEntityInstance($parameters);
        return $this->getRdapClient()->networkSearch($network);
    }

    /**
     * @return NetworkExternalRdapClient
     */
    public function getRdapClient(): NetworkExternalRdapClient
    {
        return $this->rdap_client;
    }

    /**
     * @param NetworkExternalRdapClient $rdap_client
     * @return NetworkExternalHandler
     */
    public function setRdapClient(NetworkExternalRdapClient $rdap_client): NetworkExternalHandler
    {
        $this->rdap_client = $rdap_client;
        return $this;
    }

    /**
     * Delete a Network.
     *
     * @param Network $network
     * @param array $parameters
     *
     * @return Network
     */
    public function prepareToDeletion($network, array $parameters = null)
    {
        return $network->setIsActive(FALSE);
    }

    protected function checkIfExists($network, $method)
    {
        $networkDB = $this->repository->findOneBy(['ip' => $network->getIP(), 'ipMask' => $network->getIpMask()]);

        if ($networkDB && $method == 'POST') {
            if (!$networkDB->getIsActive()) {
                $networkDB->setIsActive(TRUE);
                $networkDB->setNetworkAdmin($network->getNetworkAdmin());
                $networkDB->setNetworkEntity($network->getNetworkEntity());
            }
            $network = $networkDB;
        }
        return $network;
    }

}
