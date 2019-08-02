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
