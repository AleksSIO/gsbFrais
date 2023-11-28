<div id="contenu">
      <form action="index.php?uc=etatFrais&action=voirFraisPeriodeVisiteur" method="post">
      <div class="corpsForm">
	  <h1>Visiteur</h1>
         
      <p>
	 
        <label for="lstVisiteurs" accesskey="n">Numéro: </label>
        <select id="lstVisiteurs" name="lstVisiteurs">
            <?php
			foreach ($lesVisiteurs as $unVisiteur) :
		
				?>
				<option value="<?php echo $unVisiteur['idVisiteur']  ?>"><?php echo  $unVisiteur['idVisiteur']  ?> </option>
				<?php 
			
			endforeach
           
		   ?>
           
            
        </select>

      </p>
      </div>
      <div class="piedForm">
      <p>
        <input id="ok" type="submit" value="Valider" size="20" />
        <input id="annuler" type="reset" value="Effacer" size="20" />
      </p> 
      </div>
        
      </form>