<?php

/*
 * This file is part of the Ngen - CSIRT Incident Report System.
 *
 * (c) CERT UNLP <support@cert.unlp.edu.ar>
 *
 * This source file is subject to the GPL v3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CertUnlp\NgenBundle\Services\Api\Handler\Incident\Host;

use CertUnlp\NgenBundle\Services\Api\Handler\Handler;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;

class HostHandler extends Handler
{

    public function __construct(ObjectManager $om, $entityClass, $entityType, FormFactoryInterface $formFactory, $network_handler)
    {
        $this->network_handler = $network_handler;
        parent::__construct($om, $entityClass, $entityType, $formFactory);
    }

    public function createEntityInstance($params = []) {
        $host = new $this->entityClass();


        var_dump($incident->getOrigin()->getIp());
        die;
        return $incident;
    }

    public function prepareToDeletion($incident_report, array $parameters = null)
    {
        $incident_report->setIsActive(FALSE);
    }

    protected function checkIfExists($incident, $method)
    {
        $incidentDB = $this->repository->findOneBy(['isClosed' => false, 'ip' => $incident->getIp(), 'type' => $incident->getType()]);
        if ($incidentDB && $method == 'POST') {
            if ($incident->getFeed()->getSlug() == "shadowserver") {
                $incidentDB->setSendReport(false);
            } else {
                $incidentDB->setSendReport($incident->getSendReport());
            }

            if ($incident->getEvidenceFile()) {
                $incidentDB->setEvidenceFile($incident->getEvidenceFile());
            }

            $incident = $incidentDB;
            $incident->setLastTimeDetected(new \DateTime('now'));
        }
        return $incident;
    }

}
