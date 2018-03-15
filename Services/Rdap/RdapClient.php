<?php

/*
 * This file is part of the Ngen - CSIRT Incident Report System.
 *
 * (c) CERT UNLP <support@cert.unlp.edu.ar>
 *
 * This source file is subject to the GPL v3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CertUnlp\NgenBundle\Services\Rdap;


/**
 * Description of RdapClient
 *
 * @author dam
 */

use Exception;

class RdapClient
{

    private $response = null;
    private $request_url;

    /**
     * RdapClient constructor.
     * @param string $request_url
     */
    public function __construct(string $request_url = 'https://rdap.arin.net/registry/ip/')
    {
        $this->request_url = $request_url;
    }

    /**
     * @param $url
     * @return RdapResultWrapper|null
     * @throws Exception
     */
    public function request($url)
    {
        try {
            $this->response = new RdapResultWrapper(file_get_contents($url));

            return $this->response;
        } catch (Exception $exc) {
            throw new Exception("Request Limit", 400);
        }
    }

    /**
     * @param $ip
     * @return RdapResultWrapper|null
     * @throws Exception
     */
    public function requestIp($ip)
    {
        try {
            $result_file = $this->request_url . $ip;
            return $this->request($result_file);
        } catch (Exception $exc) {
            throw new Exception("Request Limit", 400);
        }
    }

    /**
     * @param $link
     * @return Entity
     * @throws Exception
     */
    public function requestEntity(string $link): Entity
    {
        try {
            return new Entity(json_decode(file_get_contents($link)));
        } catch (Exception $exc) {
            throw new Exception($exc);
        }
    }

    /**
     * @return RdapResultWrapper
     */
    public function getResponse(): RdapResultWrapper
    {
        return $this->response;
    }

    /**
     * @param null $response
     * @return RdapClient
     */
    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }


}
