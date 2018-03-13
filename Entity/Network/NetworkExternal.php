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
use JMS\Serializer\Annotation as JMS;

/**
 * Network
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CertUnlp\NgenBundle\Entity\Network\NetworkRepository")
 * @JMS\ExclusionPolicy("all")
 */
class NetworkExternal extends Network
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
     * Set ip
     *
     * @param string $ip
     * @return Network
     */
    public function setIp($ip)
    {
        return parent::setIp($this->iprange2cidr($this->getStartAddress(), $this->getEndAddress()));
    }

    private function iprange2cidr($ipStart, $ipEnd)
    {
        $start = ip2long($ipStart);
        $end = ip2long($ipEnd);
        $result = array();

        while ($end >= $start) {
            $maxSize = 32;
            while ($maxSize > 0) {
                $mask = hexdec(iMask($maxSize - 1));
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

    /**
     * Set abuseEntity
     *
     * @param string $abuseEntity
     *
     * @return NetworkExternal
     */
    public function setAbuseEntity($abuseEntity)
    {
        $this->abuse_entity = $abuseEntity;

        return $this;
    }

    /**
     * Get abuseEntity
     *
     * @return string
     */
    public function getAbuseEntity()
    {
        return $this->abuse_entity;
    }

    /**
     * Set abuseEntityEmails
     *
     * @param array $abuseEntityEmails
     *
     * @return NetworkExternal
     */
    public function setAbuseEntityEmails($abuseEntityEmails)
    {
        $this->abuse_entity_emails = $abuseEntityEmails;

        return $this;
    }

    /**
     * Get abuseEntityEmails
     *
     * @return array
     */
    public function getAbuseEntityEmails()
    {
        return $this->abuse_entity_emails;
    }

    /**
     * Set startAddress
     *
     * @param string $startAddress
     *
     * @return NetworkExternal
     */
    public function setStartAddress($startAddress)
    {
        $this->start_address = $startAddress;

        return $this;
    }

    /**
     * Get startAddress
     *
     * @return string
     */
    public function getStartAddress()
    {
        return $this->start_address;
    }

    /**
     * Set endAddress
     *
     * @param string $endAddress
     *
     * @return NetworkExternal
     */
    public function setEndAddress($endAddress)
    {
        $this->end_address = $endAddress;

        return $this;
    }

    /**
     * Get endAddress
     *
     * @return string
     */
    public function getEndAddress()
    {
        return $this->end_address;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return NetworkExternal
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set network_entity
     *
     * @param \CertUnlp\NgenBundle\Entity\Network\NetworkEntity $network_entity
     * @return Network
     */
    public function setNetworkEntity(\CertUnlp\NgenBundle\Entity\Network\NetworkEntity $network_entity = null)
    {
        $this->network_entity = $network_entity;

        return $this;
    }

    /**
     * Get network_entity
     *
     * @return \CertUnlp\NgenBundle\Entity\Network\NetworkEntity
     */
    public function getNetworkEntity()
    {
        return $this->network_entity;
    }

    public function isInternal()
    {
        return false;
    }

    public function isExternal()
    {
        return true;
    }

}
