Becklyn Rad Bundle
==================

This bundle provides RAD related functionality for the usage in Symfony.


⚠️ New Composer package name 
============================

This bundle has been renamed from `becklyn/rad-bundle` to `becklyn/rad` for v8.0. Versions <=7.x are still available under `becklyn/rad-bundle`.


AJAX Protocol
=============

This bundle uses a default AJAX protocol, that is used in the `AjaxResponseBuilder` and can be used for your
project. The ajax call will always return an error 200, as it shouldn't flood the error tracking (with error 400
AJAX request).

The protocol looks like this:

```typescript
interface AjaxResponse
{
    /**
     * Whether the call succeeded.
     */
    ok: boolean;

    /**
     * Any string status, like "ok" or "invalid-id" that
     * you can react to in your code (if you need to).
     */
    status: string;

    /**
     * The response data.
     */
    data: Record<number|string, any> | Array<any>;

    /**
     * A redirect target, where the AJAX handler should 
     * redirect to.
     */
    redirect?: string;

    /**
     * A toast message with optional type and action target.
     */
    message?: {
        text: string;
        impact: "positive" | "negative" | "neutral";
        action?: {
            label: string;
            url: string;
        };
    };
}
```

There is a corresponding fetch client implementation in [`mojave`](https://github.com/Becklyn/mojave) that can be used.
This type above is also available as generic TypeScript type in `mojave`.


Form Extensions
---------------

This bundle automatically adds several form extensions.

### Collection Labels Extension

This extension adds three additional optional labels for `Collection` form fields:

*   `empty_message` is displayed if there is no entry.
*   `entry_add_label` is the label of the "add entry" button.
*   `entry_remove_label` is the label of every "remove entry" button.
