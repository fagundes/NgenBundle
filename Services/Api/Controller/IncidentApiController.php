<?php

/*
 * This file is part of the Ngen - CSIRT Incident Report System.
 *
 * (c) CERT UNLP <support@cert.unlp.edu.ar>
 *
 * This source file is subject to the GPL v3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CertUnlp\NgenBundle\Services\Api\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormTypeInterface;
use CertUnlp\NgenBundle\Exception\InvalidFormException;
use CertUnlp\NgenBundle\Services\Api\Controller\ApiController;
use CertUnlp\NgenBundle\Entity\IncidentType;

class IncidentApiController extends ApiController {

    public function __construct($handler, $viewHandler, $view, $mailer) {
        parent::__construct($handler, $viewHandler, $view);
        $this->mailer = $mailer;
    }

    /**
     * Create a Object from the submitted data.
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function post(Request $request) {
        //TODO: refactoring aca o algo, poqnomegusta

        try {
            $object_data = array_merge($request->request->all(), $request->files->all());
            $hostAddresses = explode(',', $object_data['hostAddress']);
            if (count($hostAddresses) > 1) {
                $new_incidents = [];
                foreach ($hostAddresses as $hostAddress) {
                    $object_data['hostAddress'] = $hostAddress;
                    $new_incidents[] = $this->getCustomHandler()->post($object_data);
                }


                return $this->response([$new_incidents], Response::HTTP_CREATED);
            } else {

                $newObject = $this->getCustomHandler()->post($object_data);

                return $this->response([$newObject], Response::HTTP_CREATED);
            }
        } catch (InvalidFormException $exception) {
            return $exception->getForm();
        }
    }

    /**
     * Prints a mail template for the given incident.
     *
     * @param int     $id      the incident id
     *
     * @return array
     */
    public function reportHtmlAction($slug) {
        $data = array('state' => $slug);
        $this->getView()->setTemplate('CertUnlpNgenBundle:Incident:Report/Twig/incidentReportHtml.html.twig');
        $this->getView()->setTemplateData($data);
//        $this->getView()->setData($data);
//        $data['html'] = $this->viewHandler->renderTemplate($this->getView(), 'html');
//        var_dump($this->getView());die;
//        die;
        return $this->handle();
    }

    /**
     * Prints a mail template for the given incident.
     * @param int     $id      the incident id
     *
     * @return array
     */
    public function reportMailAction($incident) {
        return new Response($this->mailer->send_report($incident, null, true));
    }

}
