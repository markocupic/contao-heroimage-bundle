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
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\CoreBundle\InsertTag\InsertTagParser;
use Contao\FilesModel;
use Contao\StringUtil;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement(HeroimageElementController::TYPE, category: 'image_elements', template: 'ce_heroimage_element')]
class HeroimageElementController extends AbstractContentElementController
{
    public const TYPE = 'heroimage_element';

    public function __construct(
        private readonly Studio $contaoImageStudio,
        private readonly InsertTagParser $insertTagParser,
    ) {
    }

    protected function getResponse(Template $template, ContentModel $model, Request $request): Response|null
    {
        // Add the CSS classes
        $template->class = implode(
            ' ',
            array_filter(
                explode(
                    ' ',
                    $template->class.' '.$model->heroContentboxTextAlign,
                )
            )
        );

        // Add the button classes
        $template->heroImageButtonClass = !empty($model->heroImageButtonClass) ? ' '.trim($model->heroImageButtonClass) : '';

        // Add the background-styles
        $arrStyles = [];

        if (!empty($model->heroImageBackgroundColor)) {
            $arrStyles[] = sprintf('background-color:#%s', $model->heroImageBackgroundColor);
        }

        // Add a background-image
        $template->backgroundImage = 'none';

        if ($model->addHeroImage) {
            $objFilesModel = FilesModel::findByUuid($model->singleSRC);

            if (null !== $objFilesModel && is_file($objFilesModel->getAbsolutePath())) {
                $figure = $this->contaoImageStudio
                    ->createFigureBuilder()
                    ->from($objFilesModel)
                    ->setSize($model->size)
                    ->setMetadata($model->getOverwriteMetadata())
                    ->enableLightbox((bool) $model->fullsize)
                    ->buildIfResourceExists()
                ;

                if (null !== $figure) {
                    $figure->applyLegacyTemplateData($template, $model->imagemargin);

                    if (!empty($template->picture['img']['src'])) {
                        $arrStyles[] = sprintf("background-image:url('%s');", $template->picture['img']['src']);
                    }
                }
            }
        }

        $template->backgroundStyle = '';

        // Add the style attribute
        if (!empty($arrStyles)) {
            $template->backgroundStyle = sprintf(' style="%s"', implode(';', $arrStyles));
        }

        // Format text
        $heroImageText = nl2br($model->heroImageText);
        $heroImageText = $this->insertTagParser->replaceInline($heroImageText);
        $heroImageText = StringUtil::encodeEmail($heroImageText);
        $template->heroImageText = $heroImageText;

        // Add the href
        $template->href = $this->insertTagParser->replaceInline($model->heroImageButtonJumpTo);

        return $template->getResponse();
    }
}
