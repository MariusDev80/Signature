<?php 

namespace Signature\Controller\Admin;

use InvalidArgumentException;
use Signature\Controller\AbstractController;
use Signature\Model\User;

class UserController extends AbstractController
{
    public function listUser()
    {   
        $this->render(
            'admin/user/list-user.html.twig', 
            [
                'userList' => $this->container->getUserRepository()->list()
            ]
        );
    }

    public function addUser()
    {   
        $errorList = [];
        $values = [];
        $entiteList = $this->container->getEntiteRepository()->list();

        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btn'])) {
            
            $errorList = $this->validateUserForm($values, false);

            if (empty($errorList)) {
                $this->container->getUserRepository()->add($values);
                $this->addMessage('success', "L'utilisateur a été ajouté avec succès !");
                $this->redirect('/admin/user');
            }
        }
        
        return $this->render(
            'admin/user/add-user.html.twig',
            [   
                'entiteList' => $entiteList,
                'errorList' => $errorList,
                'values' => $values
            ]
        );
    }

    public function deleteUser()
    {   
        if (!isset($_GET['userId'])) {
            throw new InvalidArgumentException(); 
        }

        $userId = (int)$_GET['userId'];
        $user = $this->container->getUserRepository()->find($userId);

        /** @var User $user */
        if (!$user) {
            throw new InvalidArgumentException(); 
        }

        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btn'])) {
            $btn = $_POST['btn'];

            if ($btn == "confirm"){
                $this->container->getUserRepository()->delete($user->id);
                $this->addMessage('success', "L'utilisateur a été supprimé avec succès !");
                $this->redirect('/admin/user');  
            } else {
                $this->addMessage('failure', "Annulation de la suppression de l'utilisateur !");
                $this->redirect('/admin/user');
            }
        }

        return $this->render(
            'admin/user/delete.html.twig',
            [
                'user' => $user
            ]
        );
    }

    public function editUser()
    {   
        $errorList = [];
        $values = [];
        $entiteList = $this->container->getEntiteRepository()->list();

        if (!isset($_GET['userId'])) {
           throw new InvalidArgumentException(); 
        }

        $userId = (int)$_GET['userId'];
        $user = $this->container->getUserRepository()->find($userId);
        
        /** @var User $user */
        if (!$user) {
            throw new InvalidArgumentException(); 
        }

        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btn'])) {
            

            $values = [];
            $errorList = $this->validateUserForm($values, true);
            
            if (empty($errorList)) {
                $this->container->getUserRepository()->edit($user->id, $values);
                $this->addMessage('success', "L'utilisateur a été modifié avec succès !");
                $this->redirect('/admin/user');
            } 
        }

        return $this->render(
            'admin/user/edit-user.html.twig',
            [   
                'entiteList' => $entiteList,
                'user' => $user,    
                'values' => $values,
                'errorList' => $errorList
            ]
        );
    }

    private function validateUserForm(array &$values, bool $editing)
    {
        $errorList = [];

        $values['login'] = $_POST['login'];
        if ($error = $this->validateField('login', "#^[a-z]{3}$#")) {
            $errorList['login'] = $error;
        }
        if (!isset($_POST['password']) && $_POST['password'] && !$editing) {
            $errorList['password'] = "Le champ 'Mot de passe' est vide";
        } else {
            if ($error = $this->validateField('password', '#^.{8,255}#', false)) {
                $errorList['password'] = $error;
            }

            if (!isset($_POST['passwordConfirm']) && $_POST['passwordConfirm']) {
                $errorList['passwordConfirm'] = "Le champ 'Confirmation Mot de passe' est vide";
            } else {
                if ($_POST['password'] == $_POST['passwordConfirm']) {
                    $values['password'] = $_POST['password'];
                } else {
                    $errorList['passwordConfirm'] = "Le mot de passe de confirmation n'est pas identique";
                }
            }
        }
        $values['name'] = $_POST['name'];
        if ($error = $this->validateField('name', "#^[A-Za-z][A-Za-z-]{2,40}$#")) {
            $errorList['name'] = $error;
        }
        $values['firstName'] = $_POST['firstName'];
        if ($error = $this->validateField('firstName', "#^[A-Za-z][A-Za-z-]{2,40}$#")) {
            $errorList['firstName'] = $error;
        }
        $values['poste'] = $_POST['poste'];
        if ($error = $this->validateField('poste', "#^[A-Za-z][A-Za-z-]{0,40}$#")) {
            $errorList['poste'] = $error;
        }
        $values['entite'] = (int)$_POST['entite'];
        if (!isset($_POST['entite'])) {
            $errorList['entite'] = 'Le champ entité est obligatoire';
        }          
        $values['email'] = $_POST['email'];
        if ($error = $this->validateField('email', "#^.{0,255}$#")) {
            $errorList['poste'] = $error;
        }
        $values['numPro'] = $_POST['numPro'];
        if ($error = $this->validateField('numPro', "#^[\d]{10}$#", false)) {
            $errorList['numPro'] = $error;
        }

        $values['isAdmin'] = isset($_POST['isAdmin']) ? 1 : 0;
        $values['isMarketing'] = isset($_POST['isMarketing'])? 1 : 0;

        return $errorList;
    }
}
