<?php

declare(strict_types=1);

/*
 * This file is part of Contao Hero Image Bundle.
 *
 * (c) Marko Cupic 2024 <m.cupic@gmx.ch>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/contao-heroimage-bundle
 */

namespace Markocupic\ContaoHeroimageBundle\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\CoreBundle\InsertTag\InsertTagParser;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\StringUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsContentElement(HeroimageElementController::TYPE, category: 'image_elements', template: 'content_element/heroimage_element')]
class HeroimageElementController extends AbstractContentElementController
{
    public const TYPE = 'heroimage_element';

    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly Studio $studio,
        private readonly InsertTagParser $insertTagParser,
    ) {
    }

    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $stringUtil = $this->framework->getAdapter(StringUtil::class);

        // Text alignment of the content box (CSS class)
        $template->set('text_align', (string) $model->heroContentboxTextAlign);

        // Content
        $template->set('heroImagePreline', (string) $model->heroImagePreline);
        $template->set('heroImageHeadline', (string) $model->heroImageHeadline);
        $template->set('heroImageText', $stringUtil->encodeEmail($this->insertTagParser->replaceInline((string) $model->heroImageText)));
        $template->set('heroContentboxOpacity', (string) $model->heroContentboxOpacity);

        // Button
        $buttonClasses = array_filter(array_unique(explode(' ', (string) $model->heroImageButtonClass)));
        $template->set('heroImageButtonText', (string) $model->heroImageButtonText);
        $template->set('heroImageButtonClass', $buttonClasses ? ' '.implode(' ', $buttonClasses) : '');
        $template->set('href', $this->insertTagParser->replaceInline((string) $model->heroImageButtonJumpTo));

        // Background color and background image
        $backgroundColor = $model->heroImageBackgroundColor ? '#'.$model->heroImageBackgroundColor : null;
        $backgroundImage = $this->getBackgroundImageSrc($model);

        $template->set('background_color', $backgroundColor);
        $template->set('background_image', $backgroundImage);

        $styles = [];

        if (null !== $backgroundColor) {
            $styles[] = 'background-color:'.$backgroundColor;
        }

        if (null !== $backgroundImage) {
            $styles[] = \sprintf("background-image:url('%s')", $backgroundImage);
        }

        // Kept for backwards compatibility with custom templates
        $template->set('backgroundStyle', $styles ? \sprintf(' style="%s"', $stringUtil->specialcharsAttribute(implode(';', $styles))) : '');

        return $template->getResponse();
    }

    private function getBackgroundImageSrc(ContentModel $model): string|null
    {
        if (!$model->addHeroImage || !$model->singleSRC) {
            return null;
        }

        $figure = $this->studio
            ->createFigureBuilder()
            ->fromUuid($model->singleSRC)
            ->setSize($model->size)
            ->buildIfResourceExists()
        ;

        $src = $figure?->getImage()->getImageSrc();

        return $src ?: null;
    }
}
