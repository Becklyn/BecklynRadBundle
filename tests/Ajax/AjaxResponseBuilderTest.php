<?php declare(strict_types=1);

namespace Tests\Becklyn\RadBundle\Ajax;

use Becklyn\RadBundle\Ajax\AjaxResponseBuilder;
use Becklyn\RadBundle\Exception\Ajax\ResponseBuilderException;
use Becklyn\RadBundle\Exception\LabeledExceptionInterface;
use Becklyn\RadBundle\Exception\LabeledExceptionTrait;
use Becklyn\RadBundle\Route\DeferredRoute;
use Becklyn\RadBundle\Translation\DeferredTranslation;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class AjaxResponseBuilderTest extends TestCase
{
    /**
     *
     */
    public function testMinimalGenerate () : void
    {
        $builder = new AjaxResponseBuilder(
            $this->getMockBuilder(TranslatorInterface::class)->disableOriginalConstructor()->getMock(),
            $this->getMockBuilder(RouterInterface::class)->disableOriginalConstructor()->getMock(),
            true,
            "test"
        );

        $response = $builder->build();

        self::assertSame(200, $response->getStatusCode());
        self::assertEquals([
            "ok" => true,
            "status" => "test",
            "data" => [],
        ], \json_decode($response->getContent(), true));
    }


    /**
     * Also tests all messages + targets as strings.
     */
    public function testFullGenerate () : void
    {
        $builder = new AjaxResponseBuilder(
            $this->getMockBuilder(TranslatorInterface::class)->disableOriginalConstructor()->getMock(),
            $this->getMockBuilder(RouterInterface::class)->disableOriginalConstructor()->getMock(),
            false,
            "status"
        );

        $response = $builder
            ->negativeMessage("message")
            ->messageAction("action", "target")
            ->redirect("redirect")
            ->setData(["o" => "hai"])
            ->build();

        self::assertSame(200, $response->getStatusCode());
        self::assertEquals([
            "ok" => false,
            "status" => "status",
            "message" => [
                "text" => "message",
                "impact" => "negative",
                "action" => [
                    "label" => "action",
                    "action" => "target",
                ],
            ],
            "redirect" => "redirect",
            "data" => [
                "o" => "hai"
            ],
        ], \json_decode($response->getContent(), true));
    }


    /**
     *
     */
    public function testStatusCodeOnNotOk () : void
    {
        $builder = new AjaxResponseBuilder(
            $this->getMockBuilder(TranslatorInterface::class)->disableOriginalConstructor()->getMock(),
            $this->getMockBuilder(RouterInterface::class)->disableOriginalConstructor()->getMock(),
            false,
            "error"
        );

        $response = $builder->build();
        self::assertSame(200, $response->getStatusCode());
    }


    /**
     *
     */
    public function testPositiveMessage () : void
    {
        $builder = new AjaxResponseBuilder(
            $this->getMockBuilder(TranslatorInterface::class)->disableOriginalConstructor()->getMock(),
            $this->getMockBuilder(RouterInterface::class)->disableOriginalConstructor()->getMock(),
            false,
            "status"
        );

        $response = $builder
            ->positiveMessage("message")
            ->build();

        self::assertSame(200, $response->getStatusCode());
        self::assertEquals([
            "ok" => false,
            "status" => "status",
            "message" => [
                "text" => "message",
                "impact" => "positive",
            ],
            "data" => [],
        ], \json_decode($response->getContent(), true));
    }


    /**
     *
     */
    public function testNegativeMessage () : void
    {
        $builder = new AjaxResponseBuilder(
            $this->getMockBuilder(TranslatorInterface::class)->disableOriginalConstructor()->getMock(),
            $this->getMockBuilder(RouterInterface::class)->disableOriginalConstructor()->getMock(),
            false,
            "status"
        );

        $response = $builder
            ->negativeMessage("message")
            ->build();

        self::assertSame(200, $response->getStatusCode());
        self::assertEquals([
            "ok" => false,
            "status" => "status",
            "message" => [
                "text" => "message",
                "impact" => "negative",
            ],
            "data" => [],
        ], \json_decode($response->getContent(), true));
    }


    /**
     *
     */
    public function testNeutralMessage () : void
    {
        $builder = new AjaxResponseBuilder(
            $this->getMockBuilder(TranslatorInterface::class)->disableOriginalConstructor()->getMock(),
            $this->getMockBuilder(RouterInterface::class)->disableOriginalConstructor()->getMock(),
            false,
            "status"
        );

        $response = $builder
            ->message("message")
            ->build();

        self::assertSame(200, $response->getStatusCode());
        self::assertEquals([
            "ok" => false,
            "status" => "status",
            "message" => [
                "text" => "message",
                "impact" => "neutral",
            ],
            "data" => [],
        ], \json_decode($response->getContent(), true));
    }


    /**
     *
     */
    public function testMessageDeferredTranslation () : void
    {
        $translator = $this->getMockBuilder(TranslatorInterface::class)->disableOriginalConstructor()->getMock();

        $translator
            ->expects(self::once())
            ->method("trans")
            ->with("id")
            ->willReturn("translated");

        $builder = new AjaxResponseBuilder(
            $translator,
            $this->getMockBuilder(RouterInterface::class)->disableOriginalConstructor()->getMock(),
            false,
            "status"
        );

        $response = $builder
            ->message(new DeferredTranslation("id"))
            ->build();

        self::assertSame(200, $response->getStatusCode());
        self::assertEquals([
            "ok" => false,
            "status" => "status",
            "message" => [
                "text" => "translated",
                "impact" => "neutral",
            ],
            "data" => [],
        ], \json_decode($response->getContent(), true));
    }


    /**
     *
     */
    public function testMessageActionLabelString () : void
    {
        $translator = $this->getMockBuilder(TranslatorInterface::class)->disableOriginalConstructor()->getMock();

        $translator
            ->expects(self::once())
            ->method("trans")
            ->with("id")
            ->willReturn("translated");

        $router = $this->getMockBuilder(RouterInterface::class)->disableOriginalConstructor()->getMock();

        $router
            ->expects(self::once())
            ->method("generate")
            ->with("route")
            ->willReturn("compiled");

        $builder = new AjaxResponseBuilder(
            $translator, $router,
            false,
            "status"
        );

        $response = $builder
            ->message("message")
            ->messageAction(new DeferredTranslation("id"), new DeferredRoute("route"))
            ->build();

        self::assertSame(200, $response->getStatusCode());
        self::assertEquals([
            "ok" => false,
            "status" => "status",
            "message" => [
                "text" => "message",
                "impact" => "neutral",
                "action" => [
                    "label" => "translated",
                    "action" => "compiled",
                ],
            ],
            "data" => [],
        ], \json_decode($response->getContent(), true));
    }


    /**
     *
     */
    public function testMessageFromExceptionValid () : void
    {
        $builder = new AjaxResponseBuilder(
            $this->getMockBuilder(TranslatorInterface::class)->disableOriginalConstructor()->getMock(),
            $this->getMockBuilder(RouterInterface::class)->disableOriginalConstructor()->getMock(),
            false,
            "status"
        );

        $exception = new class extends \Exception implements LabeledExceptionInterface
        {
            use LabeledExceptionTrait;
        };
        $exception->setFrontendMessage("exception-message");

        $response = $builder
            ->messageFromException($exception, "fallback")
            ->build();

        self::assertSame(200, $response->getStatusCode());
        self::assertEquals([
            "ok" => false,
            "status" => "status",
            "message" => [
                "text" => "exception-message",
                "impact" => "negative",
            ],
            "data" => [],
        ], \json_decode($response->getContent(), true));
    }


    /**
     *
     */
    public function testMessageFromExceptionInvalid () : void
    {
        $builder = new AjaxResponseBuilder(
            $this->getMockBuilder(TranslatorInterface::class)->disableOriginalConstructor()->getMock(),
            $this->getMockBuilder(RouterInterface::class)->disableOriginalConstructor()->getMock(),
            false,
            "status"
        );

        $response = $builder
            ->messageFromException(new \Exception(), "fallback")
            ->build();

        self::assertSame(200, $response->getStatusCode());
        self::assertEquals([
            "ok" => false,
            "status" => "status",
            "message" => [
                "text" => "fallback",
                "impact" => "negative",
            ],
            "data" => [],
        ], \json_decode($response->getContent(), true));
    }


    /**
     *
     */
    public function testMessageActionWithoutMessage () : void
    {
        $this->expectException(ResponseBuilderException::class);

        $builder = new AjaxResponseBuilder(
            $this->getMockBuilder(TranslatorInterface::class)->disableOriginalConstructor()->getMock(),
            $this->getMockBuilder(RouterInterface::class)->disableOriginalConstructor()->getMock(),
            true,
            "test"
        );

        $builder
            ->messageAction("test", "target")
            ->build();
    }
}
