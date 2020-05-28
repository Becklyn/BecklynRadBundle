<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Ajax;

use Becklyn\RadBundle\Exception\Ajax\ResponseBuilderException;
use Becklyn\RadBundle\Exception\LabeledExceptionInterface;
use Becklyn\RadBundle\Exception\UnexpectedTypeException;
use Becklyn\RadBundle\Route\DeferredRoute;
use Becklyn\RadBundle\Translation\DeferredTranslation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * The response builder builds a response of the following structure:
 *
 * {
 *     ok: boolean;
 *     status: string; // basic string status, that you can react to in your code
 *     data: any; // the response data
 *     redirect?: string; // a possible redirect target
 *     metaTitle?: string; // whether the meta title
 * }
 */
final class AjaxResponseBuilder
{
    /** @var TranslatorInterface */
    private $translator;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var bool */
    private $ok;

    /** @var string */
    private $status;

    /** @var DeferredRoute|string|null */
    private $redirect;

    /** @var DeferredTranslation|string|null */
    private $message;

    /** @var string|null */
    private $messageImpact;

    /** @var DeferredTranslation|string|null */
    private $messageActionLabel;

    /** @var DeferredRoute|string|null */
    private $messageActionTarget;

    /** @var array */
    private $data = [];


    /**
     */
    public function __construct (
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator,
        bool $ok,
        string $status
    )
    {
        $this->urlGenerator = $urlGenerator;
        $this->translator = $translator;
        $this->ok = $ok;
        $this->status = $status;
    }


    /**
     * @return $this
     */
    public function setData (array $data) : self
    {
        $this->data = $data;
        return $this;
    }


    /**
     * @param DeferredRoute|string $redirect
     *
     * @return $this
     */
    public function redirect ($redirect) : self
    {
        if (!$redirect instanceof DeferredRoute && !\is_string($redirect))
        {
            throw new UnexpectedTypeException($redirect, DeferredRoute::class . " or string");
        }

        $this->redirect = $redirect;
        return $this;
    }


    /**
     * @param DeferredTranslation|string $message
     *
     * @return $this
     */
    public function positiveMessage ($message) : self
    {
        return $this->setMessage($message, "positive");
    }


    /**
     * @param DeferredTranslation|string $message
     *
     * @return $this
     */
    public function negativeMessage ($message) : self
    {
        return $this->setMessage($message, "negative");
    }


    /**
     * @param DeferredTranslation|string $message
     *
     * @return $this
     */
    public function message ($message) : self
    {
        return $this->setMessage($message, "neutral");
    }


    /**
     * @param DeferredTranslation|string $message
     *
     * @return $this
     */
    private function setMessage ($message, string $impact) : self
    {
        if (null !== $this->message)
        {
            throw new ResponseBuilderException("Can only set one message per response.");
        }

        if (!\is_string($message) && !$message instanceof DeferredTranslation)
        {
            throw new UnexpectedTypeException($message, DeferredTranslation::class . " or string");
        }

        $this->message = $message;
        $this->messageImpact = $impact;

        return $this;
    }


    /**
     * Automatically adds the message from an exception with an optional fallback
     *
     * @param DeferredTranslation|string $fallback
     *
     * @return $this
     */
    public function messageFromException (
        \Throwable $exception,
        $fallback
    ) : self
    {
        if (!\is_string($fallback) && !$fallback instanceof DeferredTranslation)
        {
            throw new UnexpectedTypeException($fallback, DeferredTranslation::class . " or string");
        }

        if ($exception instanceof LabeledExceptionInterface)
        {
            $label = $exception->getFrontendMessage();

            if (null !== $label)
            {
                return $this->negativeMessage($label);
            }
        }

        return $this->negativeMessage($fallback);
    }


    /**
     * @param DeferredTranslation|string $label
     * @param DeferredRoute|string       $target
     *
     * @return $this
     */
    public function messageAction ($label, $target) : self
    {
        if (!\is_string($target) && !$target instanceof DeferredRoute)
        {
            throw new UnexpectedTypeException($target, DeferredRoute::class . " or string");
        }

        if (!\is_string($label) && !$label instanceof DeferredTranslation)
        {
            throw new UnexpectedTypeException($label, DeferredTranslation::class . " or string");
        }

        $this->messageActionLabel = $label;
        $this->messageActionTarget = $target;

        return $this;
    }



    /**
     * Builds the actual response
     */
    public function build () : JsonResponse
    {
        $data = [
            "ok" => $this->ok,
            "status" => $this->status,
            "data" => $this->data,
        ];

        if (null !== $this->redirect)
        {
            $data["redirect"] = DeferredRoute::generateValue($this->redirect, $this->urlGenerator);
        }

        if (null === $this->message && null !== $this->messageActionLabel)
        {
            throw new ResponseBuilderException("Can't build response with message action, but without a message.");
        }

        if (null !== $this->message)
        {
            $message = [
                "text" => DeferredTranslation::translateValue($this->message, $this->translator),
                "impact" => $this->messageImpact,
            ];

            if (null !== $this->messageActionLabel)
            {
                $message["action"] = [
                    "label" => DeferredTranslation::translateValue($this->messageActionLabel, $this->translator),
                    "url" => DeferredRoute::generateValue($this->messageActionTarget, $this->urlGenerator),
                ];
            }

            $data["message"] = $message;
        }

        return new JsonResponse($data);
    }
}
