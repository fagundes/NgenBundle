<?php

/*
 * This file is part of the Ngen - CSIRT Incident Report System.
 *
 * (c) CERT UNLP <support@cert.unlp.edu.ar>
 *
 * This source file is subject to the GPL v3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CertUnlp\NgenBundle\Controller\Frontend\Incident;

use Symfony\Component\HttpFoundation\Request;
use CertUnlp\NgenBundle\Model\IncidentInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use CertUnlp\NgenBundle\Form\IncidentType;
use CertUnlp\NgenBundle\Form\Incident;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IncidentFrontendController extends Controller {

    public function getFrontendController() {
        return $this->get('cert_unlp.ngen.incident.frontend.controller');
    }

    /**
     * @Template("CertUnlpNgenBundle:Incident:Frontend/home.html.twig")
     * @Route("/", name="cert_unlp_ngen_incident_frontend_home")
     */
    public function homeAction(Request $request) {               
        return $this->getFrontendController()->homeEntity($request);
    }

    /**
     * @Template("CertUnlpNgenBundle:Incident:Frontend/incidentForm.html.twig")
     * @Route("/new", name="cert_unlp_ngen_incident_frontend_new_incident")
     */
    public function newIncidentAction(Request $request) {
        return $this->getFrontendController()->newEntity($request);
    }

    /**
     * @Template("CertUnlpNgenBundle:Incident:Frontend/incidentForm.html.twig")
     * @Route("{ip}/{date}/{type}/edit", name="cert_unlp_ngen_incident_frontend_edit_incident")
     * @ParamConverter("incident", class="CertUnlpNgenBundle:Incident", options={"repository_method" = "findByHostDateType"})

     */
    public function editIncidentAction(IncidentInterface $incident) {
        return $this->getFrontendController()->editEntity($incident);
    }

    /**
     * @Template("CertUnlpNgenBundle:Incident:Frontend/incidentDetail.html.twig")
     * @Route("{ip}/{date}/{type}/detail", name="cert_unlp_ngen_incident_frontend_detail_incident")
     * @ParamConverter("incident", class="CertUnlpNgenBundle:Incident", options={"repository_method" = "findByHostDateType"})

     */
    public function detailIncidentAction(IncidentInterface $incident) {
        return $this->getFrontendController()->detailEntity($incident);
    }

    /**
     * @Route("{ip}/{date}/{type}/evidence", name="cert_unlp_ngen_incident_frontend_evidence_incident")
     * @ParamConverter("incident", class="CertUnlpNgenBundle:Incident", options={"repository_method" = "findByHostDateType"})

     */
    public function evidenceIncidentAction(IncidentInterface $incident) {

        return $this->getFrontendController()->evidenceIncidentAction($incident);
    }

    /**
     * @Template("CertUnlpNgenBundle:Incident:Frontend/home.html.twig")
     * @Route("search", name="cert_unlp_ngen_incident_search_incident")
     */
    public function searchIncidentAction(Request $request) {
        return $this->getFrontendController()->searchEntity($request);
    }

    /**
     * @Template("CertUnlpNgenBundle:Incident:Frontend/incidentComments.html.twig")
     */
    public function incidentCommentsAction(IncidentInterface $incident, Request $request) {
        return $this->getFrontendController()->commentsEntity($incident, $request);
    }

}