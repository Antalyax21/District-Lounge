<!-- Vue register.php -->
<form action="/?url=auth/registerPost" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
    
    <div>
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
    </div>
    
    <div>
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
    </div>
    
    <div>
        <label for="password_confirm">Confirmer le mot de passe :</label>
        <input type="password" id="password_confirm" name="password_confirm" required>
    </div>
    
    <button type="submit">S'inscrire</button>
    
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    
    <?php if (isset($success)): ?>
        <p style="color: green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>
</form>

<a href="/?url=auth/login">Déjà un compte ? Se connecter</a>