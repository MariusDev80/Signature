<?php 

namespace Signature\Controller\Marketing;

use InvalidArgumentException;
use Signature\Controller\AbstractController;
use Signature\Model\Banner;

class BannerController extends AbstractController
{
    public function listBanner()
    {   
        $this->render(
            'marketing/banner/list-banner.html.twig', 
            [
                'bannerList' => $this->container->getBannerRepository()->list(),
            ]
        );
    }

    public function addBanner()
    {   
        $errorList = [];
        $values = [];

        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btn'])) {
            
            $errorList = $this->validateBannerForm($values);

            if (empty($errorList)) {
                move_uploaded_file($_FILES['banner']['tmp_name'], 'banner/'.$_FILES['banner']['name']);
                $banner = $this->container->getBannerRepository()->add($values, 'banner/'.$_FILES['banner']['name']);
                rename('banner/'.$_FILES['banner']['name'], 'banner/'.$banner->id.'.'.$banner->extension);
                $this->addMessage('success', "La bannière a été ajoutée avec succès !");
                $this->redirect('/marketing/banner');
            }
        }
        
        return $this->render(
            'marketing/banner/add-banner.html.twig',
            [
                'errorList' => $errorList,
                'values' => $values
            ]
        );
    }

    public function deleteBanner()
    {   
        if (!isset($_GET['bannerId'])) {
            throw new InvalidArgumentException(); 
        }

        $bannerId = (int)$_GET['bannerId'];
        $banner = $this->container->getBannerRepository()->find($bannerId);
        $entiteWithBanner = $this->container->getEntiteRepository()->listHaving('banniereRef', $banner->id);


        if (!$banner) {
            throw new InvalidArgumentException(); 
        }

        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btn'])) {
            $btn = $_POST['btn'];

            if ($btn == "confirm" && !$entiteWithBanner){
                unlink($banner->getBannerPath());
                $this->container->getBannerRepository()->delete($banner->id);
                $this->addMessage('success', "La bannière a été supprimée avec succès !");
                $this->redirect('/marketing/banner');  
            } else {
                $this->addMessage('failure', "Annulation de la suppréssion de la bannière !");
                if ($entiteWithBanner && $btn == "confirm") {
                    $message = '';
                    $message .= "Les entités suivantes sont liées à cette bannière : ";
                    $nb = 0;
                    foreach ($entiteWithBanner as $entite) {
                        $message .= $entite->name . ',';
                        $nb++;
                        if($nb >= 10){
                            $message .= '...';
                            break; 
                        }
                    }
                    $this->addMessage('failure', $message);
                }
                $this->redirect('/marketing/banner');
            }
        }

        return $this->render(
            'marketing/banner/delete.html.twig',
            [
                'banner' => $banner
            ]
        );
    }

    public function editBanner()
    {   
        $errorList = [];
        $values = [];

        if (!isset($_GET['bannerId'])) {
           throw new InvalidArgumentException(); 
        }

        $bannerId = (int)$_GET['bannerId'];
        $banner = $this->container->getBannerRepository()->find($bannerId);
        
        if (!$banner) {
            throw new InvalidArgumentException(); 
        }

        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btn'])) {

            $values = [];
            $errorList = $this->validateBannerForm($values, true);
            
            if (empty($errorList)) {
                
                // on verifie si une nouvelle bannière est presente dans la variable $_FILES car uniquement le nom a pu être modifié
                if ($_FILES['banner']['name'] != ""){
                    move_uploaded_file($_FILES['banner']['tmp_name'], 'banner/'.$_FILES['banner']['name']);
                    $banner = $this->container->getBannerRepository()->edit($banner->id, $values, 'banner/'.$_FILES['banner']['name']);
                    rename('banner/'.$_FILES['banner']['name'], 'banner/'.$banner->id.'.'.$banner->extension);
                } else {
                    $this->container->getBannerRepository()->edit($banner->id, $values);
                }
                $this->addMessage('success', "La bannière a été modifiée avec succès !");
                $this->redirect('/marketing/banner');
            } 
        }

        return $this->render(
            'marketing/banner/edit-banner.html.twig',
            [
                'banner' => $banner,
                'values' => $values,
                'errorList' => $errorList
            ]
        );
    }

    private function validateBannerForm(array &$values, bool $edition = false)
    {
        $errorList = [];
        $extension = "";

        $values['name'] = $_POST['name'];
        if ($error = $this->validateField('name', "#^[\dA-Za-z-_ ]{1,255}$#")) {
            $errorList['name'] = $error;
        }

        $values['link'] = $_POST['link'];
        if ($error = $this->validateField('link', "#^.{0,255}$#")) {
            $errorList['link'] = $error;
        }

        $publicDir = $this->getPublicDir();
        if (!is_dir($publicDir . 'banner')) {
            mkdir($publicDir . 'banner');
        } 

        if ((!isset($_FILES['banner']) || !isset($_FILES['banner']['name']) || $_FILES['banner']['name'] == "") && !$edition) {
            $errorList['banner'] = 'Aucun fichier séléctionné';
        } elseif ($_FILES['banner']['name'] != "") {
            $extension = pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION);
            $values['extension'] = $extension;
            if ($extension != 'jpeg' && $extension != 'jpg' && $extension != 'svg' && $extension != 'png') {
                $errorList['banner'] = 'Erreur de format du fichier (jpeg/jpg/svg/png acceptés)';
            } 
        }

        return $errorList;
    }
}