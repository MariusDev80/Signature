<?php 

namespace Signature\Controller\Admin;

use InvalidArgumentException;
use Signature\Controller\AbstractController;

class EntiteController extends AbstractController
{
    public function listEntite()
    {   
        $this->render(
            'admin/entite/list-entite.html.twig', 
            [
                'entiteList' => $this->container->getEntiteRepository()->list()
            ]
        );
    }

    public function addEntite()
    {   
        $errorList = [];
        $values = [];
        $bannerList = $this->container->getBannerRepository()->list();

        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btn'])) {
            
            
            $errorList = $this->validateEntiteForm($values);
            
            if (empty($errorList)) {
                $entite = $this->container->getEntiteRepository()->add($values);
                $this->addMessage('success', "L'entité a été ajoutée avec succès !");
                $this->redirect('/admin/entite');
            }
        }
        
        return $this->render(
            'admin/entite/add-entite.html.twig',
            [
                'errorList' => $errorList,
                'values' => $values,
                'bannerList' => $bannerList,
            ]
        );
    }

    public function deleteEntite()
    {   
        $entiteId = (int)$_GET['entiteId'];
        $entite = $this->container->getEntiteRepository()->find($entiteId);
        $usersWithEntite = $this->container->getUserRepository()->listHaving('entite', $entite->id);

        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btn'])) {
            $btn = $_POST['btn'];

            if ($btn == "confirm" && !$usersWithEntite){
                $this->container->getEntiteRepository()->delete($entiteId);
                $this->addMessage('success', "L'entité a été supprimée avec succès !");
                $this->redirect('/admin/entite');  
            } else {
                $this->addMessage('failure', "Annulation de la suppression de l'entité !");
                if ($usersWithEntite && $btn == "confirm") {
                    $message = '';
                    $message .= "Les utilisateurs suivants sont liés à cette entité : ";
                    $nb = 0;
                    foreach ($usersWithEntite as $user) {
                        $message .= $user->firstName . ' ' . $user->name . ',';
                        $nb++;
                        if($nb >= 10){
                            $message .= '...';
                            break; 
                        }
                    }
                    $this->addMessage('failure', $message);
                }
                $this->redirect('/admin/entite');
            }
        }

        return $this->render(
            'admin/entite/delete.html.twig',
            [
                'entiteId' => $entiteId,
                'entite' => $entite
            ]
        );
    }

    public function editEntite()
    {   
        $errorList = [];
        $values = [];
        $bannerList = $this->container->getBannerRepository()->list();

        if (!isset($_GET['entiteId'])) {
           throw new InvalidArgumentException(); 
        }

        $entiteId = (int)$_GET['entiteId'];
        $entite = $this->container->getEntiteRepository()->find($entiteId);
        
        /** @var Entite $entite */
        if (!$entite) {
            throw new InvalidArgumentException(); 
        }

        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btn'])) {
            
            $values = [];
            $errorList = $this->validateEntiteForm($values, true);
            
            if (empty($errorList)) {

                $entite = $this->container->getEntiteRepository()->edit($entiteId, $values);
                $this->addMessage('success', "L'entité a été modifié avec succès !");
                $this->redirect('/admin/entite');
            } 
        }

        return $this->render(
            'admin/entite/edit-entite.html.twig',
            [
                'entite' => $entite,
                'values' => $values,
                'errorList' => $errorList,
                'bannerList' => $bannerList,
            ]
        );
    }

    private function validateEntiteForm(array &$values, bool $edition = false)
    {
        $errorList = [];

        $values['name'] = $_POST['name'];
        if ($error = $this->validateField('name', "#^[A-Za-z][A-Za-z- ]{3,40}$#")) {
            $errorList['name'] = $error;
        }
        $values['address'] = $_POST['address'];
        if ($error = $this->validateField('address', "#^[\dA-Za-z-, <br> \r]{3,255}$#")) {
            $errorList['address'] = $error;
        }
        $values['numStandard'] = $_POST['numStandard'];
        if ($error = $this->validateField('numStandard', "#^[\d]{10}$#")) {
            $errorList['numStandard'] = $error;
        }
        $values['couleur'] = $_POST['couleur'];
        if ($error = $this->validateField('couleur', "@^#[\da-z]{6}$@", false)) {
            $errorList['couleur'] = $error;
        }
        $values['banniereRef'] = (int)$_POST['banniereRef'];
        if ($error = $this->validateField('banniereRef', "#^[\d]{1,3}$#")) {
            $errorList['banniereRef'] = $error;
        }
        $values['link'] = $_POST['link'];
        if ($error = $this->validateField('link', "#^.{0,255}$#")) {
            $errorList['link'] = $error;
        }
        $values['linkX'] = $_POST['linkX'];
        if ($error = $this->validateField('linkX', "#^.{0,255}$#", false)) {
            $errorList['address'] = $error;
        }
        $values['linkYoutube'] = $_POST['linkYoutube'];    
        if ($error = $this->validateField('linkYoutube', "#^.{0,255}$#", false)) {
            $errorList['address'] = $error;
        }
        $values['linkGitHub'] = $_POST['linkGitHub'];
        if ($error = $this->validateField('linkGitHub', "#^.{0,255}$#", false)) {
            $errorList['address'] = $error;
        }
        $values['linkLinkedin'] = $_POST['linkLinkedin'];
        if ($error = $this->validateField('linkLinkedin', "#^.{0,255}$#", false)) {
            $errorList['address'] = $error;
        }
        
        return $errorList;
    }
}
