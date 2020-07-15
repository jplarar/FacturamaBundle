<?php

namespace Jplarar\FacturamaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('jplarar_facturama');

        $rootNode
            ->children()
            ->scalarNode('facturama_username')->defaultValue(null)->end()
            ->scalarNode('facturama_password')->defaultValue(null)->end()
            ->scalarNode('facturama_serie')->defaultValue(null)->end()
            ->scalarNode('facturama_currency')->defaultValue(null)->end()
            ->scalarNode('facturama_expedition_place')->defaultValue(null)->end()
            ->scalarNode('facturama_cfdi_use')->defaultValue(null)->end()
            ->scalarNode('facturama_payment_form')->defaultValue(null)->end()
            ->scalarNode('facturama_product_code')->defaultValue(null)->end()
            ->scalarNode('facturama_unit_code')->defaultValue(null)->end()
            ->scalarNode('facturama_taxes')->defaultValue(null)->end()
            ->scalarNode('facturama_env')->defaultValue(null)->end()
            ->end();

        return $treeBuilder;
    }
}
