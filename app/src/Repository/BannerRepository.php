<?php

namespace Signature\Repository;

use DateTime;
use Signature\Model\Banner;
use Signature\Service\Database;

class BannerRepository 
{
    public function __construct(
		private Database $db
	) {}

    public function list(): array
    {
        $bannerList = [];
		$result = $this->db->list("banners");

		foreach ($result as $bannerData) {
			$bannerList[] = $this->hydrate($bannerData);
		}

        return $bannerList;
    }

    public function add(array $values, string $file): Banner
	{		
		$values['mimeType'] = mime_content_type($file);
		$values['createdAt'] = new DateTime();
		$values['updatedAt'] = new DateTime();
		$values['createdAt'] = $values['createdAt']->format('Y-m-d');
		$values['updatedAt'] = $values['updatedAt']->format('Y-m-d');
        return $this->find($this->db->add('banners', $values));
	}

    public function edit(int $bannerId, array $values): Banner
	{	
		$values['mimeType'] = mime_content_type($values['extension']);
		$values['updatedAt'] = new DateTime();
		$values['updatedAt'] = $values['updatedAt']->format('Y-m-d');
		$this->db->edit('banners', $bannerId, $values);
		return $this->find($bannerId);
	}

    public function delete(int $bannerId)
	{
		$this->db->delete('banners', $bannerId);
	}

	public function find(int $bannerId): Banner  
	{
		$bannerData = $this->db->find('banners', $bannerId);

		if (!$bannerData) {
            return null;
        }

		return $this->hydrate($bannerData);
	}

	private function hydrate(array $bannerData): Banner 
    {
		$banner = new Banner(
			$bannerData['id'],
			$bannerData['name'],
			$bannerData['extension'],
			$bannerData['mimeType'],
			new DateTime($bannerData['createdAt']),
			new DateTime($bannerData['updatedAt'])
		);

		return $banner;
    }

}

?>