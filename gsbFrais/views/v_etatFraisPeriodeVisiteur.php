<div id="contenu">
  <div class="corpsForm">
    <h3>Cumul pour tous les mois des Frais au forfait par poste : </h3>
    <h4>Visiteur : <?php echo $leVisiteur ?></h4>
        <table class="listeLegere">
                <tr>
                    <th class="mois">Mois</th>
                    <th class='rep'>Repas midi</th>    
                    <th class='nui'>Nuitée</th>   
                    <th class='etp'>Etape</th>   
                    <th class='km'>Km</th>               
                </tr>
            <?php      
              foreach ( $lesInfosPeriodeVisiteur as $uneInfoPeriodeVisiteur ) :
          
          $mois = $uneInfoPeriodeVisiteur['mois'];
          $numAnnee =substr( $mois,0,4);
		      $numMois =substr( $mois,4,2);
          $rep = $uneInfoPeriodeVisiteur['REP'];
          $nui = $uneInfoPeriodeVisiteur['NUI'];
          $etp = $uneInfoPeriodeVisiteur['ETP'];
          $km = $uneInfoPeriodeVisiteur['KM'];
        ?>
                <tr>
                    <td><?php echo $numMois."/".$numAnnee ?></td>
                    <td><?php echo $rep." €" ?></td>
                    <td><?php echo $nui." €"  ?></td>
                    <td><?php echo $etp." €"  ?></td>
                    <td><?php echo $km." €"  ?></td>
                </tr>
            <?php 
              endforeach
        ?>
        </table>
  </div>
</div>