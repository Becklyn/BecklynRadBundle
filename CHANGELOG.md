7.8.0
=====

*   (fix) Add missing PhpDoc meta comments to let PhpStorm know about `BaseController::trans()`'s translation key and domain.
*   (feature) Add `BaseController::getJsonRequestData()` and `BaseController:getLogger()`
*   (internal) Require at least PHP 7.3


7.7.0
=====

*   (feature) Add prefix-based search term tokenizer.
*   (feature) Add `SimpleEntitySearchHelper`.


7.6.0
=====

*   (feature) Added log levels in `StatsCounter`: `->debug()`, `->warning()`, `->critical()`.
*   (deprecation) Deprecated log without level in `StatsCounter`: `->log()`.
*   (feature) Added possibility to create a nested stats counter with a prefix.


7.5.4
=====

*   (bug) Fix typo in variable.


7.5.3
=====

*   (improvement) Add `DeferredTranslation::translateAllValues()`.
*   (deprecation) Deprecated entity usage integration. Use `becklyn/entity-admin` instead.
*   (improvement) Add log messages support to `StatsCounter`.


7.5.2
=====

*   (improvement) Add `CommandHelper::disableSearchIndexing()`.


7.5.1
=====

*   (bug) Fix invalid tag validation pattern.


7.5.0
=====

*   (feature) Add base tag entity.
*   (feature) Add `TagMatcher` that automatically applies a query constraint to match tags.
*   (improvement) Add `TagHelper` functionality to `TagMatcher`.


7.4.6
=====

*   (bug) Fix placeholder translation in collapsed choice widgets.


7.4.5
=====

*   (bug) Properly translate placeholder in collapsed choice widgets.


7.4.4
=====

*   (bug) Fix wrong method name in `TagHelper`.


7.4.3
=====

*   (bug) Fix bad naming of method in `TagInterface`.


7.4.2
=====

*   (improvement) Add `Pagination::isValid()`.



7.4.1
=====

*   (bug) Fix type error.


7.4.0
=====

*   (feature) Add `CommandHelper`.
*   (feature) Add generic `TagInterface` + `TagHelper`.


7.3.0
=====

*   (improvement) Made `PaginatedList` generic in PhpStan.
*   (feature) Added `PropertiesSortableHandler`.
*   (bug) Make sortable handler more robust against cases where the doctrine entity isn't a singleton.


7.2.3
=====

*   (improvement) Make nested form rendering more robust and more flexible.


7.2.2
=====

*   (bug) Fixed missing refactor of form theme, that was overlooked in `7.2.1`.


7.2.1
=====

*   (bug) Refactor form theme to not use `<label>` as most outer element, as this has too many edge cases (like nested forms).


7.2.0
=====

*   (feature) Added `DeferredTranslation`.
*   (improvement) Added `DeferredRoute::generateValue`.


7.1.4
=====

*   (improvement) Added native support for `EntityInterface`s as parameters in `DeferredRoute`s.


7.1.3
=====

*   (improvement) Added `type` to entity usages.
*   (improvement) Removed the `ResolvedEntityUsage::toArray()` normalization. As the normalization is now dependent on the type usage, this makes no sense anymore.

All these were not really used in the wild, so no BC adaptions here.


7.1.2
=====

*   (improvement) Allow `string[]` as name in entity usages.
*   (improvement) Made usages classes all final.

All these were not really used in the wild, so no BC adaptions here.


7.1.1
=====

*   (bug) Fix invalid calculations in `Pagination`.


7.1.0
=====

*   (feature) Added `EntityUsagesFinder`, that helps when trying to collect usages of entities.
*   (feature) Add and autoregister doctrine mysql extensions (`doctrine` is now a required package of this bundle)
    *   string functions: `CHAR_LENGTH()`, `DATE_FORMAT()`, `IFELSE()`, `IFNULL()`, `JSON_EXTRACT()`, `LPAD()`
    *   numeric functions: `RAND()`
    *   datetime functions: `WEEK()`, `YEAR()`
*   (internal) Properly set branch alias.
*   (feature) Add `DeferredRoute`.
*   (feature) Add `BackendTranslator`.
*   (internal) Bumped required symfony version to `4.4+` (or `5.0+`), as we need `!tagged_iterator`.


7.0.7
=====

*   (improvement) Add `total` to the normalized pagination data.


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
