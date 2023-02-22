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
 * Content elements
 */
$GLOBALS['TL_DCA']['tl_content']['palettes'][HeroimageElementController::TYPE] = '
{title_legend},name,type,heroImagePreline,heroImageHeadline;
{hero_image_legend:hide},addHeroImage;
{template_legend:hide},customTpl;
{text_legend},heroImageText;
{hero_image_button_legend},heroImageButtonText,heroImageButtonClass,heroImageButtonJumpTo;
{hero_image_background_legend:hide],heroImageBackgroundColor;
{hero_content_box_legend},heroContentboxTextAlign,heroContentboxOpacity;
{protected_legend:hide},protected;
{expert_legend:hide},guests,cssID,space;
{invisible_legend:hide},invisible,start,stop
';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['addHeroImage'] = 'singleSRC,size';
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'addHeroImage';

/**
 * Add fields to tl_content
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['heroImagePreline'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['heroImagePreline'],
    'exclude'   => true,
    'search'    => true,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 200, 'tl_class' => 'w50 clr'],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['heroImageHeadline'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['heroImageHeadline'],
    'exclude'   => true,
    'search'    => true,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 200, 'tl_class' => 'w50 clr'],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['heroImageText'] = [
    'label'       => &$GLOBALS['TL_LANG']['tl_content']['heroImageText'],
    'exclude'     => true,
    'search'      => true,
    'inputType'   => 'textarea',
    'eval'        => ['mandatory' => false, 'rte' => 'tinyMCE', 'helpwizard' => true],
    'explanation' => 'insertTags',
    'sql'         => "mediumtext NULL",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['addHeroImage'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['addHeroImage'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'eval'      => ['submitOnChange' => true],
    'sql'       => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['heroImageButtonClass'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['heroImageButtonClass'],
    'exclude'   => true,
    'search'    => true,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 200],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['heroImageButtonText'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['heroImageButtonText'],
    'exclude'   => true,
    'search'    => true,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 200],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['heroImageButtonJumpTo'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['heroImageButtonJumpTo'],
    'exclude'   => true,
    'search'    => true,
    'inputType' => 'text',
    'eval'      => ['mandatory' => true, 'rgxp' => 'url', 'decodeEntities' => true, 'maxlength' => 255, 'fieldType' => 'radio', 'filesOnly' => true, 'tl_class' => 'w50 wizard'],
    'wizard'    => [
        ['tl_content', 'pagePicker'],
    ],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['heroImageBackgroundColor'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['heroImageBackgroundColor'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => ['maxlength' => 6, 'colorpicker' => true, 'isHexColor' => true, 'decodeEntities' => true, 'tl_class' => 'w50 wizard'],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['heroContentboxTextAlign'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['heroContentboxTextAlign'],
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['align-center', 'align-left', 'align-right'],
    'eval'      => ['tl_class' => 'w50 wizard'],
    'sql'       => "varchar(255) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['heroContentboxOpacity'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['heroContentboxOpacity'],
    'exclude'   => true,
    'inputType' => 'select',
    'options'   => ['0', '0.1', '0.2', '0.3', '0.4', '0.5', '0.6', '0.7', '0.8', '0.9', '1.0'],
    'eval'      => ['tl_class' => 'w50 wizard'],
    'sql'       => "varchar(255) NOT NULL default ''",
];
