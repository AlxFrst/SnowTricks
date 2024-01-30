<?php

namespace App\Service;


use function PHPUnit\Framework\throwException;

class FilterYoutubeUrlService
{
    public function __construct(protected IdExtractor $idExtractor)
    {
    }

    public function filterVideoLink(string $videoLink): string
    {
        if (strlen($videoLink) === 11) {
            $regExp = "/(\\w|-|_)+/";
            preg_match($regExp, $videoLink, $matches);
            if (!$matches) {
                throw new \RuntimeException("L'url n'est pas valide");
            }
            return $videoLink;
        }
        $videoLinkID = $this->idExtractor->getId($videoLink);
        if (null === $videoLinkID) {
            throw new \RuntimeException("L'url n'est pas valide");
        }
        return $videoLinkID;

    }
}