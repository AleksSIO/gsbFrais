<div id="contenu">
  <div class="corpsForm">
    <h3>Frais au forfait du visiteur : <?php echo $leVisiteur ?> </h3>
    <h4>Type de frais : <?php echo $leTypeFrais ?> </h4>
        <table class="listeLegere">
                <tr>
                    <th class="mois">Mois</th>
                    <th class='montant'>Montant</th>                
                </tr>
            <?php      
              foreach ( $lesInfosVisiteurFrais as $uneInfoVisiteurFrais ) :
          
          $mois = $uneInfoVisiteurFrais['mois'];
          $numAnnee =substr( $mois,0,4);
		      $numMois =substr( $mois,4,2);
          $montant = $uneInfoVisiteurFrais['montant'];
        ?>
                <tr>
                    <td><?php echo $numMois."/".$numAnnee ?></td>
                    <td><?php echo $montant." €" ?></td>
                </tr>
            <?php 
              endforeach
        ?>
        </table>
  </div>
</div>