Changelog for 6.0
=================

*   Added new form option `attr_row`, that acts like `attr` but is dedicated for form row attributes.
*   Added `LabeledEntityRemovalBlockedException`
*   Added `Model` and `ModelInterface`.
*   Bumped all dependencies. PHP 7.2+ and Symfony 4.2+ are now required.
*   Added `classnames()` twig function.
*   Added a custom `Profiler` wrapper, that is always available and allows to disable symfony's profiler.
*   Added a `data_container()` twig function.
*   Added `DownloadableStringTrait`, to easily send strings as downloadable files to the browser.
*   Added `ChoicePlaceholderFormExtension`. Symfony removes "placeholder" attributes for certain configurations. 
    In these cases, the placeholder is re-added as `data-placeholder` attribute (if not already set).
*   Added a default form theme in `@BecklynRad/form/theme.html.twig`.
