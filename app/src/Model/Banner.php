<?php

namespace Signature\Model;

use DateTime;

class Banner
{
    public function __construct(
        public int $id,
        public string $name,
        public string $extension,
        public string $mimeType,
        public DateTime $createdAt,
        public DateTime $updatedAt
    ) { }
    
    public function getBannerPath(): string 
    {
        return "banner/$this->id.$this->extension";
    }

}
