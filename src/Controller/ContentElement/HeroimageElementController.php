<?php

/**
 * This file is part of a markocupic Contao Bundle.
 *
 * (c) Marko Cupic 2020 <m.cupic@gmx.ch>
 *
 * @author     Marko Cupic
 * @package    Heroimage
 * @license    MIT
 * @see        https://github.com/markocupic/contao-heroimage-bundle
 *
 */

declare(strict_types=1);

namespace Markocupic\ContaoHeroimageBundle\Controller\ContentElement;

use Contao\ContentModel;
use Contao\Controller;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\ServiceAnnotation\ContentElement;
use Contao\FilesModel;
use Contao\StringUtil;
use Contao\Template;
use Contao\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HeroimageElementController
 *
 * @package Markocupic\ContaoHeroimageBundle\Controller\ContentElement
 */
class HeroimageElementController extends AbstractContentElementController
{

    /**
     * HeroimageElementController constructor.
     *
     * @param ContaoFramework $framework
     */
    public function __construct(ContaoFramework $framework)
    {

        $this->framework = $framework;
    }

    /**
     * @param Template $template
     * @param ContentModel $model
     * @param Request $request
     * @return Response|null
     */
    protected function getResponse(Template $template, ContentModel $model, Request $request): ?Response
    {

        $hasValidImage = false;

        $objFile = FilesModel::findByUuid($model->singleSRC);

        if ($objFile !== null)
        {
            if (Validator::isUuid($model->singleSRC))
            {
                if (is_file(TL_ROOT . '/' . $objFile->path))
                {
                    $hasValidImage = true;
                    $model->singleSRC = $objFile->path;
                }
            }
        }

        if ($hasValidImage)
        {
            Controller::addImageToTemplate($template, $model->row(), null, null, $objFile);
        }
        $model->heroImageText = StringUtil::toHtml5($model->heroImageText);


        // Add image as background-image
        $template->backgroundImage = 'none';
        if ($model->addHeroImage)
        {
            $template->backgroundImage = "url('" . $template->picture['img']['src'] . "')";
        }

        $template->heroImageText = StringUtil::encodeEmail($model->heroImageText);

        $template->href = Controller::replaceInsertTags($model->heroImageButtonJumpTo);

        return $template->getResponse();
    }
}
