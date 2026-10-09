![Alt text](docs/logo.png?raw=true "logo")

# Contao hero image

This extension adds a hero image content element to the contao CMS.

![Alt text](docs/hero_screenshot.jpg?raw=true "frontend screenshot")

## Requirements

- PHP 8.1 or higher (Contao 6 requires a more recent PHP version)
- Contao 5.3 or Contao 6

Contao 4.13 is no longer supported. Please use version 1.x of this extension for Contao 4.13.

## Templates

The content element uses the modern Twig template `content_element/heroimage_element.html.twig`.

Version 2 replaces the legacy template `ce_heroimage_element`. If you have created a custom template
(e.g. `templates/ce_heroimage_element_custom.html.twig`), you have to recreate it as a variant of the new
template (e.g. `templates/content_element/heroimage_element/custom.html.twig`):

```twig
{% extends "@Contao/content_element/heroimage_element.html.twig" %}

{% block content %}
    {{ parent() }}
{% endblock %}
```

The template variables `heroImagePreline`, `heroImageHeadline`, `heroImageText`, `heroImageButtonText`,
`heroImageButtonClass`, `href`, `heroContentboxOpacity` and `backgroundStyle` are still available.
New variables are `text_align`, `background_color` and `background_image`. Use `element_css_classes`
instead of `class`.
