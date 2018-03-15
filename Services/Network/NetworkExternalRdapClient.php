<?php

/*
 * This file is part of the Ngen - CSIRT NetworkExternal Report System.
 *
 * (c) CERT UNLP <support@cert.unlp.edu.ar>
 *
 * This source file is subject to the GPL v3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CertUnlp\NgenBundle\Services\Network;

use CertUnlp\NgenBundle\Entity\Network\NetworkExternal;
use CertUnlp\NgenBundle\Services\Rdap\RdapClient;

class NetworkExternalRdapClient extends RdapClient
{

    /**
     * @param NetworkExternal $network
     * @return NetworkExternal
     * @throws \Exception
     */
    public function networkSearch(NetworkExternal $network)
    {
        try {
            $this->setResponse($this->requestIp($network->getIp()));
            $this->seachForAbuseEntities();
            $network->setAbuseEntity($this->getAbuseEntity());
            $network->setAbuseEntityEmails($this->getAbuseEntityEmails());
            $network->setNetworkEntity($this->getNetworkEntity());
            $network->setStartAddress($this->getStartAddress());
            $network->setEndAddress($this->getEndAddress());
            $network->setCountry($this->getCountry());
            return $network;
        } catch (\Exception $exc) {
            throw new \Exception($exc);
        }
    }

    /**
     * @throws \Exception
     */
    public function seachForAbuseEntities()
    {
        if ($this->getResponse()->getAbuseEntities()) {
            foreach ($this->getResponse()->getAbuseEntities() as $index => $abuse) {
                if (!$abuse->getEmails()) {
                    $new_entity = $this->requestEntity($abuse->getSelfLink());
                    if ($new_entity->getEmails()) {
                        $abuse->getObject()->vcardArray = $new_entity->getObject()->vcardArray;
                    }
                }
            }
        }
    }

    public function getAbuseEntity()
    {
        return $this->getResponse()->getAbuseEntity()->getName();
    }

    public function getAbuseEntityEmails()
    {
        return $this->getResponse()->getAbuseEntity()->getEmails('fn');
    }

    public function getNetworkEntity()
    {
        return $this->getResponse()->getName() . " (" . $this->getResponse()->getHandle() . ")";
    }

    public function getStartAddress()
    {
        return $this->getResponse()->getStartAddress();
    }

    public function getEndAddress()
    {
        return $this->getResponse()->getEndAddress();
    }

    public function getCountry()
    {
        return $this->getResponse()->getCountry();
    }

}
