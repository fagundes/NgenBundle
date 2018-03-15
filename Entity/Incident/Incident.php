<?php

/*
 * This file is part of the Ngen - CSIRT Incident Report System.
 *
 * (c) CERT UNLP <support@cert.unlp.edu.ar>
 *
 * This source file is subject to the GPL v3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CertUnlp\NgenBundle\Entity\Incident;

use CertUnlp\NgenBundle\Entity\IncidentState;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 * @JMS\ExclusionPolicy("all")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 */
class Incident
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Expose
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     * @JMS\Expose
     * @JMS\Type("DateTime<'Y-m-d h:m:s'>")
     * @JMS\Groups({"api"})
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_time_detected", type="datetime",nullable=true)
     * @JMS\Expose
     * @JMS\Type("DateTime<'Y-m-d h:m:s'>")
     * @JMS\Groups({"api"})
     */
    private $lastTimeDetected;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="renotification_date", type="datetime",nullable=true)
     * @JMS\Expose
     * @JMS\Type("DateTime<'Y-m-d h:m:s'>")
     * @JMS\Groups({"api"})
     */
    private $renotificationDate;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     * @JMS\Expose
     * @JMS\Type("DateTime<'Y-m-d h:m:s'>")
     * @JMS\Groups({"api"})
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     * @JMS\Expose
     * @JMS\Type("DateTime<'Y-m-d h:m:s'>")
     * @JMS\Groups({"api"})
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="CertUnlp\NgenBundle\Model\ReporterInterface", inversedBy="incidents")
     */
    private $reporter;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_closed", type="boolean")
     * @JMS\Expose
     * @JMS\Type("boolean")
     */
    private $isClosed = false;

    /**
     * @ORM\ManyToOne(targetEntity="CertUnlp\NgenBundle\Entity\IncidentType",inversedBy="incidents")
     * @ORM\JoinColumn(name="type", referencedColumnName="slug")
     * @JMS\Expose
     * @JMS\Groups({"api"})
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="CertUnlp\NgenBundle\Entity\IncidentFeed", inversedBy="incidents")
     * @ORM\JoinColumn(name="feed", referencedColumnName="slug")
     * @JMS\Expose
     * @JMS\Groups({"api"})
     * @@Assert\NotNull
     */
    private $feed;

    /**
     * @ORM\ManyToOne(targetEntity="CertUnlp\NgenBundle\Entity\IncidentState", inversedBy="incidents")
     * @ORM\JoinColumn(name="state", referencedColumnName="slug")
     * @JMS\Expose
     * @JMS\Groups({"api"})
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity="CertUnlp\NgenBundle\Entity\Incident\Host\Host", inversedBy="incidents_as_origin")
     * @JMS\Expose
     * @JMS\Groups({"api"})
     */
    private $origin;

    /**
     * @ORM\ManyToOne(targetEntity="CertUnlp\NgenBundle\Entity\Incident\Host\Host", inversedBy="incidents_as_destination")
     * @JMS\Expose
     * @JMS\Groups({"api"})
     */
    private $destination;
    /**
     * @Assert\File(maxSize = "500k")
     */
    private $evidence_file;
    /**
     * @ORM\Column(name="evidence_file_path", type="string",nullable=true)
     */
    private $evidence_file_path;
    /**
     * @ORM\Column(name="report_message_id", type="string",nullable=true)
     */
    private $report_message_id;
    /**
     * @var $evidence_file_temp
     */
    private $evidence_file_temp;
    /**
     * @var $sendReport
     */
    private $sendReport;
    /**
     * @var $report_edit
     */
    private $report_edit;
    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"id"},separator="_")
     * @ORM\Column(name="slug", type="string", length=100,nullable=true)
     * @JMS\Expose
     * @JMS\Groups({"api"})
     * */
    private $slug;
    /**
     * @ORM\OneToOne(targetEntity="CertUnlp\NgenBundle\Entity\IncidentCommentThread",mappedBy="incident",fetch="EXTRA_LAZY"))
     */
    private $comment_thread;

    /**
     * Constructor
     * @param null $origin
     * @param null $destination
     */
    public function __construct($origin = null, $destination = null)
    {
        $this->origin = $origin;
        $this->destination = $destination;
    }

    public function __toString()
    {
        return $this->getSlug();
    }

    /**
     * Get isClosed
     *
     * @return boolean
     */
    public function isClosed()
    {
        return $this->getIsClosed();
    }

    public function getOpenDays($lastTimeDetected = false)
    {
        if ($lastTimeDetected) {
            $date = $this->getLastTimeDetected() ? $this->getLastTimeDetected() : $this->getDate();
        } else {
            $date = $this->getDate();
        }
        $diff = $date->diff(new \DateTime());
        return $diff->days;
    }

    /**
     * @return \DateTime
     */
    public function getLastTimeDetected(): \DateTime
    {
        return $this->lastTimeDetected;
    }

    /**
     * @param \DateTime $lastTimeDetected
     * @return Incident
     */
    public function setLastTimeDetected(\DateTime $lastTimeDetected): Incident
    {
        $this->lastTimeDetected = $lastTimeDetected;
        return $this;
    }

    /**
     * Get direction
     *
     * @return string
     */
    public function getDirection()
    {
        return $this->origin->discr . "/" . $this->destination->discr;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Incident
     */
    public function setId(int $id): Incident
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRenotificationDate(): \DateTime
    {
        return $this->renotificationDate;
    }

    /**
     * @param \DateTime $renotificationDate
     * @return Incident
     */
    public function setRenotificationDate(\DateTime $renotificationDate): Incident
    {
        $this->renotificationDate = $renotificationDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return Incident
     */
    public function setCreatedAt(\DateTime $createdAt): Incident
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return Incident
     */
    public function setUpdatedAt(\DateTime $updatedAt): Incident
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * @param mixed $reporter
     * @return Incident
     */
    public function setReporter($reporter)
    {
        $this->reporter = $reporter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Incident
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFeed()
    {
        return $this->feed;
    }

    /**
     * @param mixed $feed
     * @return Incident
     */
    public function setFeed($feed)
    {
        $this->feed = $feed;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param mixed $origin
     * @return Incident
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param mixed $destination
     * @return Incident
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReportMessageId()
    {
        return $this->report_message_id;
    }

    /**
     * @param mixed $report_message_id
     * @return Incident
     */
    public function setReportMessageId($report_message_id)
    {
        $this->report_message_id = $report_message_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEvidenceFileTemp()
    {
        return $this->evidence_file_temp;
    }

    /**
     * @param mixed $evidence_file_temp
     * @return Incident
     */
    public function setEvidenceFileTemp($evidence_file_temp)
    {
        $this->evidence_file_temp = $evidence_file_temp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSendReport()
    {
        return $this->sendReport;
    }

    /**
     * @param mixed $sendReport
     * @return Incident
     */
    public function setSendReport($sendReport)
    {
        $this->sendReport = $sendReport;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReportEdit()
    {
        return $this->report_edit;
    }

    /**
     * @param mixed $report_edit
     * @return Incident
     */
    public function setReportEdit($report_edit)
    {
        $this->report_edit = $report_edit;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCommentThread()
    {
        return $this->comment_thread;
    }

    /**
     * @param mixed $comment_thread
     * @return Incident
     */
    public function setCommentThread($comment_thread)
    {
        $this->comment_thread = $comment_thread;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set state
     *
     * @param \CertUnlp\NgenBundle\Entity\IncidentState $state
     * @return Incident
     */
    public function setState(IncidentState $state = null)
    {
        if (!in_array($state->getSlug(), ['open', 'stand_by'])) {
            $this->close();
        } else {
            $this->open();
        }
        $this->state = $state;

        return $this;
    }

    public function close()
    {
        $this->setIsClosed(true);
    }

    public function open()
    {
        $this->setIsClosed(false);
    }

    /**
     * @return mixed
     */
    public function getEvidenceFile()
    {
        return $this->evidence_file;
    }

    /**
     * Set evidence_file
     *
     * @param File $evidenceFile
     * @return Incident
     */
    public function setEvidenceFile(File $evidenceFile = null)
    {
        $this->evidence_file = $evidenceFile;
        if ($this->getEvidenceFilePath()) {
            $this->setEvidenceFileTemp($this->getEvidenceFilePath());
            $this->setEvidenceFilePath(null);
        } else {
            $this->setEvidenceFilePath('initial');
        }
        return $this;
    }

    /**
     * Get evidence_file_path
     *
     * @return string
     */
    public function getEvidenceFilePath($fullPath = false)
    {

        if ($this->evidence_file_path) {

            if ($fullPath) {
                $pre_path = $this->getEvidenceSubDirectory() . "/";
            } else {
                $pre_path = "";
            }

            return $pre_path . $this . $this->evidence_file_path;
        }
        return null;
    }

    /**
     * Get evidence_file_path
     *
     * @return string
     */
    public function getEvidenceSubDirectory()
    {
        return '/' . $this->getDate()->format('Y') . '/' . $this->getDate()->format('F') . '/' . $this->getDate()->format('d');
    }

    /**
     * @return \DateTime
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return Incident
     */
    public function setDate(\DateTime $date = null): Incident
    {
        $this->date = $date;
        return $this;
    }

}
