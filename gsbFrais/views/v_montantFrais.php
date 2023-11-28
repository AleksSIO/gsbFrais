<div id="contenu">
  <div class="corpsForm">
    <h3>Frais au forfait du : <?php echo $numMois."/".$numAnnee ?></h3>
    <h4>Type de frais : <?php echo $leTypeFrais ?> </h4>
        <table class="listeLegere">
                <tr>
                    <th class="numvisiteur">Numéro de visiteur</th>
                    <th class='montant'>Montant</th>                
                </tr>
            <?php      
              foreach ( $lesInfosEtatFrais as $uneInfoEtatFrais ) :
          
          $numVisiteur = $uneInfoEtatFrais['numVisiteur'];
          $montant = $uneInfoEtatFrais['montant'];
        ?>
                <tr>
                    <td><?php echo $numVisiteur ?></td>
                    <td><?php echo $montant." €" ?></td>
                </tr>
            <?php 
              endforeach
        ?>
        </table>
  </div>
</div>
