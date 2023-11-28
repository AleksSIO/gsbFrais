<div id="contenu">
      <form action="index.php?uc=etatFrais&action=voirMontant" method="post">
      <div class="corpsForm">
	  <h1>Période</h1>
         
      <p>
	 
        <label for="lstMois" accesskey="n">Mois/Année : </label>
        <select id="lstMois" name="lstMois">
            <?php
			foreach ($lesMois as $unMois)
			{
			    $mois = $unMois['mois'];
				$numAnnee =  $unMois['numAnnee'];
				$numMois =  $unMois['numMois'];
				if($mois == $moisASelectionner){
				?>
				<option selected value="<?php echo $mois ?>"><?php echo  $numMois."/".$numAnnee ?> </option>
				<?php 
				}
				else{ ?>
				<option value="<?php echo $mois ?>"><?php echo  $numMois."/".$numAnnee ?> </option>
				<?php 
				}
			
			}
           
		   ?>
           
            
        </select>

        <label for="lstTypeFrais" accesskey="n">Type de frais : </label>
        <select id="lstTypeFrais" name="lstTypeFrais">
            <?php
			foreach ($lesFrais as $unFrais):
				?>
				<option value="<?php echo $unFrais['id'] ?>"><?=  $unFrais['id'] ?> </option>
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