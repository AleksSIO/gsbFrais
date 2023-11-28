<div id="contenu">
      <form action="index.php?uc=etatFrais&action=voirEtatFraisParPeriode" method="post">
      <div class="corpsForm">
	  <h1>Période</h1>
         
      <p>
	 
        <label for="lstPeriodes" accesskey="n">Mois/Année : </label>
        <select id="lstPeriodes" name="lstPeriodes">
            <?php
			    foreach ($lesPeriodes as $unePeriode):

                    $mois = $unePeriode['mois'];
				    $numAnnee =  $unePeriode['numAnnee'];
				    $numMois =  $unePeriode['numMois'];
			?>
				<option value="<?php echo $unePeriode['mois'] ?>"><?php echo  $numMois."/".$numAnnee  ?> </option>
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