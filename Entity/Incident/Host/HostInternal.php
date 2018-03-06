<?php

/*
 * This file is part of the Ngen - CSIRT Incident Report System.
 *
 * (c) CERT UNLP <support@cert.unlp.edu.ar>
 *
 * This source file is subject to the GPL v3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CertUnlp\NgenBundle\Entity\Incident\Host;

use Doctrine\ORM\Mapping as ORM;
use CertUnlp\NgenBundle\Model\NetworkInterface;
use JMS\Serializer\Annotation as JMS;
use CertUnlp\NgenBundle\Entity\Incident\Incident;
use Gedmo\Mapping\Annotation as Gedmo;
use CertUnlp\NgenBundle\Validator\Constraints as NetworkAssert;
use CertUnlp\NgenBundle\Entity\Incident\Host\Host;

/**
 * Description of Incident
 *
 * @author dam
 * @ORM\Entity(repositoryClass="CertUnlp\NgenBundle\Entity\Incident\Host\HostInternalRepository")
 * @ORM\EntityListeners({ "CertUnlp\NgenBundle\Entity\Incident\Host\Listener\HostInternalListener" })
 */
class HostInternal extends Host {

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=15)
     * @NetworkAssert\Ip
     * @NetworkAssert\ValidNetwork
     * @JMS\Expose
     * @JMS\Groups({"api"})
     */
    protected $ip;

    /**
     * @ORM\ManyToOne(targetEntity="CertUnlp\NgenBundle\Model\NetworkInterface", inversedBy="hosts") 
     * @JMS\Expose
     * @JMS\Groups({"api"})
     */
    private $network;

    public function isInternal() {
        return true;
    }

    public function isExternal() {
        return false;
    }

}
