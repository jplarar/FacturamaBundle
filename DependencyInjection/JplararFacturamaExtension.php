<?php

namespace Jplarar\FacturamaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class JplararFacturamaExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $parameters = [
            'facturama_username',
            'facturama_password',
            'serie',
            'currency',
            'expedition_place',
            'cfdi_use',
            'payment_form',
            'product_code',
            'unit_code',
            'taxes',
            'env'
        ];

        foreach ($parameters as $parameter) {
            if (!isset($config[$parameter])) {
                throw new \InvalidArgumentException('The option "jplarar_facturama.'.$parameter.'" must be set.');
            }
            $container->setParameter('jplarar_facturama.'.$parameter, $config[$parameter]);
        }
    }

    /**
     * {@inheritdoc}
     * @version 0.0.1
     * @since 0.0.1
     */
    public function getAlias()
    {
        return 'jplarar_facturama';
    }
}
