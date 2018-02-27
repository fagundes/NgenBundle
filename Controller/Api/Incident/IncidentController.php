<?php

/*
 * This file is part of the Ngen - CSIRT Incident Report System.
 *
 * (c) CERT UNLP <support@cert.unlp.edu.ar>
 *
 * This source file is subject to the GPL v3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CertUnlp\NgenBundle\Controller\Api\Incident;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CertUnlp\NgenBundle\Entity\Incident\Incident;
use CertUnlp\NgenBundle\Entity\IncidentState;
use FOS\RestBundle\Controller\Annotations as FOS;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use CertUnlp\NgenBundle\Entity\IncidentType;

class IncidentController extends FOSRestController {

    public function getApiController() {

        return $this->container->get('cert_unlp.ngen.incident.api.controller');
    }

    /**
     * List all incidents.
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
     * Prints a mail template for the given incident.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Prints a mail twig template for the given incident type.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the incident is not found"
     *   }
     * )
     * @param int     $id      the incident id
     *
     * @return array
     */
    public function getIncidentReportHtmlAction(IncidentType $incidentType) {

        return $this->getApiController()->reportHtmlAction($incidentType->getSlug());
    }

    /**
     * Prints a mail template for the given incident.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Prints a mail html template for the given incident.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the incident is not found"
     *   }
     * )
     *
     * @Fos\View()
     *
     * @param int     $id      the incident id
     *
     * @return array
     */
    public function getIncidentReportMailAction(Incident $incident) {

        return $this->getApiController()->reportMailAction($incident);
    }

    /**
     * List all incidents.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     * @FOS\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing incidents.")
     * @FOS\QueryParam(name="limit", requirements="\d+", default="5", description="How many incidents to return.")
     * @FOS\View(
     *  templateVar="incidents"
     * )
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getIncidentsAction(Request $request, ParamFetcherInterface $paramFetcher) {

        return $this->getApiController()->getAll($request, $paramFetcher);
    }

    /**
     * Get single Incident.
     *
     * @ApiDoc(
     *   resource = true,
     *   output = "CertUnlp\NgenBundle\Entity\Incident",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the incident is not found"
     *   }
     * )
     * @FOS\View(
     *  templateVar="incident"
     * )
     * @param int     $id      the incident id
     *
     * @return array
     *
     * @throws NotFoundHttpException when page not exist
     * 
     */
    public function getIncidentAction(Incident $incident) {
        return $incident;
    }

    /**
     * Create a Incident from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new incident from the submitted data.",
     *   input = "CertUnlp\NgenBundle\Form\IncidentType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     * @FOS\View(
     *  templateVar="incidents"
     * )
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postIncidentAction(Request $request) {

        return $this->getApiController()->post($request);
    }

    /**
     * Update existing incident from the submitted data or create a new incident at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "CertUnlp\NgenBundle\Form\IncidentType",
     *   statusCodes = {
     *     201 = "Returned when the IncidentInterface is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the incident id
     * @FOS\View(
     *  templateVar="incidents"
     * )
     * @return FormTypeInterface|View
     * @FOS\Put("/{ip}/{date}/{type}")
     * @throws NotFoundHttpException when incident not exist
     */
    public function putIncidentAction(Request $request) {
        return $this->getApiController()->put($request, $incident);
    }

    /**
     * Update existing incident from the submitted data or create a new incident at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     * @FOS\View(
     *  templateVar="incidents"
     * )
     * @param Request $request the request object
     * @param int     $id      the incident id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when incident not exist
     */
    public function patchIncidentStateAction(Request $request, Incident $incident, IncidentState $state) {

        return $this->getApiController()->patchState($request, $incident, $state);
    }

    /**
     * Update existing incident from the submitted data or create a new incident at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the incident id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when incident not exist
     * 
     * @FOS\Patch("/{ip}/{date}/{type}/states/{state}")
     * @ParamConverter("incident", class="CertUnlpNgenBundle:Incident", options={"repository_method" = "findByHostDateType"})
     * @FOS\View(
     *  templateVar="incidents"
     * )   
     * @FOS\QueryParam(name="state",strict=true ,requirements="open|closed|closed_by_inactivity|removed|unresolved|stand_by")
     * @FOS\QueryParam(name="date",strict=true ,requirements="yyyy-MM-dd", description="If no date is selected, the date will be today.")
     * @FOS\QueryParam(name="type",strict=true ,requirements="blacklist|botnet|bruteforce|bruteforcing_ssh|copyright|deface|dns_zone_transfer|dos_chargen|dos_ntp|dos_snmp|heartbleed|malware|open_dns open_ipmi|open_memcached|open_mssql|open_netbios|open_ntp_monitor|open_ntp_version|open_snmp|open_ssdp|phishing|poodle|scan|shellshock|spam", description="The incident type")
     * @FOS\QueryParam(name="ip",strict=true ,requirements="[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}", description="The host IP.")
     */
    public function patchIncidentStateWithParamsAction(Request $request, Incident $incident, IncidentState $state) {
        return $this->getApiController()->patchState($request, $incident, $state);
    }

    /**
     * Get single .
     *
     * @ApiDoc(
     *   resource = true,
     *   output = "CertUnlp\NgenBundle\Entity\Incident",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the incident is not found"
     *   }
     * )
     *
     * @param int     $id      the incident id
     *
     * @return array
     *
     * @throws NotFoundHttpException when page not exist
     * @Fos\Get("/{ip}/{date}/{type}")
     * @FOS\View(
     *  templateVar="incidents"
     * )
     * @ParamConverter("incident", class="CertUnlpNgenBundle:Incident", options={"repository_method" = "findByHostDateType"})
     * @FOS\QueryParam(name="date",strict=true ,requirements="yyyy-MM-dd", description="If no date is selected, the date will be today.")
     * @FOS\QueryParam(name="type",strict=true ,requirements="blacklist|botnet|bruteforce|bruteforcing_ssh|copyright|deface|dns_zone_transfer|dos_chargen|dos_ntp|dos_snmp|heartbleed|malware|open_dns open_ipmi|open_memcached|open_mssql|open_netbios|open_ntp_monitor|open_ntp_version|open_snmp|open_ssdp|phishing|poodle|scan|shellshock|spam", description="The incident type")
     * @FOS\QueryParam(name="ip",strict=true ,requirements="[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}", description="The host IP.")
     */
    public function getIncidentWithParamsAction(Incident $incident) {
        return $incident;
    }

    /**
     * Update existing incident from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "CertUnlp\NgenBundle\Form\IncidentType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(
     *  templateVar="incidents"
     * )
     * @param Request $request the request object
     * @param int     $id      the incident id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when incident not exist
     */
    public function patchIncidentAction(Request $request, Incident $incident) {
        return $this->getApiController()->patch($request, $incident);
    }

    /**
     * Update existing incident from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "CertUnlp\NgenBundle\Form\IncidentType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     *
     * @param Request $request the request object
     * @param int     $id      the incident id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when incident not exist
     * @FOS\Patch("/{ip}/{date}/{type}")
     * @FOS\View(
     *  templateVar="incidents"
     * )
     * @ParamConverter("incident", class="CertUnlpNgenBundle:Incident", options={"repository_method" = "findByHostDateType"})
     * @FOS\QueryParam(name="date",strict=true ,requirements="yyyy-MM-dd", description="If no date is selected, the date will be today.")
     * @FOS\QueryParam(name="type",strict=true ,requirements="blacklist|botnet|bruteforce|bruteforcing_ssh|copyright|deface|dns_zone_transfer|dos_chargen|dos_ntp|dos_snmp|heartbleed|malware|open_dns open_ipmi|open_memcached|open_mssql|open_netbios|open_ntp_monitor|open_ntp_version|open_snmp|open_ssdp|phishing|poodle|scan|shellshock|spam", description="The incident type")
     * @FOS\QueryParam(name="ip",strict=true ,requirements="[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}", description="The host IP.")
     */
    public function patchIncidentWithParamsAction(Request $request, Incident $incident) {

        return $this->getApiController()->patch($request, $incident);
    }

    /**
     * Update existing incident from the submitted data or create a new incident at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "CertUnlp\NgenBundle\Form\IncidentType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     * @FOS\View(
     *  templateVar="incidents"
     * )
     * @param Request $request the request object
     * @param int     $incident      the incident id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when incident not exist
     */
    public function deleteIncidentAction(Request $request, Incident $incident) {
        return $this->getApiController()->delete($request, $incident);
    }

}
