<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace leomarcel\UX\Dropzonemultiple;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Titouan Galopin <galopintitouan@gmail.com>
 *
 * @final
 */
class DropzonemultipleBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
