<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CertUnlp\NgenBundle\Entity\Incident\Host;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use CertUnlp\NgenBundle\Model\NetworkInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use CertUnlp\NgenBundle\Entity\Incident\Host\Host;

/**
 * Description of Incident
 *
 * @author dam
 * @ORM\Entity(repositoryClass="CertUnlp\NgenBundle\Entity\Incident\Host\Repository\HostExternalListener")
 * @ORM\EntityListeners({ "CertUnlp\NgenBundle\Entity\Incident\Host\Listener\HostExternalListener" })
 */
class HostExternal extends Host {

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=15)
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
        return false;
    }

    public function isExternal() {
        return true;
    }

}
