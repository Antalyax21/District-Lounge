<?php

namespace Codez\DistrictLounge\Controllers;

use Codez\DistrictLounge\Core\BaseController;
use Codez\DistrictLounge\Models\UserModel;
use Exception;

class AuthController extends BaseController
{
    public function login()
    {
        $this->loginForm();
    }

    public function loginForm()
    {
        $this->render('Auth/login', [
            'csrf' => $this->getCsrfToken()
        ]);
    }

    public function loginPost()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $csrf = $_POST['csrf_token'] ?? '';

            if (!$this->verifyCsrfToken($csrf)) {
                $this->render('Auth/login', [
                    'csrf' => $this->getCsrfToken(),
                    'error' => 'Token CSRF invalide.'
                ]);
                return;
            }

            $userModel = new UserModel();
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['user_password'])) {
                // Régénérer l'ID de session pour la sécurité
                session_regenerate_id(true);

                // Définir toutes les variables de session nécessaires
                $_SESSION['users_id'] = $user['users_id'];
                $_SESSION['user_email'] = $user['user_email'];
                $_SESSION['logged_in'] = true;
                $_SESSION['type_libelle'] = getUserType($user['type_id']);

                // Mettre à jour les données d'authentification après la connexion
                updateAuthData();

                $this->redirect('/?url=home/index');
            } else {
                $this->render('Auth/login', [
                    'csrf' => $this->getCsrfToken(),
                    'error' => 'Email ou mot de passe incorrect.'
                ]);
            }
        }
    }


    public function logout()
    {
        $this->logoutUser();
        $this->redirect('/?url=auth/login');
    }

    public function register()
    {
        $this->render('Auth/register', [
            'csrf' => $this->getCsrfToken()
        ]);
    }

    public function registerPost()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['password_confirm'] ?? '';
            $csrf = $_POST['csrf_token'] ?? '';

            // Validation du token CSRF
            if (!$this->verifyCsrfToken($csrf)) {
                $this->render('Auth/register', [
                    'csrf' => $this->getCsrfToken(),
                    'error' => 'Token CSRF invalide.'
                ]);
                return;
            }

            // Validation des mots de passe
            if ($password !== $confirm) {
                $this->render('Auth/register', [
                    'csrf' => $this->getCsrfToken(),
                    'error' => 'Les mots de passe ne correspondent pas.'
                ]);
                return;
            }

            // Validation de la longueur du mot de passe
            if (strlen($password) < 8) {
                $this->render('Auth/register', [
                    'csrf' => $this->getCsrfToken(),
                    'error' => 'Le mot de passe doit contenir au moins 8 caractères.'
                ]);
                return;
            }

            // Validation de la complexité du mot de passe
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/', $password)) {
                $this->render('Auth/register', [
                    'csrf' => $this->getCsrfToken(),
                    'error' => 'Le mot de passe doit contenir au moins une minuscule, une majuscule et un chiffre.'
                ]);
                return;
            }

            // Validation de l'email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->render('Auth/register', [
                    'csrf' => $this->getCsrfToken(),
                    'error' => 'Format d\'email invalide.'
                ]);
                return;
            }

            $userModel = new UserModel();

            // Vérifier si l'email existe déjà
            $existing = $userModel->findByEmail($email);
            if ($existing) {
                $this->render('Auth/register', [
                    'csrf' => $this->getCsrfToken(),
                    'error' => 'Un compte avec cet email existe déjà.'
                ]);
                return;
            }

            // Préparer les données utilisateur
            $userData = [
                'user_email' => $email,
                'user_password' => $password,
                'type_id' => 3 // ID du type "Client"
            ];

            try {
                $inserted = $userModel->create($userData);

                if ($inserted) {
                    $this->render('Auth/login', [
                        'csrf' => $this->getCsrfToken(),
                        'success' => 'Inscription réussie ! Vous pouvez maintenant vous connecter.'
                    ]);
                } else {
                    $this->render('Auth/register', [
                        'csrf' => $this->getCsrfToken(),
                        'error' => 'Erreur lors de l\'inscription. Veuillez réessayer.'
                    ]);
                }
            } catch (Exception $e) {
                // Log l'erreur sans exposer les détails
                error_log("Erreur inscription - Email: " . $email . " - Message: " . $e->getMessage());
                $this->render('Auth/register', [
                    'csrf' => $this->getCsrfToken(),
                    'error' => 'Erreur technique lors de l\'inscription.'
                ]);
            }
        } else {
            $this->redirect('/?url=auth/register');
        }
    }
}
