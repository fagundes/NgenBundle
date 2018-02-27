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

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use \CertUnlp\NgenBundle\Exception\InvalidFormException;
use Symfony\Component\Security\Core\SecurityContext;
use CertUnlp\NgenBundle\Services\Api\Handler\Handler;
use CertUnlp\NgenBundle\Services\Api\Handler\UserHandler;

abstract class HostHandler extends Handler {

    public function createEntityInstance($params = []) {
        $incident = new $this->entityClass();

        $incident->setOrigin($this->host_handler->createEntityInstance($params['origin']));
        $incident->setDestination($this->host_handler->createEntityInstance($params['destination']));
        var_dump($incident);
        die;
        return $incident;
    }

    protected function prepareToDeletion($incident, array $parameters) {
        $incident->close();
    }

}
