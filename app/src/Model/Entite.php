<?php

namespace Signature\Model;

class Entite
{
    public function __construct(
        public int $id,
        public string $name,
        public string $address,
        public string $numStandard,
        public string $site,
        public ?string $couleur = null,
        public ?Banner $banniereRef = null,
        public ?string $logoPath = null,
        public ?string $logoFooterPath = null,
        public ?string $linkX = null,
        public ?string $linkYoutube = null,
        public ?string $linkGitHub = null,
        public ?string $linkLinkedin = null,
    ) { }

    public function getRsLinkList(Entite $defaultEntite = null): ?array
    {
        $linksDefinition = [
            'github' => 'linkGitHub',
            'x-twitter' => 'linkX',
            'linkedin' => 'linkLinkedin',
            'youtube' => 'linkYoutube',
        ];
        $footerLinks = [];

        foreach ($linksDefinition as $svgName => $propertyName) {
            if ($this->$propertyName) {
                $footerLinks[] = [
                    'icon' => base64_encode(file_get_contents('img/icon/' . $svgName . '.svg')),
                    'link' => $this->$propertyName,
                ];
            } elseif ($defaultEntite && $defaultEntite->$propertyName ) {
                $footerLinks[] = [
                    'icon' => base64_encode(file_get_contents('img/icon/' . $svgName . '.svg')),
                    'link' => $defaultEntite->$propertyName,
                ];
            }
        }

        return $footerLinks;

    }
}