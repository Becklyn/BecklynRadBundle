Paginationw
==========

Adds a Pagination Navigation

## Pagination DataObject

```php
$pagination = new Pagination($currentPage = 1, $itemsPerPage = 25, $minPage = 1);
```

Additionally to the constructor arguments, you can get the min, max and normalized current page.
The current page is normalized, so that if you provide an invalid current page, you still get a valid page.

```php
$pagination = new Pagination(10, 10);
$pagination->setNumberOfItems(50);

var_dump($pagination->getMinPage());            // int 1
var_dump($pagination->getMaxPage());            // int 5
var_dump($pagination->getCurrentPage());        // int 1 (normalized, page = 10 is invalid)

$pagination->setCurrentPage(4);
var_dump($pagination->getCurrentPage());        // int 4 (valid, so returned unchanged)

$pagination->setCurrentPage("really invalid");
var_dump($pagination->getCurrentPage());        // int 1 (normalized)
```



## PaginatedList for Models

Provided by the **DoctrineModel**.

```php
getPaginatedResults (QueryBuilder $queryBuilder, Pagination $pagination, QueryBuilder $countQueryBuilder = null)
```

**Arguments**

<dl>
    <dt>`QueryBuilder $queryBuilder`</dt><dd>The query to get the entities</dd>
    <dt>`Pagination $pagination`</dt><dd>The prepared pagination object (currentPage, itemsPerPage and minPage)</dd>
    <dt>`QueryBuilder $countQueryBuilder` (optional)</dt><dd>A seperate count query if the query is too complex</dd>
</dl>

The method needs to calculate the number of total items. This is done by cloning the original query builder and changing the selection to `COUNT(...)`. This simple method will fail for more complex queries (like `GROUP`ed queries). In these cases you can just pass a `$countQueryBuilder` which is then used to calculate the number of total items.


The method will return a `PaginatedList` which just groups the fetched list items and the modified pagination.

```php
$paginatedList->getList();        // returns the list of doctrine results
$paginatedList->getPagination();  // returns the modified pagination element
```


## Twig-Integration

The `pagination()` twig function generates HTML for a generic pagination.
The generated pagination looks like that (using the Bootstrap 3 integration):
![Generated Pagination in Boostrap 3](https://raw.github.com/Becklyn/BecklynRadBundle/master/docs/images/pagination-screenshot.png)

The inactive list items are links to the corresponding list URL, the active item is no link.

```twig
{{ pagination(pagination, route, routeParams = {}, pageParameterName = "page") }}
```

**Arguments**
<dl>
    <dt>`Pagination $pagination`</dt><dd>the pagination element you want to create the HTML for</dd>
    <dt>`string $route`</dt><dd>the route to generate the page links</dd>
    <dt>`array $additionalRouteParameters` (optional)</dt><dd>additional parameters which are required to generate the page links (excluding the `page` parameter of course)</dd>
    <dt>`string $pageParameterName` (optional)</dt><dd>if the `page` parameter is named differently in your route, you can adjust it here</dd>
</dl>

### Examples of pagination calls

For the following example route definition ...

```yml
my_route:
    path: /clients/{clientId}/{pageNr}
    # ...
```
... the corresponding pagination call would look something like this:

```twig
{{ pagination(paginationObject, "my_route", {clientId: client.id}, "pageNr") }}
```


## Integration with Bootstrap 3

The generated HTML of the `pagination()` Twig function is already compatible with the Bootstrap 3 HTML definitions.

If you want to center the pagination, you can do it like this:

```twig
<div class="text-center">
    {{ pagination(...) }}
</div>
```