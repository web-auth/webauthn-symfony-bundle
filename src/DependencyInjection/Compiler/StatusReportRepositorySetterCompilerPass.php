<?php

declare(strict_types=1);

namespace Webauthn\Bundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\MetadataService\StatusReportRepository;

final class StatusReportRepositorySetterCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (! $container->hasAlias(StatusReportRepository::class)) {
            return;
        }

        $this->setLoggerToServiceDefinition($container, AuthenticatorAttestationResponseValidator::class);
    }

    private function setLoggerToServiceDefinition(ContainerBuilder $container, string $service): void
    {
        if (! $container->hasDefinition($service)) {
            return;
        }

        $definition = $container->getDefinition($service);
        $definition->addMethodCall('setStatusReportRepository', [new Reference(StatusReportRepository::class)]);
    }
}
