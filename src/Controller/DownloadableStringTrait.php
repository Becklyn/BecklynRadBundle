<?php declare(strict_types=1);

namespace Becklyn\Rad\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;

trait DownloadableStringTrait
{
    /**
     * Sends a string as download.
     *
     * If you want to send a file, use {@see AbstractController::file()} instead.
     */
    private function stringAsDownload (string $content, string $filename, string $contentType) : Response
    {
        $response = new Response();
        $headers = $response->headers;

        $headers->set('Cache-Control', 'private');
        $headers->set('Content-Type', $contentType);
        $headers->set(
            'Content-Disposition',
            HeaderUtils::makeDisposition(
                HeaderUtils::DISPOSITION_ATTACHMENT,
                $filename,
                \preg_replace('~[^\\x20-\\x7e]~', "", $filename)
            )
        );
        $headers->set('Content-Length', \strlen($content));
        $response->setContent($content);

        return $response;
    }
}
