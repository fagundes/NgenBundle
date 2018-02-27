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

class HostInternalHandler extends Handler {

    public function prepareToDeletion($incident_report, array $parameters = null) {
        $incident_report->setIsActive(FALSE);
    }

    protected function checkIfExists($incident, $method) {
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
