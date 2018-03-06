<?php

/*
 * This file is part of the Ngen - CSIRT Incident Report System.
 *
 * (c) CERT UNLP <support@cert.unlp.edu.ar>
 *
 * This source file is subject to the GPL v3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CertUnlp\NgenBundle\Entity\Network;

use Doctrine\ORM\Mapping as ORM;
use CertUnlp\NgenBundle\Model\NetworkInterface;
use CertUnlp\NgenBundle\Model\IncidentInterface;
use CertUnlp\NgenBundle\Validator\Constraints as NetworkAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as JMS;
use CertUnlp\NgenBundle\Entity\Network\Network;

/**
 * Network
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CertUnlp\NgenBundle\Entity\Network\NetworkRepository")
 * @JMS\ExclusionPolicy("all")
 */
class NetworkInternal extends Network {

    /**
     * @var string
     * @NetworkAssert\Ip
     * @ORM\Column(name="ip", type="string", length=40)
     * @JMS\Expose
     */
    private $ip;

    /**
     * @ORM\ManyToOne(targetEntity="CertUnlp\NgenBundle\Entity\Network\NetworkAdmin", inversedBy="networks",cascade={"persist"}) 
     * @JMS\Expose
     */
    private $network_admin;

    /**
     * @ORM\ManyToOne(targetEntity="CertUnlp\NgenBundle\Entity\Network\NetworkEntity", inversedBy="networks",cascade={"persist"}) 
     * @JMS\Expose
     */
    private $network_entity;

    /**
     * Set ip
     *
     * @param string $ip
     * @return Network
     */
    public function setIp($ip) {

        $ip_and_mask = explode('/', $ip);

        $this->ip = $ip_and_mask[0];
        $this->setNumericIp(ip2long($ip_and_mask[0]));
        if (isset($ip_and_mask[1])) {
            $this->setIpMask($ip_and_mask[1]);
        }
        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp() {
        return $this->ip;
    }

    /**
     * Set network_admin
     *
     * @param \CertUnlp\NgenBundle\Entity\Network\NetworkAdmin $network_admin
     * @return Network
     */
    public function setNetworkAdmin(\CertUnlp\NgenBundle\Entity\Network\NetworkAdmin $network_admin = null) {
        $this->network_admin = $network_admin;

        return $this;
    }

    /**
     * Get network_admin
     *
     * @return \CertUnlp\NgenBundle\Entity\Network\NetworkAdmin 
     */
    public function getNetworkAdmin() {
        return $this->network_admin;
    }

    /**
     * Set network_entity
     *
     * @param \CertUnlp\NgenBundle\Entity\Network\NetworkEntity $network_entity
     * @return Network
     */
    public function setNetworkEntity(\CertUnlp\NgenBundle\Entity\Network\NetworkEntity $network_entity = null) {
        $this->network_entity = $network_entity;

        return $this;
    }

    /**
     * Get network_entity
     *
     * @return \CertUnlp\NgenBundle\Entity\Network\NetworkEntity 
     */
    public function getNetworkEntity() {
        return $this->network_entity;
    }

    public function isInternal() {
        return true;
    }

    public function isExternal() {
        return false;
    }

}
