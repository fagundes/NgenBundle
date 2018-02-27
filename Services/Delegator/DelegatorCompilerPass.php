<?php

/*
 * This file is part of the Ngen - CSIRT Incident Report System.
 *
 * (c) CERT UNLP <support@cert.unlp.edu.ar>
 *
 * This source file is subject to the GPL v3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CertUnlp\NgenBundle\Services\Delegator;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Description of DelegatorCompilerPass
 *
 * @author dam
 */
class DelegatorCompilerPass implements CompilerPassInterface {

    public function process(ContainerBuilder $container) {
        if (!$container->hasDefinition('cert_unlp.ngen.incident.internal.delegator_chain')) {
            return;
        }

        if (!$container->hasDefinition('cert_unlp.ngen.incident.external.delegator_chain')) {
            return;
        }

        $incident_definition_delegator = $container->getDefinition('cert_unlp.ngen.incident.delegator_chain');
        $host_internal_definition_delegator = $container->getDefinition('cert_unlp.ngen.host.internal.delegator_chain');
        $host_external_definition_delegator = $container->getDefinition('cert_unlp.ngen.host.external.delegator_chain');

        $incident_tagged_services = $container->findTaggedServiceIds('cert_unlp.ngen.incident.delegate');
        $host_internal_tagged_services = $container->findTaggedServiceIds('cert_unlp.ngen.host.internal.delegate');
        $host_external_tagged_services = $container->findTaggedServiceIds('cert_unlp.ngen.host.external.delegate');

        $this->addDelegate($incident_tagged_services, $incident_definition_delegator);
        $this->addDelegate($host_internal_tagged_services, $host_internal_definition_delegator);
        $this->addDelegate($host_external_tagged_services, $host_external_definition_delegator);
    }

    private function addDelegate($tagged_services, $definition_delegator) {
        foreach ($tagged_services as $id => $tags) {
            foreach ($tags as $attributes) {

                $definition_delegator->addMethodCall(
                        'addDelegate', array(new Reference($id), isset($attributes["alias"]) ? $attributes["alias"] : null, isset($attributes["priority"]) ? $attributes["priority"] : null)
                );
            }
        }
    }

}
