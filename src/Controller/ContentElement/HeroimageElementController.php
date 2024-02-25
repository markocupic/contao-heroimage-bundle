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
use Contao\CoreBundle\Framework\Adapter;
use Contao\CoreBundle\Framework\ContaoFramework;
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

    protected Adapter $filesModel;
    protected Adapter $stringUtil;

    public function __construct(
        protected readonly ContaoFramework $framework,
        protected readonly Studio $contaoImageStudio,
        protected readonly InsertTagParser $insertTagParser,
    ) {
        $this->filesModel = $this->framework->getAdapter(FilesModel::class);
        $this->stringUtil = $this->framework->getAdapter(StringUtil::class);
    }

    protected function getResponse(Template $template, ContentModel $model, Request $request): Response
    {
        // Add the CSS classes
        $template->class = implode(' ', array_filter(array_unique(array_merge([$template->class ?? ''], [$model->heroContentboxTextAlign ?? '']))));

        // Add the button classes
        $heroImageButtonClass = implode(' ', array_filter(array_unique(explode(' ', $model->heroImageButtonClass ?? ''))));
        $template->heroImageButtonClass = !empty($heroImageButtonClass) ? ' '.$heroImageButtonClass : '';

        // Add the background-styles
        $arrStyles = [];

        if (!empty($model->heroImageBackgroundColor)) {
            $arrStyles[] = sprintf('background-color:#%s', $model->heroImageBackgroundColor);
        }

        // Add a background-image
        $template->backgroundImage = 'none';

        if ($model->addHeroImage) {
            $objFilesModel = $this->filesModel->findByUuid($model->singleSRC);

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
        $heroImageText = $this->insertTagParser->replaceInline((string) $model->heroImageText);
        $template->heroImageText = $this->stringUtil->encodeEmail($heroImageText);

        // Add the href attribute
        $template->href = $this->insertTagParser->replaceInline((string) $model->heroImageButtonJumpTo);

        return $template->getResponse();
    }
}
