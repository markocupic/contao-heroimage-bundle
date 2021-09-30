<?php

declare(strict_types=1);

/*
 * This file is part of Contao Hero Image Bundle.
 *
 * (c) Marko Cupic 2021 <m.cupic@gmx.ch>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/contao-heroimage-bundle
 */

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__.'/../../../../contao/easy-coding-standard/config/self.php');
    $containerConfigurator->import(__DIR__.'/set/header_comment_fixer.php');
};
