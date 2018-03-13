<?php

/*
 * This file is part of the Ngen - CSIRT Incident Report System.
 *
 * (c) CERT UNLP <support@cert.unlp.edu.ar>
 *
 * This source file is subject to the GPL v3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CertUnlp\NgenBundle\Controller\Api\Network;

use CertUnlp\NgenBundle\Entity\Network\NetworkEntity;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NetworkEntityController extends FOSRestController {

    public function getApiController() {

        return $this->container->get('cert_unlp.ngen.network.entity.api.controller');
    }

    /**
     * List all networks.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getAction(Request $request, ParamFetcherInterface $paramFetcher) {

        return null;
    }

    /**
     * List all academic unit network_entity.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @FOS\Get("/network_entity")

     * @FOS\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing academic unit network_entity.")
     * @FOS\QueryParam(name="limit", requirements="\d+", default="5", description="How many academic unit network_entity to return.")
     *
     * @FOS\View(
     *  templateVar="network_entities"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getNetworkEntitiesAction(Request $request, ParamFetcherInterface $paramFetcher) {
        return $this->getApiController()->getAll($request, $paramFetcher);
    }

    /**
     * Gets a Network for a given id.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a network admin for a given id",
     *   output = "CertUnlp\NgenBundle\Entity\Network\NetworkEntity",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the network is not found"
     *   }
     * )
     *
     * @param int     $id      the network id
     *
     * @return array
     * @FOS\View(
     *  templateVar="network_entity"
     * )
     * @throws NotFoundHttpException when network not exist
     * @ParamConverter("network_entity", class="CertUnlpNgenBundle:NetworkEntity")
     * @FOS\Get("/network_entity/{slug}")
     *         
     */
    public function getNetworkEntityAction(NetworkEntity $network_entity) {
        return $network_entity;
    }

    /**
     * Create a Network from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new network from the submitted data.",
     *   input = "CertUnlp\NgenBundle\Form\NetworkType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\Post("/network_entity")

     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postNetworkEntityAction(Request $request) {
        return $this->getApiController()->post($request);
    }

    /**
     * Update existing network from the submitted data or create a new network at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "CertUnlp\NgenBundle\Form\NetworkType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     * @FOS\Patch("/network_entity/{slug}")
     * @param Request $request the request object
     * @param int     $id      the network id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when network not exist
     */
    public function patchNetworkEntityAction(Request $request, NetworkEntity $network_entity) {
        return $this->getApiController()->patch($request, $network_entity, true);
    }

    /**
     * Update existing network from the submitted data or create a new network at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "CertUnlp\NgenBundle\Form\NetworkType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     * @FOS\Patch("/network_entity/{slug}")
     * @param Request $request the request object
     * @param int     $id      the network id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when network not exist
     */
    public function patchNetworkEntityBySlugAction(Request $request, NetworkEntity $network_entity) {
        return $this->getApiController()->patch($request, $network_entity);
    }

    /**
     * Update existing network from the submitted data or create a new network at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "CertUnlp\NgenBundle\Form\NetworkType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     *
     * @param Request $request the request object
     * @param int     $id      the network id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when network not exist
     * @FOS\Patch("/network_entity/{slug}/activate")
     */
    public function patchNetworkEntityActivateAction(Request $request, NetworkEntity $network_entity) {

        return $this->getApiController()->activate($request, $network_entity);
    }

    /**
     * Update existing network from the submitted data or create a new network at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "CertUnlp\NgenBundle\Form\NetworkType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     *
     * @param Request $request the request object
     * @param int     $id      the network id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when network not exist
     * @FOS\Patch("/network_entity/{slug}/desactivate")
     */
    public function patchNetworkEntityDesactivateAction(Request $request, NetworkEntity $network_entity) {

        return $this->getApiController()->desactivate($request, $network_entity);
    }

}
