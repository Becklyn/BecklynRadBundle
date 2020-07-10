8.0.0 (unreleased)
=====

*   (bc) Removed everything related to `EntityUsages`.
*   (bc) Removed `StatsCounter::log()`.
*   (bc) Removed `InvalidSortOperationException`.
*   (bc) Removed `BundleExtension` and `ConfigurableBundleExtension`.
*   (internal) Renamed Composer package from `becklyn/rad-bundle` to `becklyn/rad`. See README for more information.
*   (bc) Rename namespace `Becklyn\RadBundle` to `Becklyn\Rad`.
*   (improvement) Move `isNew()` to `EntityInterface` as well (is automatically implemented by `IdTrait`).
*   (internal) `RadException` is now an interface.
*   (bc) Remove `RadExceptionInterface`.


7.14.1
======

*   (improvement) Allow passing `null` to `DataContainer` methods (to not embed anything).


7.14.0
======

*   (internal) Require at least PHP 7.4.
*   (feature) Add support for passing in JSON paths into `SimpleEntitySearchHelper::applyJsonSearch()`. 


7.13.4
======

*   (improvement) Add named constructor `DeferredTranslation::backend()`.


7.13.3
======

*   (deprecation) Deprecate `BundleExtension` and `ConfigurableBundleExtension`. Please use the respective versions from `becklyn/rad-bundles` instead.


7.13.2
======

*   (bug) Add `.form-row` to top level form errors.


7.13.1
======

*   (improvement) Add search mode to search helper + query tokenizer.
*   (internal) Require at least PHP 7.4. 


7.13.0
======

*   (feature) Add `SimpleEntitySearchHelper::applyJsonSearch()`.
*   (improvement) Also automatically enable `JSON_SEARCH()` MySQL function in Doctrine.


7.12.1
======

*   (bug) Fix form error translations being in the wrong translation domain, so e.g. Gluggi would render an incorrect error message.


7.12.0
======

*   (feature) Add `LinkableInterface`.
*   (improvement) Implement `LinkableInterface` in `DeferredRoute`.


7.11.2
======

*   (improvement) Return `null` for `DeferredRoute::generateValue(null)`.
*   (internal) Fixed some typos in doc comments.
*   (improvement) Improve types for `DeferredRoute` and `DeferredTranslation`.


7.11.1
======

*   (improvement) Add `allow_sort` field for `Collection` form fields.
*   (improvement) Expose prefixes in collection row JSON data.


7.11.0
======

*   (improvement) Return `null` for `DeferredTranslation::translateValue(null)`.
*   (feature) Add proper styling for `Collection` form fields.
*   (feature) Add additional texts for `Collection` form types:
    *   `empty_message` is displayed if there is no entry.
    *   `entry_add_label` is the label of the "add entry" button.
    *   `entry_remove_label` is the label of every "remove entry" button.


7.10.7
======

*   (bug) Also add `Router` under interface class name in the service locator in the `BaseController`.


7.10.6
======

*   (improvement) Replace `$configurator` from `BundleExtension` with better `ConfigurableBundleExtension`.


7.10.5
======

*   (improvement) Add `$configurator` callable to `BundleExtension`.


7.10.4
======

*   (improvement) Add helper `DeferredRoute::ensureValidValue()`.
*   (improvement) Add helper `DeferredTranslation::ensureValidValue()`.


7.10.3
======

*   (improvement) Add helper `DeferredRoute::isValidValue()`.
*   (improvement) Add helper `DeferredTranslation::isValidValue()`.


7.10.2
=====

*   (improvement) Move calculation of pagination offset from `Paginator` to `Pagination`.


7.10.1
======

*   (improvement) Return `bool` in `SortableHandler::sortElementBefore()` instead of throwing an exception.
*   (deprecation) Deprecate `InvalidSortOperationException`, as it is not used anymore.
*   (improvement) Mark all sortable handlers as `final`.



7.10.0
======

*   (feature) Add `DeferredForm`.


7.9.4
=====

*   (improvement) Simplify signature of `BaseController::ajaxResponse()`.


7.9.3
=====

*   (improvement) Add dynamic default for `$status` in `AjaxResponseBuilder`.
*   (improvement) Make `LabeledExceptionTrait::setFrontendMessage()` fluent.
*   (improvement) Add `LabeledExceptionTrait::createWithLabel()`.


7.9.2
=====

*   (improvement) Add named constructor `DeferredTranslation::backend()`.


7.9.1
=====

*   (bug) Fix invalid key in message actions in `AjaxResponseBuilder`.


7.9.0
=====

*   (feature) Add `AjaxResponseBuilder` + `BaseController::ajaxReponse()`.
*   (bug) Rename `ExceptionWithFrontendLabelTrait` to `LabeledExceptionTrait`.


7.8.2
=====

*   (improvement) Add `ExceptionWithFrontendLabelTrait`.
*   (improvement) Add generic `UnexpectedTypeException`.


7.8.1
=====

*   (bug) Fix unintentional BC break from 7.8.0: Remove return type from `BaseController::getSubscribedServices()`. 


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
