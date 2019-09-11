<?php declare(strict_types=1);

namespace Tests\Becklyn\RadBundle\Html;

use Becklyn\RadBundle\Html\DataContainer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class DataContainerTest extends TestCase
{
    /**
     *
     */
    public function testRender () : void
    {
        $dataContainer = new DataContainer();

        self::assertSame(
            '<script class="_data-container test" type="application/json">{"&lt;b&gt;":2}</script>',
            $dataContainer->renderToHtml(["<b>" => 2], "test")
        );
    }
    /**
     *
     */
    public function testRenderWithId () : void
    {
        $dataContainer = new DataContainer();

        self::assertSame(
            '<script id="some-id" class="_data-container test" type="application/json">{"&lt;b&gt;":2}</script>',
            $dataContainer->renderToHtml(["<b>" => 2], "test", "some-id")
        );
    }


    public function testRenderToResponse () : void
    {
        $dataContainer = new DataContainer();
        $result = $dataContainer->createResponse(["<b>" => 2], "test", "some-id");

        self::assertInstanceOf(Response::class, $result);
        self::assertSame(
            '<script id="some-id" class="_data-container test" type="application/json">{"&lt;b&gt;":2}</script>',
            $result->getContent()
        );
    }
}
