<?php

/**
 * @file
 * Contains \EclipseGc\Plugin\Filter\PluginDefinitionDeriverFilter.
 */

namespace EclipseGc\Plugin\Mutator;

use EclipseGc\Plugin\Derivative\PluginDefinitionDerivativeInterface;
use EclipseGc\Plugin\Derivative\PluginDeriverResolverInterface;
use EclipseGc\Plugin\PluginDefinitionInterface;

class PluginDefinitionDeriverMutator implements PluginDefinitionMutatorInterface {

  /**
   * @var \EclipseGc\Plugin\Derivative\PluginDeriverResolverInterface
   */
  protected $deriverResolver;

  /**
   * PluginDefinitionDeriverMutator constructor.
   *
   * @param \EclipseGc\Plugin\Derivative\PluginDeriverResolverInterface $deriverResolver
   */
  public function __construct(PluginDeriverResolverInterface $deriverResolver) {
    $this->deriverResolver = $deriverResolver;
  }

  /**
   * {@inheritdoc}
   */
  public function mutate(PluginDefinitionInterface ...$definitions) : array {
    $results = [];
    foreach ($definitions as $definition) {
      if ($definition instanceof PluginDefinitionDerivativeInterface) {
        $deriver = $this->deriverResolver->getDeriverInstance($definition->getDeriver());
        foreach ($deriver->getDerivativeDefinitions($definition) as $pluginDefinition) {
          $results[$pluginDefinition->getPluginId()] = $pluginDefinition;
        }
        continue;
      }
      $results[$definition->getPluginId()] = $definition;
    }
    return $results;
  }

}
