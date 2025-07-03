<h2>Connexion</h2>
<form method="POST" action="/?url=auth/login">

    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
    <label>Email : <input type="email" name="email" required></label><br>
    <label>Mot de passe : <input type="password" name="password" required></label><br>
    <button type="submit">Se connecter</button>
</form>
<p>Pas encore de compte ? <a href="/?url=auth/register">Inscrivez-vous</a></p>