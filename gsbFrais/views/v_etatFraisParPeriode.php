<div id="contenu">
  <div class="corpsForm">
    <h3>Cumul pour tous les visiteurs des Frais au forfait par poste : </h3>
    <h4>Période du : <?php echo $numMois."/".$numAnnee ?></h4>
        <table class="listeLegere">
                <tr>
                    <th class="numvisiteur">Numéro du visiteur</th>
                    <th class='rep'>Repas midi</th>    
                    <th class='nui'>Nuitée</th>   
                    <th class='etp'>Etape</th>   
                    <th class='km'>Km</th>               
                </tr>
            <?php      
              foreach ( $lesInfosParPeriode as $uneInfoParPeriode ) :
          
          $numVisiteur = $uneInfoParPeriode['numVisiteur'];
          $rep = $uneInfoParPeriode['REP'];
          $nui = $uneInfoParPeriode['NUI'];
          $etp = $uneInfoParPeriode['ETP'];
          $km = $uneInfoParPeriode['KM'];
        ?>
                <tr>
                    <td><?php echo $numVisiteur?></td>
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