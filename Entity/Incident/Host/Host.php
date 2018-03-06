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
use Symfony\Component\Validator\Constraints as Assert;
use CertUnlp\NgenBundle\Validator\Constraints as CustomAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use CertUnlp\NgenBundle\Model\ReporterInterface;
use CertUnlp\NgenBundle\Model\IncidentInterface;
use CertUnlp\NgenBundle\Model\NetworkInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use JMS\Serializer\Annotation as JMS;
use Doctrine\ORM\Mapping\MappedSuperclass;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 * @JMS\ExclusionPolicy("all")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"host"="host","internal" = "HostInternal", "external" = "HostExternal"})
 */
class Host {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Expose
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=15)
     * @JMS\Expose
     * @JMS\Groups({"api"})
     */
    protected $ip;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     * @JMS\Expose
     * @JMS\Type("DateTime<'Y-m-d h:m:s'>")
     * @JMS\Groups({"api"})
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     * @JMS\Expose
     * @JMS\Type("DateTime<'Y-m-d h:m:s'>")
     * @JMS\Groups({"api"})
     */
    protected $updatedAt;

    /** @ORM\OneToMany(targetEntity="CertUnlp\NgenBundle\Model\IncidentInterface",mappedBy="origin", cascade={"persist","remove"}, fetch="EAGER")) */
    private $incidents_as_origin;

    /** @ORM\OneToMany(targetEntity="CertUnlp\NgenBundle\Model\IncidentInterface",mappedBy="destination", cascade={"persist","remove"}, fetch="EAGER")) */
    private $incidents_as_destination;

    /**
     * @ORM\OneToOne(targetEntity="CertUnlp\NgenBundle\Entity\IncidentCommentThread",mappedBy="incident",fetch="EXTRA_LAZY"))
     */
    private $comment_thread;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     * @JMS\Expose
     */
    private $isActive = true;

    /**
     * @var string
     * 
     * @Gedmo\Slug(fields={"ip"},separator="_")     
     * @ORM\Column(name="slug", type="string", length=100,nullable=true)
     * @JMS\Expose
     * @JMS\Groups({"api"})
     * */
    protected $slug;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    public function __toString() {
        return $this->getSlug();
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Incident
     */
    public function setIp($ip) {
        $this->ip = $ip;
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Incident
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Incident
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * Set network
     *
     * @param \CertUnlp\NgenBundle\Model\NetworkInterface $network
     * @return Incident
     */
    public function setNetwork(NetworkInterface $network = null) {
        
    }

    /**
     * Get network
     *
     * @return \CertUnlp\NgenBundle\Model\NetworkInterface
     */
    public function getNetwork() {
        
    }

    /**
     * Get network
     *
     */
    public function getNetworkAdmin() {
        
    }

    /**
     * Set commentThread
     *
     * @param \CertUnlp\NgenBundle\Entity\IncidentCommentThread $commentThread
     *
     * @return Incident
     */
    public function setCommentThread(\CertUnlp\NgenBundle\Entity\IncidentCommentThread $commentThread = null) {
        $this->comment_thread = $commentThread;

        return $this;
    }

    /**
     * Get commentThread
     *
     * @return \CertUnlp\NgenBundle\Entity\IncidentCommentThread
     */
    public function getCommentThread() {
        return $this->comment_thread;
    }

    public function getEmails() {
        return [];
    }

    public function isInternal() {
        return false;
    }

    public function isExternal() {
        return false;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Incident
     */
    public function setSlug($slug) {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * Add incident
     *
     * @param \CertUnlp\NgenBundle\Entity\Incident $incident
     *
     * @return Host
     */
    public function addIncident(\CertUnlp\NgenBundle\Entity\Incident\Incident $incident) {
        $this->incidents[] = $incident;

        return $this;
    }

    /**
     * Remove incident
     *
     * @param \CertUnlp\NgenBundle\Entity\Incident\Incident $incident
     */
    public function removeIncident(\CertUnlp\NgenBundle\Entity\Incident\Incident $incident) {
        $this->incidents->removeElement($incident);
    }

    /**
     * Get incidents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncidents() {
        return $this->incidents;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Host
     */
    public function setIsActive($isActive) {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive() {
        return $this->isActive;
    }

    /**
     * Add incidentsAsOrigin
     *
     * @param \CertUnlp\NgenBundle\Entity\Incident\Incident $incidentsAsOrigin
     *
     * @return Host
     */
    public function addIncidentsAsOrigin(\CertUnlp\NgenBundle\Entity\Incident\Incident $incidentsAsOrigin) {
        $this->incidents_as_origin[] = $incidentsAsOrigin;

        return $this;
    }

    /**
     * Remove incidentsAsOrigin
     *
     * @param \CertUnlp\NgenBundle\Entity\Incident\Incident $incidentsAsOrigin
     */
    public function removeIncidentsAsOrigin(\CertUnlp\NgenBundle\Entity\Incident\Incident $incidentsAsOrigin) {
        $this->incidents_as_origin->removeElement($incidentsAsOrigin);
    }

    /**
     * Get incidentsAsOrigin
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncidentsAsOrigin() {
        return $this->incidents_as_origin;
    }

    /**
     * Add incidentsAsDestination
     *
     * @param \CertUnlp\NgenBundle\Entity\Incident\Incident $incidentsAsDestination
     *
     * @return Host
     */
    public function addIncidentsAsDestination(\CertUnlp\NgenBundle\Entity\Incident\Incident $incidentsAsDestination) {
        $this->incidents_as_destination[] = $incidentsAsDestination;

        return $this;
    }

    /**
     * Remove incidentsAsDestination
     *
     * @param \CertUnlp\NgenBundle\Entity\Incident\Incident $incidentsAsDestination
     */
    public function removeIncidentsAsDestination(\CertUnlp\NgenBundle\Entity\Incident\Incident $incidentsAsDestination) {
        $this->incidents_as_destination->removeElement($incidentsAsDestination);
    }

    /**
     * Get incidentsAsDestination
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncidentsAsDestination() {
        return $this->incidents_as_destination;
    }

    /**
     * Constructor
     */
    public function __construct($ip = null) {
        $this->ip = $ip;
        $this->incidents_as_origin = new \Doctrine\Common\Collections\ArrayCollection();
        $this->incidents_as_destination = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
