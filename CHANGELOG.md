7.0.6
=====

*   (improvement) Don't use deprecated `ManagerRegistry`.


7.0.5
=====

*   (improvement) Added option to explicitly set the bundle alias.


7.0.4
=====

*   (improvement) Support the new bundle structure of Symfony 5 in `BundleExtension`.


7.0.3
=====

*   Allow Symfony 5.


7.0.2
=====

*   Also add `form-widget-nested` on nested forms.


7.0.1
=====

*   Improve handling of entity removal blocked messages.


7.0.0
=====

*   All exceptions now extend `RadException`.
*   Added `SortableHandler`.
*   Make methods in `Model` and `ModelInterface` fluent.


6.3.2
=====

*   Fix trans domain for ``BaseController::getEntityRemovalMessage()`` message: should be `backend`, 
    as this feature isn't really useful in the frontend.


6.3.1
=====

*   Added `PaginatedList::createFromArray()`.


6.3.0
=====

*   Added `IdTrait::isNew()`.


6.2.1
=====

*   The `form-row` elements now also have typed classes, like `form-row-text`.
*   Add proper form error translation labels.
*   All form elements now use `form-` as common class name prefix.


6.2.0
=====

*   Extracted `DataContainer` into it's own service. You can now directly create a data container response using `DataContainer::createResponse()`.


6.1.0
=====

*   Added `StatsCounter`.
*   Changed the constructor parameters for `Pagination`.
*   Added `Paginator`.


6.0.0
=====

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
*   Added `row_attr` option for forms, with which you can apply attributes the row container in form themes.
