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

use CertUnlp\NgenBundle\Model\NetworkInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Network
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CertUnlp\NgenBundle\Entity\Network\NetworkRepository")
 * @JMS\ExclusionPolicy("all")
 */
class NetworkInternal extends Network implements NetworkInterface
{
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
     * Set network_admin
     *
     * @param NetworkAdmin $network_admin
     * @return Network
     */
    public function setNetworkAdmin(NetworkAdmin $network_admin = null)
    {
        $this->network_admin = $network_admin;

        return $this;
    }

    /**
     * Get network_admin
     *
     * @return NetworkAdmin
     */
    public function getNetworkAdmin()
    {
        return $this->network_admin;
    }

    /**
     * Set network_entity
     *
     * @param NetworkEntity $network_entity
     * @return Network
     */
    public function setNetworkEntity(NetworkEntity $network_entity = null)
    {
        $this->network_entity = $network_entity;

        return $this;
    }

    /**
     * Get network_entity
     *
     * @return NetworkEntity
     */
    public function getNetworkEntity()
    {
        return $this->network_entity;
    }

    public function isInternal()
    {
        return true;
    }

    public function isExternal()
    {
        return false;
    }

}
