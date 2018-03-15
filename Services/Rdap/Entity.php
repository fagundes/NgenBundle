<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CertUnlp\NgenBundle\Services\Rdap;

/**
 * Description of Entity
 *
 * @author dam
 */
class Entity
{
    private $object;
    private $entities;

    public function __construct($entity_object)
    {
        $this->setObject($entity_object);
        if (isset($this->getObject()->entities)) {
            foreach ($this->getObject()->entities as $entity) {

                $this->entities[] = new Entity($entity);
            }
        } else {
            $this->entities = [];
        }
    }

    public function __toString()
    {
        return $this->getObject()->name . "( " . $this->getObject()->handle . " )";
    }

    public function getVcard()
    {
        if (isset($this->getObject()->vcardArray)) {
            return $this->getObject()->vcardArray[1];
        }
        return [];
    }

    /**
     * @return array
     */
    public function getLinks()
    {
        if (isset($this->getObject()->links)) {
            return $this->getObject()->links;
        }
        return [];
    }

    public function getSelfLink()
    {
        if ($this->getLinks()) {
            return array_filter(
                $this->getLinks(), function ($e) {
                return $e->rel == "self";
            }
            )[0]->href;
        }
        return [];
    }

    public function getRoles()
    {
        if (isset($this->getObject()->roles)) {
            return $this->getObject()->roles;
        }
        return [];
    }

    public function getRolesAsString()
    {
        $string = "";
        if (isset($this->getObject()->roles)) {
            foreach ($this->getObject()->roles as $role) {
                $string .= "$role ";
            }
        }
        return $string;
    }

    /**
     * @return array
     */
    public function getHandle()
    {
        if (isset($this->getObject()->handle)) {
            return $this->getObject()->handle;
        }
        return [];
    }

    public function getVcardElement($element)
    {
        $elements = [];
        foreach ($this->getVcard() as $vcard) {
            if ($vcard[0] == $element) {
                $elements[] = $vcard[sizeof($vcard) - 1];
            }
        }
        return $elements;
    }

    public function getEmails()
    {
        return $this->getVcardElement('email');
    }

    public function getName()
    {
        return $this->getVcardElement('fn')[0];
    }

    public function getOrganization()
    {
        return $this->getVcardElement('org');
    }

    public function getPhone()
    {
        return $this->getVcardElement('tel');
    }

    public function getEntities($callback = '')
    {
        $entities = [];
        if ($callback) {
            $entities[] = $callback($this);
        } else {
            $entities[] = $this;
        }
        foreach ($this->entities as $entity) {
            $entities = array_merge($entities, $entity->getEntities($callback));
        }
        return $entities;
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param mixed $object
     * @return Entity
     */
    public function setObject($object)
    {
        $this->object = $object;
        return $this;
    }

}
