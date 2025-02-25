<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace leomarcel\UX\Dropzonemultiple\DependencyInjection;

use leomarcel\UX\Dropzonemultiple\Form\DropzonemultipleType;
use Symfony\Component\AssetMapper\AssetMapperInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Titouan Galopin <galopintitouan@gmail.com>
 *
 * @internal
 */
class DropzonemultipleExtension extends Extension implements PrependExtensionInterface
{
  public function prepend(ContainerBuilder $container)
  {
    // Register the Dropzonemultiple form theme if TwigBundle is available
    $bundles = $container->getParameter('kernel.bundles');

//    if (isset($bundles['TwigBundle'])) {
      $container->prependExtensionConfig('twig', ['form_themes' => ['@Dropzonemultiple/form_theme.html.twig']]);

    if ($this->isAssetMapperAvailable($container)) {
      $container->prependExtensionConfig('framework', [
        'asset_mapper' => [
          'paths' => [
            __DIR__.'/../../assets/dist' => '@leomarcel/ux-dropzonemultiple',
          ],
        ],
      ]);
    }
  }

  public function load(array $configs, ContainerBuilder $container)
  {
    $container
      ->setDefinition('form.dropzonemultiple', new Definition(DropzonemultipleType::class))
      ->addTag('form.type')
      ->setPublic(false)
    ;
  }

  private function isAssetMapperAvailable(ContainerBuilder $container): bool
  {
    if (!interface_exists(AssetMapperInterface::class)) {
      return false;
    }

    // check that FrameworkBundle 6.3 or higher is installed
    $bundlesMetadata = $container->getParameter('kernel.bundles_metadata');
    if (!isset($bundlesMetadata['FrameworkBundle'])) {
      return false;
    }

    return is_file($bundlesMetadata['FrameworkBundle']['path'].'/Resources/config/asset_mapper.php');
  }
}
