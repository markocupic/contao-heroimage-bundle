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

use Markocupic\ContaoHeroimageBundle\Controller\ContentElement\HeroimageElementController;

/**
 * Content element
 */
$GLOBALS['TL_LANG']['CTE']['image_elements'] = 'Image elements';
$GLOBALS['TL_LANG']['CTE'][HeroimageElementController::TYPE] = ['Hero Image (banner picture winth headline, text and link button)', 'Insert a hero image with a headline, some text and a button as a content element.'];
