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
        private readonly string $projectDir,
    ) {
    }

    protected function getResponse(Template $template, ContentModel $model, Request $request): Response|null
    {
        $template->backgroundImage = 'none';

        // Add an image as background-image
        if ($model->addHeroImage) {
            $objFile = FilesModel::findByUuid($model->singleSRC);

            if (null !== $objFile && is_file($this->projectDir.'/'.$objFile->path)) {
                $objFilesModel = $objFile;

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
                        $template->backgroundImage = "url('".$template->picture['img']['src']."')";
                    }
                }
            }
        }

        $heroImageText = nl2br($model->heroImageText);
        $heroImageText = $this->insertTagParser->replaceInline($heroImageText);
        $heroImageText = StringUtil::encodeEmail($heroImageText);
        $template->heroImageText = $heroImageText;

        $template->href = $this->insertTagParser->replaceInline($model->heroImageButtonJumpTo);

        return $template->getResponse();
    }
}
