<?php

/*
 * This file is part of the Ngen - CSIRT Incident Report System.
 *
 * (c) CERT UNLP <support@cert.unlp.edu.ar>
 *
 * This source file is subject to the GPL v3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CertUnlp\NgenBundle\Services\Api\Handler;

use CertUnlp\NgenBundle\Exception\InvalidFormException;
use CertUnlp\NgenBundle\Model\ApiHandlerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;

abstract class Handler implements ApiHandlerInterface
{

    protected $om;
    protected $entityClass;
    protected $repository;
    protected $formFactory;
    protected $entityType;

    public function __construct(ObjectManager $om, string $entityClass, string $entityType, FormFactoryInterface $formFactory)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
        $this->entityType = $entityType;
    }

    /**
     * Get a Entity by id.
     *
     * @param array $parameters
     * @return Entity
     */
    public function getOrCreate(array $parameters)
    {
        $entity = $this->get($parameters);
        if (!$entity) {
            $entity = $this->createEntityInstance($parameters);
        }
        return $entity;
    }

    /**
     * Get a Entity by id.
     *
     * @param array $parameters
     * @return Entity
     */
    public function get(array $parameters)
    {
        return $this->repository->findOneBy($parameters);
    }

    public function createEntityInstance($parameters = [])
    {
        if ($parameters) {
            try {
                $refMethod = new \ReflectionMethod($this->entityClass, '__construct');
            } catch (\ReflectionException $e) {
                var_dump($e);
                die;
            }
            $params = $refMethod->getParameters();
            $re_args = array();

            foreach ($params as $params_value) {
                foreach ($params_value as $name => $param_name) {
                    $re_args[$param_name] = $parameters[$param_name];
                }
            }

            try {
                $refClass = new \ReflectionClass($this->entityClass);
            } catch (\ReflectionException $e) {
                var_dump($e);
                die;
            }
            $entity = $refClass->newInstanceArgs((array)$re_args);
        } else {
            $entity = new $this->entityClass();
        }
        return $entity;
    }

    /**
     * Get a list of entities.
     *
     * @param array $params
     * @param array $order
     * @param int $limit the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all(array $params = array(), $order = array(), $limit = null, $offset = null)
    {
        return $this->repository->findBy($params, $order, $limit, $offset);
    }

    /**
     * Create a new Entity.
     *
     * @param array $parameters
     *
     * @param bool $csrf_protection
     * @return Entity
     */
    public function post(array $parameters, $csrf_protection = false)
    {
        $entity_class_instance = $this->createEntityInstance($parameters);

        return $this->processForm($entity_class_instance, $parameters, 'POST', $csrf_protection);
    }

    /**
     * Processes the form.
     *
     * @param Entity $entity_class_instance
     * @param array $parameters
     * @param String $method
     *
     * @param bool $csrf_protection
     * @return Entity
     *
     */
    protected function processForm($entity_class_instance, $parameters, $method = "PUT", $csrf_protection = true)
    {

        $form = $this->formFactory->create(new $this->entityType(), $entity_class_instance, array('csrf_protection' => $csrf_protection, 'method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);

        if ($form->isValid()) {
            $entity_class_instance = $form->getData();

            $entity_class_instance = $this->checkIfExists($entity_class_instance, $method);
            $this->om->persist($entity_class_instance);
            $this->om->flush($entity_class_instance);

            return $entity_class_instance;
        }
        throw new InvalidFormException
        ('Invalid submitted data', $form);
    }

    abstract protected function checkIfExists($entity_class_instance, $method);

    /**
     * Edit a Entity.
     *
     * @param Entity $entity_class_instance
     * @param array $parameters
     *
     * @return Entity
     */
    public function put($entity_class_instance, array $parameters)
    {
        return $this->processForm($entity_class_instance, $parameters, 'PUT', false);
    }

    /**
     * Delete a Network.
     *
     * @param $entity
     * @param array $parameters
     *
     * @return Entity
     */
    public function desactivate($entity, array $parameters = null)
    {
        return $this->delete($entity, $parameters);
    }

    /**
     * Delete a Entity.
     *
     * @param Entity $entity_class_instance
     * @param array $parameters
     *
     * @return Entity
     */
    public function delete($entity_class_instance, array $parameters = null)
    {
        $this->prepareToDeletion($entity_class_instance, $parameters);
        return $this->patch($entity_class_instance, $parameters);
    }

    /**
     * Prepare to delete a Entity.
     *
     * @param Entity $entity_class_instance
     * @param array $parameters
     *
     * @return Entity
     */
    abstract protected function prepareToDeletion($entity_class_instance, array $parameters);

    /**
     * Partially update a Entity.
     *
     * @param Entity $entity_class_instance
     * @param array $parameters
     *
     * @return Entity
     */
    public function patch($entity_class_instance, array $parameters = null)
    {
        return $this->processForm($entity_class_instance, $parameters, 'PATCH', false);
    }

    /**
     * Delete a Network.
     *
     * @param $entity
     * @param array $parameters
     *
     * @return Entity
     */
    public function activate($entity, array $parameters = null)
    {
        $entity->setIsActive(TRUE);
        return $this->patch($entity, $parameters);
    }

}
