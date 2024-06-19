<?php

namespace Signature\Repository;

use Signature\Model\Entite;
use Signature\Service\Database;

class EntiteRepository 
{
    public function __construct(
        private Database $db,
        private BannerRepository $bannerRepository,
    ) {}


    public function list(): array
    {
        $entiteList = [];

        $result = $this->db->list("entite");
        foreach ($result as $entiteData) {
            $entiteList[] = $this->hydrate($entiteData);
        }

        return $entiteList;
    }

    public function listHaving(string $colName, $value): array
    {
        $entiteList = [];

        $result = $this->db->listHaving("entite", $colName, $value);
        foreach ($result as $entiteData) {
            $entiteList[] = $this->hydrate($entiteData);
        }

        return $entiteList;
    }

    public function add(array $values): Entite
	{
        return $this->find($this->db->add('entite', $values));
	}

    public function edit(int $entiteId, array $values): Entite
    {

		$this->db->edit('entite', $entiteId, $values);
        return $this->find($entiteId);
	}

    public function delete(int $entiteId)
	{
		$this->db->delete('entite', $entiteId);
	}

    public function identify(string $name): ?Entite
    {
        $entiteData = $this->db->findBy(
            'entite',
            [ 
                'name'=> $name,
            ],
        );
        
        if (!$entiteData) {
            return null;
        }

        return $this->hydrate($entiteData);
    }

	public function find(int $entiteId) : Entite
	{
		$entiteData = $this->db->find('entite', $entiteId);

        if (!$entiteData) {
            return null;
        }

		return $this->hydrate($entiteData);
	}

    private function hydrate(array $entiteData): Entite 
    {
		$entite = new Entite(
			$entiteData['id'],
			$entiteData['name'],
			$entiteData['address'],
			$entiteData['numStandard'],
            $entiteData['site'],
			$entiteData['couleur'],
			$this->bannerRepository->find($entiteData['banniereRef']),
            $entiteData['logoPath'],
            $entiteData['logoFooterPath'],
            $entiteData['linkX'],
            $entiteData['linkYoutube'],
            $entiteData['linkGitHub'],
            $entiteData['linkLinkedin'],
		);

		return $entite;
    }
}

?>