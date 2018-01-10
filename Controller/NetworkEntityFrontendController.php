<?php

/*
 * This file is part of the Ngen - CSIRT Incident Report System.
 * 
 * (c) CERT UNLP <support@cert.unlp.edu.ar>
 * 
 * This source file is subject to the GPL v3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * Description of NetworkEntityFrontendController
 *
 * @author dam
 */

namespace CertUnlp\NgenBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CertUnlp\NgenBundle\Form\NetworkEntityType;
use CertUnlp\NgenBundle\Entity\Network\NetworkEntity;

class NetworkEntityFrontendController extends Controller {

    public function getFrontendController() {
        return $this->get('cert_unlp.ngen.network_entity.frontend.controller');
    }

    /**
     * @Template("CertUnlpNgenBundle:NetworkEntity:Frontend/home.html.twig")
     * @Route("/", name="cert_unlp_ngen_network_entity_frontend_home")
     */
    public function homeAction(Request $request) {
        return $this->getFrontendController()->homeEntity($request);
    }

    /**
     * @Template("CertUnlpNgenBundle:NetworkEntity:Frontend/home.html.twig")
     * @Route("search", name="cert_unlp_ngen_network_entity_search")
     */
    public function searchNetworkEntityAction(Request $request) {
        return $this->getFrontendController()->searchEntity($request);
    }

    /**
     * @Template("CertUnlpNgenBundle:NetworkEntity:Frontend/networkEntityForm.html.twig")
     * @Route("/new", name="cert_unlp_ngen_network_entity_new")
     */
    public function newNetworkEntityAction(Request $request) {
        return $this->getFrontendController()->newEntity($request);
    }

    /**
     * @Template("CertUnlpNgenBundle:NetworkEntity:Frontend/networkEntityForm.html.twig")
     * @Route("{slug}/edit", name="cert_unlp_ngen_network_entity_edit")
     */
    public function editNetworkEntityAction(NetworkEntity $NetworkEntity) {
        return $this->getFrontendController()->editEntity($NetworkEntity);
    }

    /**
     * @Template("CertUnlpNgenBundle:NetworkEntity:Frontend/networkEntityDetail.html.twig")
     * @Route("{slug}/detail", name="cert_unlp_ngen_network_entity_detail")
     */
    public function detailNetworkEntityAction(NetworkEntity $NetworkEntity) {
        return $this->getFrontendController()->detailEntity($NetworkEntity);
    }

}
