<?php

/*
 * This file is part of the Ngen - CSIRT Incident Report System.
 *
 * (c) CERT UNLP <support@cert.unlp.edu.ar>
 *
 * This source file is subject to the GPL v3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CertUnlp\NgenBundle\Services\Frontend\Controller;

use CertUnlp\NgenBundle\Services\Frontend\Controller\FrontendController;

class IncidentFrontendController extends FrontendController {

    public function __construct($doctrine, $formFactory, $entityType, $paginator, $finder, $comment_manager, $thread_manager, $evidence_path) {
        parent::__construct($doctrine, $formFactory, $entityType, $paginator, $finder, $comment_manager, $thread_manager);
        $this->evidence_path = $evidence_path;
    }

}
