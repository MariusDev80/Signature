<?php 

namespace Signature\Controller\Profile;

use InvalidArgumentException;
use Signature\Controller\AbstractController;

class ProfileController extends AbstractController
{
    public function show() 
    {
        $this->render('profile/userInfo.html.twig');
    }

    public function edit()
    {   
        $errorList = [];
        $values = [];
        $entiteList = $this->container->getEntiteRepository()->list();

        if (!isset($_GET['userId'])) {
           throw new InvalidArgumentException(); 
        }

        $userId = (int)$_GET['userId'];
        $user = $this->container->getUserRepository()->find($userId);
        
        if (!$user) {
            throw new InvalidArgumentException(); 
        }

        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btn'])) {
           
            $values = [];
            $errorList = $this->validateUserForm($values);
            
            if (empty($errorList)) {
                $this->container->getUserRepository()->edit($user->id, $values);
                $this->addMessage('success', "Profil modifié avec succès !");
                $this->redirect('/profile');
            } 
        }

        return $this->render(
            'profile/edit.html.twig',
            [   
                'entiteList' => $entiteList,
                'user' => $user,    
                'values' => $values,
                'errorList' => $errorList
            ]
        );
    }

    private function validateUserForm(array &$values)
    {
        $errorList = [];

        $values['login'] = $_POST['login'];
        if ($error = $this->validateField('login', "#^[a-z]{3}$#")) {
            $errorList['login'] = $error;
        }
        if ($_POST['password']) {
            $values['password'] = $_POST['password'];
            if ($error = $this->validateField('password', '#^.{8,255}#')) {
                $errorList['password'] = $error;
            }
        }
        $values['name'] = $_POST['name'];
        if ($error = $this->validateField('name', "#^[A-Za-z][A-Za-z-]{3,40}$#", false)) {
            $errorList['name'] = $error;
        }
        $values['firstName'] = $_POST['firstName'];
        if ($error = $this->validateField('firstName', "#^[A-Za-z][A-Za-z-]{3,40}$#", false)) {
            $errorList['firstName'] = $error;
        }
        $values['poste'] = $_POST['poste'];
        if ($error = $this->validateField('poste', "#^[A-Za-z][A-Za-z-]{0,40}$#", false)) {
            $errorList['poste'] = $error;
        }
        $values['entite'] = (int)$_POST['entite'];
        if (!isset($_POST['entite'])) {
            $errorList['entite'] = 'Le champ entité est obligatoire';
        }          
        $values['numPro'] = $_POST['numPro'];
        if ($error = $this->validateField('numPro', "#^[\d]{10}$#", false)) {
            $errorList['numPro'] = $error;
        }

        return $errorList;
    }
}