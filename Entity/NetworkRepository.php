<?php

/*
 * This file is part of the Ngen - CSIRT Incident Report System.
 *
 * (c) CERT UNLP <support@cert.unlp.edu.ar>
 *
 * This source file is subject to the GPL v3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CertUnlp\NgenBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * NetworkRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NetworkRepository extends EntityRepository {

    public function findByHostAddress($address) {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('n')
                ->from('CertUnlpNgenBundle:Network', 'n')
                ->where($qb->expr()->eq("BIT_AND(INET_ATON(:address),n.numericIpMask)", "n.numericIp"))
                ->andWhere('n.isActive = true')
                ->orderBy("n.ipMask", "DESC");

        $ip = is_array($address) ? $address['ip'] : $address;
        $qb->setParameter('address', $ip);

        $results = $qb->getQuery()->getResult();

        return $results ? $results[0] : null;
    }

}
