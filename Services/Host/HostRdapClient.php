<?php

/*
 * This file is part of the Ngen - CSIRT Host Report System.
 *
 * (c) CERT UNLP <support@cert.unlp.edu.ar>
 *
 * This source file is subject to the GPL v3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CertUnlp\NgenBundle\Services\Host;

use CertUnlp\NgenBundle\Services\Rdap\RdapClient;
use CertUnlp\NgenBundle\Entity\Incident\Host\HostExternal as Host;

class HostRdapClient extends RdapClient {

    public function prePersistDelegation(Host $host) {
        try {
            $this->response = $this->requestIp($host->getIp());
            $this->seachForAbuseEntities();
            $host->setAbuseEntity($this->getAbuseEntity());
            $host->setAbuseEntityEmails($this->getAbuseEntityEmails());
            $host->setNetworkEntity($this->getNetworkEntity());
            $host->setStartAddress($this->getStartAddress());
            $host->setEndAddress($this->getEndAddress());
            $host->setCountry($this->getCountry());
        } catch (Exception $exc) {
            throw new Exception($exc);
        }
    }

    public function hostSearch(Host $host) {
        try {
            $this->response = $this->requestIp($host->getIp());
            $this->seachForAbuseEntities();
            $host->setAbuseEntity($this->getAbuseEntity());
            $host->setAbuseEntityEmails($this->getAbuseEntityEmails());
            $host->setNetworkEntity($this->getNetworkEntity());
            $host->setStartAddress($this->getStartAddress());
            $host->setEndAddress($this->getEndAddress());
            $host->setCountry($this->getCountry());
            return $host;
        } catch (Exception $exc) {
            throw new Exception($exc);
        }
    }

    public function seachForAbuseEntities() {
        if ($this->response->getAbuseEntities()) {
            foreach ($this->response->getAbuseEntities() as $index => $abuse) {
                if (!$abuse->getEmails()) {
                    $new_entity = $this->requestEntity($abuse->getSelfLink());
                    if ($new_entity->getEmails()) {
                        $abuse->object->vcardArray = $new_entity->object->vcardArray;
                    }
                }
            }
        }
    }

    public function getAbuseEntity() {
        return $this->response->getAbuseEntity()->getName();
    }

    public function getAbuseEntityEmails() {
        return $this->response->getAbuseEntity()->getEmails('fn');
    }

    public function getNetworkEntity() {
        return $this->response->getName() . " (" . $this->response->getHandle() . ")";
    }

    public function getStartAddress() {
        return $this->response->getStartAddress();
    }

    public function getEndAddress() {
        return $this->response->getEndAddress();
    }

    public function getCountry() {
        return $this->response->getCountry();
    }

}
