<?php
/** @var PdoGsb $pdo */
include 'views/v_sommaire.php';
$action = $_REQUEST['action'];
$idVisiteur = $_SESSION['idVisiteur'];
switch($action){
	case 'selectionnerMois':{
		$lesMois=$pdo->getLesMoisDisponibles($idVisiteur);
		// Afin de sélectionner par défaut le dernier mois dans la zone de liste,
		// on demande toutes les clés, et on prend la première,
		// les mois étant triés décroissants
		$lesCles = array_keys( $lesMois );
		$moisASelectionner = $lesCles[0];
		include("views/v_listeMois.php");
		break;
	}
	case 'voirEtatFrais':{
		$leMois = $_REQUEST['lstMois'];
		$lesMois=$pdo->getLesMoisDisponibles($idVisiteur);
		$moisASelectionner = $leMois;
		include("views/v_listeMois.php");
		$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,$leMois);
		$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur,$leMois);
		$numAnnee =substr( $leMois,0,4);
		$numMois =substr( $leMois,4,2);
		$libEtat = $lesInfosFicheFrais['libEtat'];
		$montantValide = $lesInfosFicheFrais['montantValide'];
		$nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
		$dateModif =  $lesInfosFicheFrais['dateModif'];
		
		//Gestion des dates
		@list($annee,$mois,$jour) = explode('-',$dateModif);
		$dateModif = "$jour"."/".$mois."/".$annee;

		//$dateModif =  dateAnglaisVersFrancais($dateModif);
		include("views/v_etatFrais.php");
		break;
	}
	case 'selectionnerMoisAnneeFrais':{
		$lesMois=$pdo->getLesMoisDisponibles($idVisiteur);
		$lesFrais=$pdo->getLesTdfDisponibles();
		// Afin de sélectionner par défaut le dernier mois dans la zone de liste,
		// on demande toutes les clés, et on prend la première,
		// les mois étant triés décroissants
		$lesCles = array_keys( $lesMois );
		$moisASelectionner = $lesCles[0];
		include("views/v_listeMoisAnneeFrais.php");
		break;
	}
	case 'voirMontant':{
		$leMois = $_REQUEST['lstMois'];
		$leTypeFrais = $_REQUEST['lstTypeFrais'];
		$lesMois=$pdo->getLesPeriodes();
		$lesFrais=$pdo->getLesTdfDisponibles();
		include("views/v_listeMoisAnneeFrais.php");
		$numAnnee =substr( $leMois,0,4);
		$numMois =substr( $leMois,4,2);
		$lesInfosEtatFrais = $pdo->getLesInfosEtatFrais($leMois,$leTypeFrais);
		
		include("views/v_montantFrais.php");
		break;
	}
	case 'selectionnerVisiteurTypeFrais':{
		$lesVisiteurs=$pdo->getLesVisiteurs();
		$lesFrais=$pdo->getLesTdfDisponibles();
		include("views/v_listeVisiteurTypeFrais.php");
		break;
	}
	case 'voirFraisParVisiteur':{
		$leVisiteur = $_REQUEST['lstVisiteurs'];
		$leTypeFrais = $_REQUEST['lstTypeFrais'];
		$lesVisiteurs=$pdo->getLesVisiteurs();
		$lesFrais=$pdo->getLesTdfDisponibles();
		include("views/v_listeVisiteurTypeFrais.php");
		$lesInfosVisiteurFrais = $pdo->getLesInfosVisiteurFrais($leVisiteur,$leTypeFrais);
		
		include("views/v_etatFraisParVisiteur.php");
		break;
	}
	case 'selectionnerPeriode':{
		$lesPeriodes=$pdo->getLesPeriodes();
		include("views/v_listePeriode.php");
		break;
	}
	case 'voirEtatFraisParPeriode':{
		$laPeriode = $_REQUEST['lstPeriodes'];
		$lesPeriodes=$pdo->getLesPeriodes();
		include("views/v_listePeriode.php");
		$numAnnee =substr( $laPeriode,0,4);
		$numMois =substr( $laPeriode,4,2);
		$lesInfosParPeriode = $pdo->getFraisPeriode($laPeriode);
		
		include("views/v_etatFraisParPeriode.php");
		break;
	}
	case 'selectionnerVisiteur':{
		$lesVisiteurs=$pdo->getLesVisiteurs();
		// Afin de sélectionner par défaut le dernier mois dans la zone de liste,
		// on demande toutes les clés, et on prend la première,
		// les mois étant triés décroissants
		include("views/v_listeVisiteur.php");
		break;
	}
	case 'voirFraisPeriodeVisiteur':{
		$leVisiteur = $_REQUEST['lstVisiteurs'];
		$lesVisiteurs=$pdo->getLesVisiteurs();
		include("views/v_listeVisiteur.php");
		$lesInfosPeriodeVisiteur = $pdo->getFraisPeriodeVisiteur($leVisiteur);
		
		include("views/v_etatFraisPeriodeVisiteur.php");
		break;
	}
	case 'formulaire':{
		$lesVisiteurs=$pdo->getLesVisiteurs();
	
		$rep = "";
		$nui = "";
		$etp = "";
		$km = "";
		$mois = "";
		$annee = "";
		include("views/v_saisieVisiteur.php");
		
		break;
	}
	case 'saisie':{

		$_SESSION['periode'] = "";
	
		if(isset($_REQUEST['Rechercher']))
		{
			$mois = $_REQUEST['mois'];
			$annee = $_REQUEST['annee'];
			$periode = $annee.$mois;
			$_SESSION['periode'] = $periode;
			$dateSelect = $pdo->getLigneFraisForfait($idVisiteur, $periode);
			if(!is_array($dateSelect))
			{
				$rep = "";
				$nui = "";
				$etp = "";
				$km = "";

				$lesVisiteurs=$pdo->getLesVisiteurs();
			
				include("views/v_saisieVisiteur.php");
			}
			else
			{
				$q1 = $pdo->getLaQuantite($idVisiteur, $periode, "REP");
				$q2 = $pdo->getLaQuantite($idVisiteur, $periode, "NUI");
				$q3 = $pdo->getLaQuantite($idVisiteur, $periode, "ETP");
				$q4 = $pdo->getLaQuantite($idVisiteur, $periode, "KM");
	
				$rep = $q1["quantite"];
				$nui = $q2["quantite"];
				$etp = $q3["quantite"];
				$km = $q4["quantite"];
	
				
				$lesVisiteurs=$pdo->getLesVisiteurs();
				
				include("views/v_saisieVisiteur.php");
			}
		
		}
		
		
		if(isset($_REQUEST['Envoyer']))
		{	
			$mois = $_REQUEST['mois'];
			$annee = $_REQUEST['annee'];
			$laPeriode = $annee.$mois;
			$typeRep = "REP";
			$rep = $_REQUEST['rep'];
			$typeNui = "NUI";
			$nui = $_REQUEST['nui'];
			$typeEtp = "ETP";
			$etp = $_REQUEST['etp'];
			$typeKm = "KM";
			$km = $_REQUEST['km'];

			$dateSelect2 = $pdo->getLigneFraisForfait($idVisiteur, $laPeriode);
			
			if(!is_array($dateSelect2))
			{
				try
				{
					$i1 = $pdo->insertFicheFrais($idVisiteur, $laPeriode);
					$ficheFrais = $pdo->getFicheFrais($idVisiteur, $laPeriode);
					
					if(is_array($ficheFrais) != null)
					{
						$i2 = $pdo->insertLigneFraisForfait($idVisiteur, $laPeriode, $typeRep, $rep);
						$i3 = $pdo->insertLigneFraisForfait($idVisiteur, $laPeriode, $typeNui, $nui);
						$i4 = $pdo->insertLigneFraisForfait($idVisiteur, $laPeriode, $typeEtp, $etp);
						$i5 = $pdo->insertLigneFraisForfait($idVisiteur, $laPeriode, $typeKm, $km);
					}
			
					
					$lesVisiteurs=$pdo->getLesVisiteurs();
				
					echo "<script> alert('Lignes insérées !')</script>";
					include("views/v_saisieVisiteur.php");
				}
				catch(exception $e)
				{
					$e = "<script> alert('Lignes non insérées !')</script>";
					$lesVisiteurs=$pdo->getLesVisiteurs();
			
					include("views/v_saisieVisiteur.php");
					echo $e;
				}
				
			}
			else
			{
				try
				{
					$req1 = $pdo->updateLigneFraisForfait($idVisiteur, $laPeriode, $typeRep, $rep);
					$req2 = $pdo->updateLigneFraisForfait($idVisiteur, $laPeriode, $typeNui, $nui);
					$req3 = $pdo->updateLigneFraisForfait($idVisiteur, $laPeriode, $typeEtp, $etp);
					$req4 = $pdo->updateLigneFraisForfait($idVisiteur, $laPeriode, $typeKm, $km);
					
					$lesVisiteurs=$pdo->getLesVisiteurs();
					
					echo "<script> alert('Lignes modifiées !')</script>";
					include("views/v_saisieVisiteur.php");
				}
				catch(exception $e)
				{
					$e = "<script> alert('Lignes non modifiées !')</script>";
					$lesVisiteurs=$pdo->getLesVisiteurs();
				
					include("views/v_saisieVisiteur.php");
					echo $e;
				}
			}

		}
		
		break;
	}
	

}