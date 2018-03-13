<?php

/*
 * This file is part of the Ngen - CSIRT Incident Report System.
 *
 * (c) CERT UNLP <support@cert.unlp.edu.ar>
 *
 * This source file is subject to the GPL v3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CertUnlp\NgenBundle\Services\Host;

use CertUnlp\NgenBundle\Services\Api\Handler\NetworkHandler;

/**
 * Description of HostFactory
 *
 * @author dam
 */
class HostFactory {

    private $network_handler;
    private $host_external_class;
    private $host_internal_class;

    public function __construct(NetworkHandler $network_handler, $host_internal_class, $host_external_class, $host_rdap_client) {

        $this->network_handler = $network_handler;
        $this->host_internal_class = $host_internal_class;
        $this->host_external_class = $host_external_class;
        $this->host_rdap_client = $host_rdap_client;
    }

    public function getHost($ip) {

        $network = $this->network_handler->getByIp($ip);
        if ($network) {
            $host = new $this->host_external_class($ip);
            $host->setNetwork($network);
        } else {
            $host = new $this->host_external_class($ip);
            $host = $this->host_rdap_client->hostSearch($host);
        }

        return $host;
    }

}
