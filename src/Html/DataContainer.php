<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Html;

use Symfony\Component\HttpFoundation\Response;

class DataContainer
{
    /**
     * Renders the data container to HTML.
     *
     * @return string
     */
    public function renderToHtml (array $data, string $className, ?string $id = null)
    {
        return \sprintf(
            '<script%s class="_data-container %s" type="application/json">%s</script>',
            null !== $id ? ' id="' . \htmlspecialchars($id, \ENT_QUOTES) . '"' : "",
            \htmlspecialchars($className, \ENT_QUOTES),
            \htmlspecialchars(\json_encode($data), \ENT_NOQUOTES)
        );
    }


    /**
     * Renders the data container as a direct response.
     * Convenient to use in embedded controllers.
     */
    public function createResponse (array $data, string $className, ?string $id = null) : Response
    {
        return new Response($this->renderToHtml($data, $className, $id));
    }
}
