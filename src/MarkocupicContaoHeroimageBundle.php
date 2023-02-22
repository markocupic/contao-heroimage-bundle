<?php

declare(strict_types=1);

/*
 * This file is part of Contao Hero Image Bundle.
 *
 * (c) Marko Cupic 2023 <m.cupic@gmx.ch>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/contao-heroimage-bundle
 */

namespace Markocupic\ContaoHeroimageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MarkocupicContaoHeroimageBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
