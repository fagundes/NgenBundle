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
use CertUnlp\NgenBundle\Model\NetworkInterface;
use CertUnlp\NgenBundle\Services\Api\Handler\Handler;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;

class NetworkHandler extends Handler
{

    private $network_internal_handler;
    private $network_external_handler;

    public function __construct(ObjectManager $om, string $entityClass, string $entityType, FormFactoryInterface $formFactory, NetworkInternalHandler $network_internal_handler, NetworkExternalHandler $network_external_handler)
    {
        parent::__construct($om, $entityClass, $entityType, $formFactory);
        $this->network_internal_handler = $network_internal_handler;
        $this->network_external_handler = $network_external_handler;
    }

    /**
     * Get a Entity by id.
     *
     * @param array $parameters
     * @return Network
     */
    public function get(array $parameters)
    {
        $ip_and_mask = explode('/', $parameters['ip']);

        $parameters['ip'] = $ip_and_mask[0];
        if (isset($ip_and_mask[1])) {
            $parameters['ipMask'] = $ip_and_mask[1];
        }
        return $this->getOrCreate($parameters);
    }

    /**
     * @param array $parameters
     * @return Network
     */
    public function getOrCreate(array $parameters): Network
    {
        $network = $this->getByIp($parameters);
        if (!$network) {
            $network = $this->network_external_handler->getOrCreate($parameters);
        }

        return $network;
    }

    /**
     * Get a Network.
     *
     * @param $address
     * @return NetworkInterface
     */
    public function getByIp($ip)
    {
        return $this->repository->findByIp($ip);
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
