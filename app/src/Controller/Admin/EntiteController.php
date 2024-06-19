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
                rename($_FILES['logo']['tmp_name'], "entite/logo/".$entite->logoPath);
                rename($_FILES['logoFooter']['tmp_name'], "entite/logoFooter/".$entite->logoFooterPath);
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
                $message = "Annulation de la suppréssion de l'entité !<br>";
                if ($usersWithEntite && $btn == "confirm") {
                    $message .= "Les utilisateurs suivants sont liés à cette entité : <br>";
                    $nb = 0;
                    foreach ($usersWithEntite as $user) {
                        $message .= $user->firstName . ' ' . $user->name . ',';
                        $nb++;
                        if($nb >= 10){
                            break; 
                        }
                    }
                    $message .= '...';
                }
                $this->addMessage('failure', $message);
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

                if ($_FILES['logo']['name'] != ""){
                    move_uploaded_file($_FILES['logo']['tmp_name'], $this->getPublicDir()."entite/logo/".$entite->logoPath);
                }

                if ($_FILES['logoFooter']['name'] != ""){
                    move_uploaded_file($_FILES['logoFooter']['tmp_name'], $this->getPublicDir()."entite/logoFooter/".$entite->logoFooterPath);
                }
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
        $values['site'] = $_POST['site'];
        if ($error = $this->validateField('site', "#^.{0,255}$#")) {
            $errorList['site'] = $error;
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
        if (!isset($_FILES['logo'])) {
            $errorList['logo'] = 'Aucun fichier séléctionné';
        } elseif ($_FILES['logo']['name'] != "") {
            $extension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            if ($extension != 'jpeg' && $extension != 'jpg' && $extension != 'svg' && $extension != 'png') {
                $errorList['logo'] = 'Erreur de format du fichier (jpeg/jpg/svg/png acceptés)';
            } else {
                $values['logoPath'] = $_FILES['logo']['name'];
            }
        }
        if (!isset($_FILES['logoFooter'])) {
            $errorList['logoFooter'] = 'Aucun fichier séléctionné';
        } elseif (!$_FILES['logoFooter']['name'] == "") {
            $extension = pathinfo($_FILES['logoFooter']['name'], PATHINFO_EXTENSION);
            if ($extension != 'jpeg' && $extension != 'jpg' && $extension != 'svg' && $extension != 'png') {
                $errorList['logoFooter'] = 'Erreur de format du fichier (jpeg/jpg/svg/png acceptés)';
            } else {
                $values['logoFooterPath'] = $_FILES['logoFooter']['name'];
            }
        }
        
        return $errorList;
    }
}
