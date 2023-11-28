<!-- Division pour le sommaire -->
<nav class="menuLeft">
    <ul class="menu-ul">
        <li class="menu-item"><a href="index.php">retour</a></li>

        <li class="menu-item">
            Visiteur :<br>
            <?php echo $_SESSION['prenom'] . "  " . $_SESSION['nom'] ?>
        </li>

        <li class="menu-item">
            <a href="index.php?uc=etatFrais&action=selectionnerMois" title="Consultation de mes fiches de frais">Mes
                fiches de frais</a>
        </li>

        <li class="menu-item">
            <a href="index.php?uc=etatFrais&action=selectionnerMoisAnneeFrais" title="Frais par mois">Frais par mois</a>
        </li>

        <li class="menu-item">
            <a href="index.php?uc=etatFrais&action=selectionnerVisiteurTypeFrais" title="Frais par visiteur">Frais par visiteur</a>
        </li>

        <li class="menu-item">
            <a href="index.php?uc=etatFrais&action=selectionnerPeriode" title="Frais par période">Frais par période</a>
        </li>

        <li class="menu-item">
            <a href="index.php?uc=etatFrais&action=selectionnerVisiteur" title="Frais Période/Visiteur">Frais Période/Visiteur </a>
        </li>

        <li class="menu-item">
            <a href="index.php?uc=etatFrais&action=formulaire" title="Saisie Frais">Saisie Frais</a>
        </li>

        <li class="menu-item">
            <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
        </li>
    </ul>
</nav>
<section class="content">


