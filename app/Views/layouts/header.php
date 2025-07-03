<!DOCTYPE html>
<html>
<head>
    <title>District Lounge</title>
</head>
<body>
    <header>
        <nav>
            <?php if (AuthConstants::isConnected()): ?>
                <span>Bienvenue, <?= htmlspecialchars(AuthConstants::getUserEmail() ?? 'Utilisateur') ?></span>
                <p>Vous êtes connecté !</p>
                <a href="/?url=auth/logout">Déconnexion</a>
               
                <?php if (AuthConstants::isAdmin()): ?>
                    <a href="/?url=admin/dashboard">Administration</a>
                <?php elseif (AuthConstants::isCommercialOrAdmin()): ?>
                    <a href="/?url=commercial/dashboard">Espace Commercial</a>
                <?php elseif (AuthConstants::isClient()): ?>
                    <a href="/?url=client/dashboard">Mon Espace</a>
                <?php endif; ?>
               
            <?php else: ?>
                <a href="/?url=auth/login">Connexion</a>
                <a href="/?url=auth/register">Inscription</a>
            <?php endif; ?>
        </nav>
    </header>
   
    <!-- Debug -->
    <div style="background: #f0f0f0; padding: 10px; margin: 10px 0; font-size: 12px;">
        <strong>DEBUG SESSION:</strong><br>
        isConnected: <?= var_export(AuthConstants::isConnected(), true) ?><br>
        userEmail: <?= htmlspecialchars($_SESSION['user_email'] ?? 'non défini') ?><br>
        type_libelle: <?= htmlspecialchars($_SESSION['type_libelle'] ?? 'non défini') ?><br>
        userId: <?= htmlspecialchars($_SESSION['users_id'] ?? 'non défini') ?><br>
       
        SESSION users_id: <?= htmlspecialchars($_SESSION['users_id'] ?? 'non défini') ?><br>
        SESSION user_email: <?= htmlspecialchars($_SESSION['user_email'] ?? 'non défini') ?><br>
        SESSION type_libelle: <?= htmlspecialchars($_SESSION['type_libelle'] ?? 'non défini') ?><br>
        SESSION logged_in: <?= var_export($_SESSION['logged_in'] ?? false, true) ?><br>
       
        <strong>CONSTANTES DYNAMIQUES:</strong><br>
        IS_CONNECTED: <?= var_export(AuthConstants::isConnected(), true) ?><br>
        IS_CLIENT: <?= var_export(AuthConstants::isClient(), true) ?><br>
        IS_ADMIN: <?= var_export(AuthConstants::isAdmin(), true) ?><br>
    </div>
    
    <main>
        <!-- le contenu plus tard -->
    </main>
</body>
</html>