<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace leomarcel\UX\Dropzonemultiple\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Titouan Galopin <galopintitouan@gmail.com>
 *
 * @final
 */
class DropzonemultipleType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
      $resolver->setDefaults([
        'attr' => [
          'placeholder' => 'Drag and drop or browse',
        ],
      ]);
    }

    public function getParent()
    {
        return FileType::class;
    }

    public function getBlockPrefix()
    {
        return 'dropzonemultiple';
    }
}
