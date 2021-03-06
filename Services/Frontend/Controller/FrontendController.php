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

use Symfony\Component\HttpFoundation\Request;

class FrontendController {

    public function __construct($doctrine, $formFactory, $entityType, $paginator, $finder, $comment_manager, $thread_manager) {
        $this->doctrine = $doctrine;
        $this->paginator = $paginator;
        $this->finder = $finder;
        $this->entityType = $entityType;
        $this->formFactory = $formFactory;
        $this->comment_manager = $comment_manager;
        $this->thread_manager = $thread_manager;
    }

    public function getDoctrine() {
        return $this->doctrine;
    }

    public function getFinder() {
        return $this->finder;
    }

    public function getPaginator() {
        return $this->paginator;
    }

    public function homeEntity(Request $request, $term = '') {
        return $this->searchEntity($request, $term);
    }

    public function searchEntity(Request $request, $term = null) {
        if (!$term) {
            $term = $request->get('term') ? $request->get('term') : '*';
        }
        $results = $this->getFinder()->createPaginatorAdapter($term);

        $pagination = $this->getPaginator()->paginate(
                $results, $request->query->get('page', 1), 7
                , array('defaultSortFieldName' => 'createdAt', 'defaultSortDirection' => 'desc')
        );

        $pagination->setParam('term', $term);

        return array('objects' => $pagination, 'term' => $term);
    }

    public function newEntity(Request $request) {
        return array('form' => $this->formFactory->create(new $this->entityType())->createView(), 'method' => 'POST');
    }

    public function editEntity($object) {

        return array('form' => $this->formFactory->create(new $this->entityType(), $object)->createView(), 'method' => 'patch');
    }

    public function detailEntity($object) {
        return array('object' => $object);
    }

    public function commentsEntity($object, Request $request) {
        $id = $object->getId();
        $thread = $this->thread_manager->findThreadById($id);
        if (null === $thread) {
            $thread = $this->thread_manager->createThread();
            $thread->setId($id);
            $object->setCommentThread($thread);
            $thread->setIncident($object);
            $thread->setPermalink($request->getUri());

            // Add the thread
            $this->thread_manager->saveThread($thread);
        }

        $comments = $this->comment_manager->findCommentTreeByThread($thread);

        return array(
            'comments' => $comments,
            'thread' => $thread,
        );
    }

}
