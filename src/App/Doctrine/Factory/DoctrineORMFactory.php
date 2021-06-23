<?php

declare(strict_types=1);

namespace App\Doctrine\Factory;

use Doctrine\Common\{
    Annotations\AnnotationReader
};
use Doctrine\ORM\{Configuration,
    EntityManager,
    Mapping\UnderscoreNamingStrategy,
    Mapping\Driver\AnnotationDriver,
    ORMException
};
use PSR\Container\ContainerInterface;

class DoctrineORMFactory
{
    /**
     * @param ContainerInterface $container
     * @return EntityManager
     * @throws ORMException
     */
    public function __invoke(ContainerInterface $container): EntityManager
    {
        $config = $container->has('config') ? $container->get('config') : [];

        $proxyDir = (isset($config['doctrine']['connection']['orm']['proxy_dir'])) ? $config['doctrine']['connection']['orm']['proxy_dir'] : 'data/cache/EntityProxy';
        $proxyNamespace = (isset($config['doctrine']['connection']['orm']['proxy_namespace'])) ? $config['doctrine']['connection']['orm']['proxy_namespace'] : 'EntityProxy';
        $autoGenerateProxyClasses = (isset($config['doctrine']['connection']['orm']['auto_generate_proxy_classes'])) ? $config['doctrine']['connection']['orm']['auto_generate_proxy_classes'] : true;
        $underscoreNamingStrategy = (isset($config['doctrine']['connection']['orm']['underscore_naming_strategy'])) ? $config['doctrine']['connection']['orm']['underscore_naming_strategy'] : true;

        $doctrine = new Configuration();
        $doctrine->setProxyDir($proxyDir);
        $doctrine->setProxyNamespace($proxyNamespace);
        $doctrine->setAutoGenerateProxyClasses($autoGenerateProxyClasses);

        if ($underscoreNamingStrategy) {
            $doctrine->setNamingStrategy(new UnderscoreNamingStrategy());
        }

        $driver = new AnnotationDriver(
            new AnnotationReader(),
            [__DIR__ . '/../../../src/Entity']
        );

        $doctrine->setMetadataDriverImpl($driver);

        return EntityManager::create($config['doctrine']['connection']['orm_default'], $doctrine);
    }
}