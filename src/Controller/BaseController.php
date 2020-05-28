<?php declare(strict_types=1);

namespace Becklyn\RadBundle\Controller;

use Becklyn\RadBundle\Ajax\AjaxResponseBuilder;
use Becklyn\RadBundle\Exception\EntityRemovalBlockedException;
use Becklyn\RadBundle\Exception\LabeledEntityRemovalBlockedException;
use Becklyn\RadBundle\Form\FormErrorMapper;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Base class for all controllers.
 */
abstract class BaseController extends AbstractController
{
    /**
     * @param string      $id     #TranslationKey
     * @param string|null $domain #TranslationDomain
     */
    protected function trans (string $id, array $parameters = [], ?string $domain = null, ?string $locale = null) : string
    {
        return $this->get(TranslatorInterface::class)->trans($id, $parameters, $domain, $locale);
    }


    /**
     * Fetches the Entity remove message from an exception.
     */
    protected function getEntityRemovalMessage (\Exception $exception) : string
    {
        switch (true)
        {
            case $exception instanceof LabeledEntityRemovalBlockedException:
                $message = $exception->getFrontendMessage();
                break;

            case $exception instanceof EntityRemovalBlockedException:
                $message = "entity_removal.failed.generic_blocked";
                break;

            case $exception->getPrevious() instanceof ForeignKeyConstraintViolationException:
            case $exception instanceof ForeignKeyConstraintViolationException:
                // this entity is still referenced
                $message = "entity_removal.failed.foreign_key";
                break;

            default:
                // unknown cause of failed removal
                // -> use generic error message
                $message = "entity_removal.failed.generic";
                // -> log the error
                $this->get(LoggerInterface::class)->error("Removal of an entity failed due to unspecified reasons.", [
                    "exception" => $exception,
                ]);
                break;
        }

        return $this->trans($message, [], "backend");
    }


    /**
     * Returns the form error mapping for the given form.
     */
    protected function getFormErrorMapping (FormInterface $form) : array
    {
        $mapper = new FormErrorMapper($this->get(TranslatorInterface::class));
        return $mapper->generate($form);
    }


    /**
     * Returns the logger.
     */
    protected function getLogger () : LoggerInterface
    {
        return $this->get(LoggerInterface::class);
    }


    /**
     * Returns the parsed JSON formatted request body.
     */
    protected function getJsonRequestData (Request $request) : array
    {
        if ("json" !== $request->getContentType())
        {
            throw new HttpException(415, "Expected JSON request content type.");
        }

        try
        {
            $data = \json_decode($request->getContent(), true, 512, \JSON_THROW_ON_ERROR);

            if (!\is_array($data))
            {
                throw new HttpException(
                    400,
                    \sprintf("Invalid JSON structure received, expected list / key-value map, got %s", \gettype($data))
                );
            }

            return $data;
        }
        catch (\JsonException $e)
        {
            throw new HttpException(
                400,
                "Invalid JSON received, error: {$e->getMessage()}",
                $e
            );
        }
    }


    /**
     * Creates an AJAX response and returns its builder.
     */
    protected function ajaxResponse (bool $ok, string $status) : AjaxResponseBuilder
    {
        $builder = new AjaxResponseBuilder(
            $this->get(TranslatorInterface::class),
            $this->get(RouterInterface::class)
        );

        return $builder->setStatus($ok, $status);
    }


    /**
     * @inheritDoc
     */
    public static function getSubscribedServices ()
    {
        $services = parent::getSubscribedServices();
        $services[] = LoggerInterface::class;
        $services[] = TranslatorInterface::class;

        return $services;
    }
}
