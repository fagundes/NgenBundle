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
 * @ORM\Entity()
 * @JMS\ExclusionPolicy("all")
 */
class NetworkExternal extends Network implements NetworkInterface
{

    /**
     * @ORM\Column(type="string",nullable=true)
     * @JMS\Expose
     * @JMS\Groups({"api"})
     */
    private $abuse_entity;

    /**
     * @ORM\Column(type="array",nullable=true)
     * @JMS\Expose
     * @JMS\Groups({"api"})
     */
    private $abuse_entity_emails;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @JMS\Expose
     * @JMS\Groups({"api"})
     */
    private $network_entity;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @JMS\Expose
     * @JMS\Groups({"api"})
     */
    private $country;

    /**
     * @var string
     */
    private $start_address;

    /**
     * @var string
     */
    private $end_address;

    /**
     * @return string
     */
    public function getStartAddress()
    {
        return $this->start_address;
    }

    /**
     * @param string $start_address
     * @return NetworkExternal
     */
    public function setStartAddress($start_address)
    {
        $this->start_address = $start_address;
        $this->renewIp();
        return $this;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Network
     */
    public function renewIp()
    {
        if ($this->getStartAddress() && $this->getEndAddress()) {
            $this->setIp($this->iprange2cidr($this->getStartAddress(), $this->getEndAddress()));
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getEndAddress()
    {
        return $this->end_address;
    }

    /**
     * @param string $end_address
     * @return NetworkExternal
     */
    public function setEndAddress($end_address)
    {
        $this->end_address = $end_address;
        $this->renewIp();
        return $this;
    }

    private function iprange2cidr($ipStart, $ipEnd)
    {
        $start = ip2long($ipStart);
        $end = ip2long($ipEnd);
        $result = array();

        while ($end >= $start) {
            $maxSize = 32;
            while ($maxSize > 0) {
                $mask = hexdec($this->iMask($maxSize - 1));
                $maskBase = $start & $mask;
                if ($maskBase != $start)
                    break;
                $maxSize--;
            }
            $x = log($end - $start + 1) / log(2);
            $maxDiff = floor(32 - floor($x));

            if ($maxSize < $maxDiff) {
                $maxSize = $maxDiff;
            }

            $ip = long2ip($start);
            array_push($result, "$ip/$maxSize");
            $start += pow(2, (32 - $maxSize));
        }
        return $result[0];
    }

    private function iMask($s)
    {
        return base_convert((pow(2, 32) - pow(2, (32 - $s))), 10, 16);
    }

    public function isInternal()
    {
        return false;
    }

    public function isExternal()
    {
        return true;
    }

    /**
     * @return mixed
     */
    public function getAbuseEntityEmails()
    {
        return $this->abuse_entity_emails;
    }

    /**
     * @param mixed $abuse_entity_emails
     * @return NetworkExternal
     */
    public function setAbuseEntityEmails($abuse_entity_emails)
    {
        $this->abuse_entity_emails = $abuse_entity_emails;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNetworkEntity()
    {
        return $this->network_entity;
    }

    /**
     * @param mixed $network_entity
     * @return NetworkExternal
     */
    public function setNetworkEntity($network_entity)
    {
        $this->network_entity = $network_entity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     * @return NetworkExternal
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    public function getNetworkAdmin()
    {
        $this->getAbuseEntity();
    }

    /**
     * @return mixed
     */
    public function getAbuseEntity()
    {
        return $this->abuse_entity;
    }

    /**
     * @param mixed $abuse_entity
     * @return NetworkExternal
     */
    public function setAbuseEntity($abuse_entity)
    {
        $this->abuse_entity = $abuse_entity;
        return $this;
    }
}
