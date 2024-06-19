<?php

namespace Signature\Service;

use Signature\Model\User;
use Signature\Repository\BannerRepository;
use Signature\Repository\EntiteRepository;
use Signature\Repository\UserRepository;

class Container
{
    private ?User $currentUser = null;

    private ?ViewRenderer $viewRenderer = null;
    
    private ?Database $database = null;
    private ?UserRepository $userRepository = null;
    private ?EntiteRepository $entiteRepository = null;
    private ?BannerRepository $bannerRepository = null;

    public function setCurrentUser(User $user)
    {
        $this->currentUser = $user;
    }

    public function loginUser($user): void 
    {   
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION['user_id'] = $user->id;
        $this->currentUser = $user;
    }

    public function logoutUser(): void
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    public function getCurrentUser(): ?User
    {
        return $this->currentUser;
    }

    public function getDatabase(): Database
    {
        if ($this->database === null) {
            $this->database = new Database();
        }

        return $this->database;
    }

    public function getUserRepository(): UserRepository
    {

        if ($this->userRepository === null) {
            $this->userRepository = new UserRepository(
                $this->getDatabase(),
                $this->getEntiteRepository(),
            );
        }

        return $this->userRepository;
    }

    public function getEntiteRepository(): EntiteRepository
    {

        if ($this->entiteRepository === null) {
            $this->entiteRepository = new EntiteRepository($this->getDatabase(),$this->getBannerRepository());
        }

        return $this->entiteRepository;
    }

    public function getBannerRepository(): BannerRepository
    {

        if ($this->bannerRepository === null) {
            $this->bannerRepository = new BannerRepository($this->getDatabase());
        }

        return $this->bannerRepository;
    }

    public function getViewRenderer(): ViewRenderer
    {
        if ($this->viewRenderer === null) {
            $this->viewRenderer = new ViewRenderer($this);
        }

        return $this->viewRenderer;
    }
}