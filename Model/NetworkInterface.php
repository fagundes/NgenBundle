<?php

/*
 * This file is part of the Ngen - CSIRT Incident Report System.
 *
 * (c) CERT UNLP <support@cert.unlp.edu.ar>
 *
 * This source file is subject to the GPL v3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CertUnlp\NgenBundle\Model;

use CertUnlp\NgenBundle\Entity\Network\Network;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author demyen
 */
interface NetworkInterface {

    /**
     * Get ipMask
     *
     * @return string 
     */
    public function getIp();

    /**
     * Get ipMask
     *
     * @return string
     */
    public function getIpMask();

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Network
     */
    public function setIsActive($isActive);

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive();

    /**
     * Get networkAdmin
     *
     * @return \CertUnlp\NgenBundle\Entity\Network\NetworkAdmin 
     */
    public function getNetworkAdmin();

    /**
     * Get NetworkEntity
     *
     * @return \CertUnlp\NgenBundle\Entity\Network\NetworkEntity 
     */
    public function getNetworkEntity();

//    /**
//     * Get incidents
//     *
//     * @return \Doctrine\Common\Collections\Collection
//     */
//    public function getIncidents();

    public function equals(NetworkInterface $other);
}
