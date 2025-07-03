<?php
namespace Codez\DistrictLounge\Core;

abstract class BaseController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
   
    protected function render($view, array $data = [])
    {
        $authData = getAuthData();
       
        // Fusionner avec les données passées
        $data = array_merge($authData, $data);
       
        extract($data);
        require_once __DIR__ . '/../app/Views/' . $view . '.php';
    }
   
    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }
   
    // GESTION DE L'AUTHENTIFICATION
    protected function loginUser($userId)
    {
        $_SESSION['users_id'] = $userId;
       
        // Récupérer les informations de l'utilisateur
        $userModel = new \Codez\DistrictLounge\Models\UserModel();
        $user = $userModel->getUserById($userId);
       
        if ($user) {
            $_SESSION['user_email'] = $user['user_email'];
            $_SESSION['type_libelle'] = getUserType($user['type_id']);
            $_SESSION['logged_in'] = true;
           
            // mettre à jour les données d'authentification après connexion
            updateAuthData();
        }
    }
   
    protected function logoutUser()
    {
        session_unset();
        session_destroy();
       
        // Demarrer une nouvelle session pour éviter les problèmes de session
        session_start();
       
        // Mettre à jour les données d'authentification globales 
        updateAuthData();
    }
   
    protected function isAuthenticated(): bool
    {
        // Appeler la méthode AuthConstants pour vérifier l'authentification
        return \AuthConstants::isConnected();
    }
   
    protected function getAuthenticatedUserId(): ?int
    {
        return $_SESSION['users_id'] ?? null;
    }
   
    protected function requireAuth()
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/?url=auth/login');
        }
    }
   
    protected function requireAdmin()
    {
        //Appeler la méthode statique
        if (!\AuthConstants::isAdmin()) {
            $this->redirect('/?url=unauthorized');
        }
    }
    
    // Methode supplémentaire pour différents types d'utilisateurs
    protected function requireClient()
    {
        if (!\AuthConstants::isClient()) {
            $this->redirect('/?url=unauthorized');
        }
    }
    
    protected function requireCommercialOrAdmin()
    {
        if (!\AuthConstants::isCommercialOrAdmin()) {
            $this->redirect('/?url=unauthorized');
        }
    }
    
    // Methode utilitaires pour vérifier les rôles
    protected function isAdmin(): bool
    {
        return \AuthConstants::isAdmin();
    }
    
    protected function isClient(): bool
    {
        return \AuthConstants::isClient();
    }
    
    protected function isCommercialOrAdmin(): bool
    {
        return \AuthConstants::isCommercialOrAdmin();
    }
    
    protected function getCurrentUser(): ?array
    {
        if (!$this->isAuthenticated()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['users_id'] ?? null,
            'email' => $_SESSION['user_email'] ?? null,
            'type' => $_SESSION['type_libelle'] ?? null
        ];
    }
   
    // GESTION DU TOKEN CSRF
    protected function getCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
   
    protected function verifyCsrfToken(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}