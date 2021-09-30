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

namespace Markocupic\ContaoHeroimageBundle\Controller\ContentElement;

use Contao\ContentModel;
use Contao\Controller;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\FilesModel;
use Contao\StringUtil;
use Contao\Template;
use Contao\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HeroimageElementController.
 */
class HeroimageElementController extends AbstractContentElementController
{
    /**
     * HeroimageElementController constructor.
     */
    public function __construct(ContaoFramework $framework)
    {
        $this->framework = $framework;
    }

    protected function getResponse(Template $template, ContentModel $model, Request $request): ?Response
    {
        $hasValidImage = false;

        $objFile = FilesModel::findByUuid($model->singleSRC);

        if (null !== $objFile) {
            if (Validator::isUuid($model->singleSRC)) {
                if (is_file(TL_ROOT.'/'.$objFile->path)) {
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
