<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\UX\Dropzonemultiple\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\UX\Dropzonemultiple\Form\DropzonemultipleType;
use Symfony\UX\Dropzonemultiple\Tests\Kernel\TwigAppKernel;
use Twig\Environment;

/**
 * @author Titouan Galopin <galopintitouan@gmail.com>
 *
 * @internal
 */
class DropzonemultipleTypeTest extends TestCase
{
    public function testRenderForm()
    {
        $kernel = new TwigAppKernel('test', true);
        $kernel->boot();
        $container = $kernel->getContainer()->get('test.service_container');

        $form = $container->get(FormFactoryInterface::class)->createBuilder()
            ->add('photo', DropzonemultipleType::class, ['attr' => ['data-controller' => 'mydropzone']])
            ->getForm()
        ;

        $rendered = $container->get(Environment::class)->render('dropzonemultiple_form.html.twig', ['form' => $form->createView()]);

        $this->assertSame(
            '<form name="form" method="post" enctype="multipart/form-data"><div id="form"><div><label for="form_photo" class="required">Photo</label><div class="dropzonemultiple-container" data-controller="mydropzone symfony--ux-dropzonemultiple--dropzonemultiple">
        <input type="file" id="form_photo" name="form[photo]" required="required" data-controller="" class="dropzonemultiple-input" data-symfony--ux-dropzonemultiple--dropzonemultiple-target="input" />

        <div class="dropzonemultiple-placeholder" data-symfony--ux-dropzonemultiple--dropzonemultiple-target="placeholder"></div>

        <div class="dropzonemultiple-preview" data-symfony--ux-dropzonemultiple--dropzonemultiple-target="preview" style="display: none">
            <button class="dropzonemultiple-preview-button" type="button"
                    data-symfony--ux-dropzonemultiple--dropzonemultiple-target="previewClearButton"></button>

            <div class="dropzonemultiple-preview-image" style="display: none"
                 data-symfony--ux-dropzonemultiple--dropzonemultiple-target="previewImage"></div>

            <div data-symfony--ux-dropzonemultiple--dropzonemultiple-target="previewFilename" class="dropzonemultiple-preview-filename"></div>
        </div>
    </div></div></div></form>
',
            str_replace(' >', '>', $rendered)
        );
    }
}
