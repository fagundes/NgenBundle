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

use CertUnlp\NgenBundle\Entity\Incident\Host\Host;
use CertUnlp\NgenBundle\Model\NetworkInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Network
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CertUnlp\NgenBundle\Entity\Network\NetworkRepository")
 * @UniqueEntity(
 *     fields={"ip", "ipMask","isActive"},
 *     message="This network was already added!")
 * @JMS\ExclusionPolicy("all")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"network" = "Network" , "internal" = "NetworkInternal", "external" = "NetworkExternal"})
 */
class Network
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_mask", type="string", length=40)
     * @Assert\Range(
     *      min = 1,
     *      max = 32,
     * )
     * @JMS\Expose
     */
    protected $ipMask;
    /**
     * @var string
     * @ORM\Column(name="ip", type="string", length=40)
     * @JMS\Expose
     */
    protected $ip;
    /**
     * @var string
     *
     * @ORM\Column(name="numeric_ip", type="integer",options={"unsigned":true})
     */
    protected $numericIp;
    /**
     * @ORM\OneToMany(targetEntity="CertUnlp\NgenBundle\Entity\Incident\Host\Host",mappedBy="network", cascade={"persist","remove"}, fetch="EAGER"))
     * @JMS\Expose
     */
    protected $hosts;
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     * @JMS\Expose
     */
    protected $isActive = true;
    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     * @JMS\Expose
     * @JMS\Type("DateTime<'Y-m-d h:m:s'>")
     */
    protected $createdAt;
    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     * @JMS\Expose
     * @JMS\Type("DateTime<'Y-m-d h:m:s'>")
     */
    protected $updatedAt;
    /**
     * @var string
     *
     * @ORM\Column(name="numeric_ip_mask", type="bigint", options={"unsigned":true})
     */
    private $numericIpMask;

    /**
     * Constructor
     * @param string $ip
     */
    public function __construct($ip = "")
    {
        $this->setIp($ip);
        $this->hosts = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Network
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function __toString()
    {
        return $this->getIpAndMask();
    }

    public function getIpAndMask()
    {
        return $this->getIp() . "/" . $this->getIpMask();
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Network
     */
    public function setIp($ip)
    {
        $ip_and_mask = explode('/', $ip);

        $this->ip = $ip_and_mask[0];
        $this->setNumericIp(ip2long($ip_and_mask[0]));
        if (isset($ip_and_mask[1])) {
            $this->setIpMask($ip_and_mask[1]);
        }
        return $this;
    }

    /**
     * Get ipMask
     *
     * @return string
     */
    public function getIpMask()
    {
        return $this->ipMask;
    }

    /**
     * Set ipMask
     *
     * @param string $ipMask
     * @return Network
     */
    public function setIpMask($ipMask)
    {
        $this->ipMask = $ipMask;
        $this->setNumericIpMask(0xffffffff << (32 - $ipMask));

        return $this;
    }

    /**
     * Get numericIp
     *
     * @return integer
     */
    public function getNumericIp()
    {
        return $this->numericIp;
    }

    /**
     * Set numericIp
     *
     * @param integer $numericIp
     * @return Network
     */
    public function setNumericIp($numericIp)
    {
        $this->numericIp = $numericIp;

        return $this;
    }

    /**
     * Get numericIpMask
     *
     * @return integer
     */
    public function getNumericIpMask()
    {
        return $this->numericIpMask;
    }

    /**
     * Set numericIpMask
     *
     * @param integer $numericIpMask
     * @return Network
     */
    public function setNumericIpMask($numericIpMask)
    {
        $this->numericIpMask = $numericIpMask;

        return $this;
    }

    public function equals(NetworkInterface $other = null)
    {
        if ($other) {
            return ($this->getIp() == $other->getIp()) && ($this->getIpMask() == $other->getIpMask());
        } else {
            return false;
        }
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Network
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Network
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Add host
     *
     * @param Host $host
     *
     * @return Network
     */
    public function addHost(Host $host)
    {
        $this->hosts[] = $host;

        return $this;
    }

    /**
     * Remove host
     *
     * @param Host $host
     */
    public function removeHost(Host $host)
    {
        $this->hosts->removeElement($host);
    }

    /**
     * Get hosts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHosts()
    {
        return $this->hosts;
    }
}
