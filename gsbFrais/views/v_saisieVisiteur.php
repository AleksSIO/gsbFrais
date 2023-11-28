<div id="contenu">
      <form action="index.php?uc=etatFrais&action=saisie" method="post">
      <div class="corpsForm">
	  <h1>Saisie</h1>
      
      <p>
        
        <label for="lstVisiteurs" accesskey="n">Numéro de visiteur : </label>
            
        <select id="lstVisiteurs" name="lstVisiteurs" style="width: 77px;">
            <?php
            foreach ($lesVisiteurs as $unVisiteur) :
            
            ?>
            <option value="<?php echo $unVisiteur['idVisiteur']  ?>"><?php echo  $unVisiteur['idVisiteur']  ?> </option>
            <?php 
                
            endforeach
            
            ?>  
        </select>

        <br><br>

        <label for="mois" accesskey="n">Mois (2 chiffres) : </label>
     
        <input type="text" name="mois" id="mois" value="<?php echo $mois ?>" size="6" style="margin-left: 1.75%">
      
        <br><br>

        <label for="annee" accesskey="n">Année (4 chiffres) : </label>

        <input type="text" name="annee" id="annee" value="<?php echo $annee ?>"size="6" style="margin-left: 0.75%">


     
    </p>
    <p>
        <input id="ok" type="submit" name="Rechercher" value="Rechercher" size="20" />
      </p> 

    <h2>Frais au forfait</h2>
    
    <p>
        <input type="hidden" value="<?php echo $idVisiteur ?>">

        <label for="REP">Repas midi : </label>
        <input type="text" name="rep" id="REP" value="<?php echo $rep ?>" size="6" style="margin-left: 5.75%">

        <br><br>

        <label for="NUI">Nuitées : </label>
        <input type="text" name="nui" id="NUI" value="<?php echo $nui ?>" size="6" style="margin-left: 8.5%">

        <br><br>

        <label for="ETP">Etape : </label>
        <input type="text" name="etp" id="ETP" value="<?php echo $etp ?>" size="6" style="margin-left: 9.75%">

        <br><br>

        <label for="KM">Km : </label>
        <input type="text" name="km" id="KM"  value="<?php echo $km ?>" size="6" style="margin-left: 11.15%">
    </p>
      </div>
      <div class="piedForm">
      <p>
        <input id="ok" type="submit" name="Envoyer" value="Valider" size="20" />
        <input id="annuler" type="reset" value="Effacer" size="20" />
      </p> 
      </div>
        
      </form>