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

namespace Markocupic\ContaoHeroimageBundle\Controller\ContentElement;

use Contao\ContentModel;
use Contao\Controller;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\FilesModel;
use Contao\StringUtil;
use Contao\Template;
use Contao\Validator;
use Markocupic\SacEventToolBundle\Controller\ContentElement\UserPortraitController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement(HeroimageElementController::TYPE, category: 'image_elements', template: 'ce_heroimage_element')]
class HeroimageElementController extends AbstractContentElementController
{
    public const TYPE = 'heroimage_element';

    public function __construct(
        private readonly ContaoFramework $framework,
        private string $projectDir,
    ){
    }

    protected function getResponse(Template $template, ContentModel $model, Request $request): ?Response
    {
        $hasValidImage = false;

        $objFile = FilesModel::findByUuid($model->singleSRC);

        if (null !== $objFile) {
            if (Validator::isUuid($model->singleSRC)) {
                if (is_file($this->projectDir.'/'.$objFile->path)) {
                    $hasValidImage = true;
                    $model->singleSRC = $objFile->path;
                }
            }
        }

        if ($hasValidImage) {
            Controller::addImageToTemplate($template, $model->row(), null, null, $objFile);
        }
        $model->heroImageText = StringUtil::toHtml5($model->heroImageText);

        // Add image as background-image
        $template->backgroundImage = 'none';

        if ($model->addHeroImage) {
            $template->backgroundImage = "url('".$template->picture['img']['src']."')";
        }

        $template->heroImageText = StringUtil::encodeEmail($model->heroImageText);

        $template->href = Controller::replaceInsertTags($model->heroImageButtonJumpTo);

        return $template->getResponse();
    }
}
