7.x to 8.0
==========

*   The entity usage integration was removed. Migrate to `becklyn/entity-admin`.
*   `StatsCounter::log()` was removed. Use `StatsCounter::debug/warning/critical()` instead.
*   The `InvalidSortOperationException` exception was removed. There is no replacement.
*   Update all imported namespaces from `Becklyn\RadBundle\` to `Becklyn\Rad\`.
*   The classes `BundleExtension` and `ConfigurableBundleExtension` have been removed. Please use the respective versions from `becklyn/rad-bundles` instead.
*   `RadExceptionInterface` was removed, and `RadException` is now an interface. Just use `RadException` in your catch instead.


6.x to 7.0
==========

*   The return types from `Model` and `ModelInterface` were removed. Remove them in your code as well and always return the instance
    to create a fluent interface.


6.0 to 6.1
==========

*   `Pagination`: the namespace has changed and the constructor have been swapped to `($current, $perPage = 50, $total = 0)`.
*   `PaginatedList`: the namespace has changed. 


5.x to 6.0
==========

*   The `BundleExtension` was trimmed. It only loads the `services.yaml` (if the file exists). Extend the class if you need to process configuration values.
    You should't need to extend it to only load `services.yaml`.
*   Removed `DoctrineModel`, use `Model` instead.
*   Removed `SortableHelper`, a replacement will be added in one of the next releases.
*   Removed `SortableModelTrait`, a replacement will be added in one of the next releases.
