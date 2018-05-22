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
 * IncidentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InternalIncidentRepository extends EntityRepository
{

    function findByHostDateType($parameters)
    {
        $query = $this->createQueryBuilder('i')
            ->where('i.type = :type')
            ->andWhere('i.hostAddress = :hostAddress')
            ->andWhere('i.date = :date')
//                ->andWhere('i.isClosed = :closed')
            ->setParameter('type', $parameters['type'])
            ->setParameter('hostAddress', $parameters['hostAddress'])
//                ->setParameter('closed', FALSE)
            ->setParameter('date', $parameters['date']);

        return $query->getQuery()->getOneOrNullResult();
    }

    public function findByUnique($parameters)
    {
        $query = $this->createQueryBuilder('i')
            ->where('i.type = :type')
            ->andWhere('i.hostAddress = :hostAddress')
            ->andWhere('i.isClosed = :closed')
            ->setParameter('type', $parameters['type'])
            ->setParameter('hostAddress', $parameters['hostAddress'])
            ->setParameter('closed', FALSE);

        $incident = $query->getQuery()->getOneOrNullResult();
        if ($incident) {
            $incident->setLastTimeDetected(new \DateTime('now'));
        }
        return [];
    }

    public function findRenotificables()
    {
        $query1 = $this->createQueryBuilder('i')
//            ->select('count(i)')
            ->where('i.isClosed = :closed')
            ->andWhere('DATE_DIFF(:date,i.date) >= 15')
            ->andWhere('DATE_DIFF(:date,i.renotificationDate) = 5')
            ->setParameter('closed', false)
            ->setParameter('date', new \DateTime());

        $query2 = $this->createQueryBuilder('i')
//            ->select('count(i)')
            ->where('i.isClosed = :closed')
            ->andWhere('DATE_DIFF(:date,i.date) >= 15')
            ->andWhere('i.renotificationDate is null')
            ->setParameter('closed', false)
            ->setParameter('date', new \DateTime());

        return $query1->getQuery()->getResult() + $query2->getQuery()->getResult();
    }

}
