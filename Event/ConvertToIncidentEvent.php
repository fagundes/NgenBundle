<?php

/*
 * This file is part of the Ngen - CSIRT Incident Report System.
 *
 * (c) CERT UNLP <support@cert.unlp.edu.ar>
 *
 * This source file is subject to the GPL v3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CertUnlp\NgenBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class ConvertToIncidentEvent extends Event {

    protected $convertible;

    public function __construct($convertible) {
        $this->convertible = $convertible;
    }

    public function getConvertible() {
        return $this->convertible;
    }

}
