<?php declare(strict_types=1);

namespace Becklyn\Rad\Translation;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Simplified translator to translate backend texts.
 */
class BackendTranslator
{
    private TranslatorInterface $translator;


    /**
     */
    public function __construct (TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }


    /**
     * Translates the given message as backend text.
     */
    public function t (?string $id, array $parameters = [], ?string $locale = null) : ?string
    {
        return null !== $id
            ? $this->translator->trans($id, $parameters, "backend", $locale)
            : null;
    }


    /**
     * Translates all messages.
     *
     * @param (string|null|array)[] $messages
     *
     * @return (string|null)[]
     */
    public function transAll (array $messages) : array
    {
        $result = [];

        foreach ($messages as $key => $message)
        {
            // the translation value can be an array of ["id", [parameters]]
            if (\is_array($message))
            {
                $result[$key] = $this->t($message[0], $message[1] ?? []);
                continue;
            }

            $result[$key] = $this->t($message);
        }

        return $result;
    }
}
