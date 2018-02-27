<?php

/*
 * This file is part of the Ngen - CSIRT Incident Report System.
 *
 * (c) CERT UNLP <support@cert.unlp.edu.ar>
 *
 * This source file is subject to the GPL v3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CertUnlp\NgenBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class CertUnlpNgenExtension extends Extension {

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container) {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('cert_unlp.ngen.team.mail', $config['team']['mail']);
        $container->setParameter('cert_unlp.ngen.resources.path', '%kernel.root_dir%/Resources/');

        $this->setHostParameter($container, $config);
        $this->setIncidentParameter($container, $config);
        $this->setFeedParameter($container, $config);
        $this->setStateParameter($container, $config);
        $this->setFeedShadowserverParameter($container, $config);
        $this->setNetworkParameter($container, $config);
        $this->setNetworkEntityParameter($container, $config);
        $this->setNetworkAdminParameter($container, $config);
        $this->setTypeParameter($container, $config);
        $this->setTypeReportParameter($container, $config);
        $this->setUserParameter($container, $config);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

    private function setHostParameter($container, $config) {

        $container->setParameter('cert_unlp.ngen.host.internal.class', $config['hosts']['internal']['class']);
        $container->setParameter('cert_unlp.ngen.host.external.class', $config['hosts']['external']['class']);
        $container->setParameter('cert_unlp.ngen.host.internal.handler.class', $config['hosts']['internal']['handler']['class']);
        $container->setParameter('cert_unlp.ngen.host.external.handler.class', $config['hosts']['external']['handler']['class']);
        $container->setParameter('cert_unlp.ngen.host.internal.form_type.class', $config['hosts']['internal']['form_type']['class']);
        $container->setParameter('cert_unlp.ngen.host.external.form_type.class', $config['hosts']['external']['form_type']['class']);
        $container->setParameter('cert_unlp.ngen.host.factory.class', $config['hosts']['factory']['class']);
    }

    private function setIncidentParameter($container, $config) {

        $container->setParameter('cert_unlp.ngen.incident.class', $config['incidents']['class']);
        $container->setParameter('cert_unlp.ngen.incident.handler.class', $config['incidents']['handler']['class']);
        $container->setParameter('cert_unlp.ngen.incident.delegator_chain.class', $config['incidents']['delegator_chain']['class']);
        $container->setParameter('cert_unlp.ngen.incident.form_type.class', $config['incidents']['form_type']['class']);
        $container->setParameter('cert_unlp.ngen.incident.mailer.class', $config['incidents']['mailer']['class']);

        if (isset($config['incidents']['mailer']['sender_address'])) {
            $container->setParameter('cert_unlp.ngen.incident.mailer.sender_address', $config['incidents']['mailer']['sender_address']);
        } else {
            $container->setParameter('cert_unlp.ngen.incident.mailer.sender_address', $container->getParameter('cert_unlp.ngen.incident.mailer.sender_address'));
        }

        $container->setParameter('cert_unlp.ngen.incident.evidence.path', $config['incidents']['evidence']['path']);
        $container->setParameter('cert_unlp.ngen.incident.evidence.prefix', $config['incidents']['evidence']['prefix']);
        $container->setParameter('cert_unlp.ngen.incident.reporter.class', $config['incidents']['reporter']['class']);
        $container->setParameter('cert_unlp.ngen.incident.mailer.host', $config['incidents']['mailer']['host']);
        $container->setParameter('cert_unlp.ngen.incident.mailer.transport', $config['incidents']['mailer']['transport']);
        $container->setParameter('cert_unlp.ngen.incident.mailer.username', $config['incidents']['mailer']['username']);
        $container->setParameter('cert_unlp.ngen.incident.mailer.password', $config['incidents']['mailer']['password']);
    }

    private function setFeedParameter($container, $config) {
        $container->setParameter('cert_unlp.ngen.incident.feed.class', $config['incidents']['feeds']['class']);
        $container->setParameter('cert_unlp.ngen.incident.feed.handler.class', $config['incidents']['feeds']['handler']['class']);
        $container->setParameter('cert_unlp.ngen.incident.feed.form_type.class', $config['incidents']['feeds']['form_type']['class']);
    }

    private function setStateParameter($container, $config) {
        $container->setParameter('cert_unlp.ngen.incident.state.class', $config['incidents']['states']['class']);
        $container->setParameter('cert_unlp.ngen.incident.state.handler.class', $config['incidents']['states']['handler']['class']);
        $container->setParameter('cert_unlp.ngen.incident.state.form_type.class', $config['incidents']['states']['form_type']['class']);
    }

    private function setFeedShadowserverParameter($container, $config) {
        $container->setParameter('cert_unlp.ngen.feed.path', $config['feeds']['path']);
        $container->setParameter('cert_unlp.ngen.feed.shadowserver.enabled', $config['feeds']['shadowserver']['enabled']);
        $container->setParameter('cert_unlp.ngen.feed.shadowserver.class', $config['feeds']['shadowserver']['class']);
        $container->setParameter('cert_unlp.ngen.feed.shadowserver.client.class', $config['feeds']['shadowserver']['client']['class']);
        $container->setParameter('cert_unlp.ngen.feed.shadowserver.client.url', $config['feeds']['shadowserver']['client']['url']);
        $container->setParameter('cert_unlp.ngen.feed.shadowserver.client.user', $config['feeds']['shadowserver']['client']['user']);
        $container->setParameter('cert_unlp.ngen.feed.shadowserver.client.password', $config['feeds']['shadowserver']['client']['password']);
    }

    private function setNetworkParameter($container, $config) {
        $container->setParameter('cert_unlp.ngen.network.class', $config['networks']['class']);
        $container->setParameter('cert_unlp.ngen.network.default_network', $config['networks']['default_network']);
        $container->setParameter('cert_unlp.ngen.network.handler.class', $config['networks']['handler']['class']);
        $container->setParameter('cert_unlp.ngen.network.validator.class', $config['networks']['validator']['class']);
        $container->setParameter('cert_unlp.ngen.network.form_type.class', $config['networks']['form_type']['class']);
    }

    private function setNetworkEntityParameter($container, $config) {
        $container->setParameter('cert_unlp.ngen.network_entity.class', $config['network_entity']['class']);
        $container->setParameter('cert_unlp.ngen.network_entity.handler.class', $config['network_entity']['handler']['class']);
        $container->setParameter('cert_unlp.ngen.network_entity.form_type.class', $config['network_entity']['form_type']['class']);
    }

    private function setNetworkAdminParameter($container, $config) {
        $container->setParameter('cert_unlp.ngen.network.admin.class', $config['networks']['admin']['class']);
        $container->setParameter('cert_unlp.ngen.network.admin.handler.class', $config['networks']['admin']['handler']['class']);
        $container->setParameter('cert_unlp.ngen.network.admin.form_type.class', $config['networks']['admin']['form_type']['class']);
    }

    private function setTypeParameter($container, $config) {
        $container->setParameter('cert_unlp.ngen.incident.type.class', $config['incidents']['types']['class']);
        $container->setParameter('cert_unlp.ngen.incident.type.handler.class', $config['incidents']['types']['handler']['class']);
        $container->setParameter('cert_unlp.ngen.incident.type.form_type.class', $config['incidents']['types']['form_type']['class']);
    }

    private function setTypeReportParameter($container, $config) {
        $container->setParameter('cert_unlp.ngen.incident.type.report.class', $config['incidents']['types']['reports']['class']);
        $container->setParameter('cert_unlp.ngen.incident.type.report.handler.class', $config['incidents']['types']['reports']['handler']['class']);
        $container->setParameter('cert_unlp.ngen.incident.type.report.form_type.class', $config['incidents']['types']['reports']['form_type']['class']);
    }

    private function setUserParameter($container, $config) {
        $container->setParameter('cert_unlp.ngen.user.class', $config['users']['class']);
        $container->setParameter('cert_unlp.ngen.user.handler.class', $config['users']['handler']['class']);
        $container->setParameter('cert_unlp.ngen.user.form_type.class', $config['users']['form_type']['class']);
    }

}
