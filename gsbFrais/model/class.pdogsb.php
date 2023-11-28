<?php
/**
 * Classe d'accès aux données.

 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO
 * $monPdoGsb qui contiendra l'unique instance de la classe

 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoGsb{
      	private static $serveur='mysql:host=localhost';
      	private static $bdd='dbname=gsbfrais';
      	private static $user='root' ;
      	private static $mdp='' ;
		private static $monPdo;
		private static $monPdoGsb=null;
/**
 * Constructeur privé, crée l'instance de PDO qui sera sollicitée
 * pour toutes les méthodes de la classe
 */
	private function __construct(){
    	PdoGsb::$monPdo = new PDO(PdoGsb::$serveur.';'.PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp);
		PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
	}
	public function _destruct(){
		PdoGsb::$monPdo = null;
	}

    /**
     * Fonction statique qui crée l'unique instance de la classe
     * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
     * @return null L'unique objet de la classe PdoGsb
     */
	public  static function getPdoGsb(){
		if(PdoGsb::$monPdoGsb==null){
			PdoGsb::$monPdoGsb= new PdoGsb();
		}
		return PdoGsb::$monPdoGsb;
	}

    /**
     * Retourne les informations d'un visiteur
     * @param $login
     * @param $mdp
     * @return mixed L'id, le nom et le prénom sous la forme d'un tableau associatif
     */
    public function getInfosVisiteur($login, $mdp){
        $req = "select id, nom, prenom from visiteur where login= :login and mdp= :mdp";
        $rs = PdoGsb::$monPdo->prepare($req);
        $rs->bindParam(':login', $login, PDO::PARAM_STR);
        $rs->bindParam(':mdp', $mdp, PDO::PARAM_STR);
        $rs->execute();
        $ligne = $rs->fetch();
        return $ligne;
    }

    /**
     * Transforme une date au format français jj/mm/aaaa vers le format anglais aaaa-mm-jj
     
    * @param $madate au format  jj/mm/aaaa
    * @return la date au format anglais aaaa-mm-jj
    */
    public function dateAnglaisVersFrancais($maDate){
        @list($annee,$mois,$jour)=explode('-',$maDate);
        $date="$jour"."/".$mois."/".$annee;
        return $date;
    }

    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
     * concernées par les deux arguments
     * La boucle foreach ne peut être utilisée ici, car on procède
     * à une modification de la structure itérée - transformation du champ date-
     * @param $idVisiteur
     * @param $mois 'sous la forme aaaamm
     * @return array 'Tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif
     */
    public function getLesFraisHorsForfait($idVisiteur,$mois){
        $req = "select * from lignefraishorsforfait where idVisiteur = :idVisiteur
		and mois = :mois ";
        $res = PdoGsb::$monPdo->prepare($req);
        $res->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);
        $res->bindParam(':mois', $mois, PDO::PARAM_INT);
        $res->execute();
        $lesLignes = $res->fetchAll();
        $nbLignes = count($lesLignes);
        for ($i=0; $i<$nbLignes; $i++){
            $date = $lesLignes[$i]['date'];
            //Gestion des dates
            @list($annee,$mois,$jour) = explode('-',$date);
            $dateStr = "$jour"."/".$mois."/".$annee;
            $lesLignes[$i]['date'] = $dateStr;
        }
        return $lesLignes;
    }


    /**
     * Retourne les mois pour lesquels, un visiteur a une fiche de frais
     * @param $idVisiteur
     * @return array 'Un tableau associatif de clé un mois - aaaamm - et de valeurs l'année et le mois correspondant
     */
    public function getLesMoisDisponibles($idVisiteur){
        $req = "select mois from fichefrais where idvisiteur = :idVisiteur order by mois desc ";
        $res = PdoGsb::$monPdo->prepare($req);
        $res->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);
        $res->execute();
        $lesMois =array();
        $laLigne = $res->fetch();
        while($laLigne != null)	{
            $mois = $laLigne['mois'];
            $numAnnee =substr( $mois,0,4);
            $numMois =substr( $mois,4,2);
            $lesMois["$mois"]=array(
                "mois"=>"$mois",
                "numAnnee"  => "$numAnnee",
                "numMois"  => "$numMois"
            );
            $laLigne = $res->fetch();
        }
        return $lesMois;
    }

    /**
     * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donn�
     * @param $idVisiteur
     * @param $mois 'sous la forme aaaamm
     * @return mixed 'Un tableau avec des champs de jointure entre une fiche de frais et la ligne d'�tat
     */
    public function getLesInfosFicheFrais($idVisiteur,$mois){
        $req = "select fichefrais.idEtat as idEtat, fichefrais.dateModif as dateModif, fichefrais.nbJustificatifs as nbJustificatifs, 
			fichefrais.montantValide as montantValide, etat.libelle as libEtat from  fichefrais inner join etat on fichefrais.idEtat = etat.id 
			where fichefrais.idVisiteur = :idVisiteur and fichefrais.mois = :mois";
        $res = PdoGsb::$monPdo->prepare($req);
        $res->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);
        $res->bindParam(':mois', $mois, PDO::PARAM_INT);
        $res->execute();
        $laLigne = $res->fetch();
        return $laLigne;
    }

    /**
     * Retourne les types de frais
     * @return array 'Un tableau avec les types de frais'
     */
    public function getLesTdfDisponibles(){
        $req = "select id from fraisforfait";
        $res = PdoGsb::$monPdo->prepare($req);
        $res->execute();
        $lesTypes = $res->fetchAll();

        return $lesTypes;
    }

    /**
     * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donné et un type de frais donné
     * @param $idVisiteur
     * @param $mois 'sous la forme aaaamm
     * @param $type 
     * @return mixed 'Un tableau avec des champs de jointure entre la table lignefraisforfait et la table fraisforfait
     */
    public function getLesInfosEtatFrais(/*$idVisiteur,*/$mois,$type){
        $req = "select lignefraisforfait.idVisiteur as numVisiteur, (lignefraisforfait.quantite * fraisforfait.montant) as montant from lignefraisforfait inner join fraisforfait 
            on lignefraisforfait.idFraisForfait = fraisforfait.id where 
            lignefraisforfait.mois = :mois and lignefraisforfait.idFraisForfait = :type";
            /*lignefraisforfait.idVisiteur = :idVisiteur  and */
        $res = PdoGsb::$monPdo->prepare($req);
        /*$res->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);*/
        $res->bindParam(':mois', $mois, PDO::PARAM_INT);
        $res->bindParam(':type', $type, PDO::PARAM_STR);
        $res->execute();
        $laLigne = $res->fetchAll();
        return $laLigne;
    }

    /**
     * Retourne les visiteurs
     * @return array 'Un tableau avec les visiteurs'
     */
    public function getLesVisiteurs(){
        $req = "select distinct(idVisiteur) from lignefraisforfait";
        $res = PdoGsb::$monPdo->prepare($req);
        $res->execute();
        $laLigne = $res->fetchAll();
        return $laLigne;

    }

    /**
     * Retourne les informations d'une fiche de frais d'un visiteur pour un type donné
     * @param $idVisiteur
     * @param $type 
     * @return mixed 'Un tableau avec des champs de jointure entre la table lignefraisforfait et la table fraisforfait
     */
    public function getLesInfosVisiteurFrais($idVisiteur,$type){
        $req = "select lignefraisforfait.mois as mois, (lignefraisforfait.quantite * fraisforfait.montant) as montant from lignefraisforfait inner join fraisforfait 
            on lignefraisforfait.idFraisForfait = fraisforfait.id where 
            lignefraisforfait.idVisiteur = :idVisiteur and lignefraisforfait.idFraisForfait = :type order by mois desc";
        $res = PdoGsb::$monPdo->prepare($req);
        $res->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);
        $res->bindParam(':type', $type, PDO::PARAM_STR);
        $res->execute();
        $laLigne = $res->fetchAll();
        return $laLigne;
    }

     /**
     * Retourne les mois
     * @return array 'Un tableau avec les mois'
     */
    public function getLesPeriodes(){
        $req = "select mois from fichefrais order by mois desc ";
        $res = PdoGsb::$monPdo->prepare($req);
        $res->execute();
        $lesPeriodes =array();
        $laLigne = $res->fetch();
        while($laLigne != null)	{
            $mois = $laLigne['mois'];
            $numAnnee =substr( $mois,0,4);
            $numMois =substr( $mois,4,2);
            $lesPeriodes["$mois"]=array(
                "mois"=>"$mois",
                "numAnnee"  => "$numAnnee",
                "numMois"  => "$numMois"
            );
            $laLigne = $res->fetch();
        }
        return $lesPeriodes;

    }

    /**
     * Retourne les informations du montant pour chaque type de frais par visiteur
     * @param $mois 'sous la forme aaaamm
     * @return mixed 'Un tableau avec des champs de jointure entre la table lignefraisforfait et la table fraisforfait
     */
    public function getFraisPeriode($mois){
        $req = "select lf.idVisiteur as numVisiteur,
        sum(ff.montant * case when lf.idFraisForfait = 'ETP' then lf.quantite END) as 'ETP',
        sum(ff.montant * case when lf.idFraisForfait = 'KM' then lf.quantite END) as 'KM',
        sum(ff.montant * case when lf.idFraisForfait = 'NUI' then lf.quantite END) as 'NUI',
        sum(ff.montant * case when lf.idFraisForfait = 'REP' then lf.quantite END) as 'REP'
        from lignefraisforfait lf
        inner join fraisforfait ff on ff.id = lf.idFraisForfait
        where mois = :mois
        GROUP BY lf.idVisiteur";
        $res = PdoGsb::$monPdo->prepare($req);
        $res->bindParam(':mois', $mois, PDO::PARAM_INT);
        $res->execute();
        $laLigne = $res->fetchAll();
        return $laLigne; 
    }

    /**
     * Retourne les informations du montant pour chaque type de frais par mois spour un utilisateur donné
     * @param $idVisiteur 
     * @return mixed 'Un tableau avec des champs de jointure entre la table lignefraisforfait et la table fraisforfait
     */
    public function getFraisPeriodeVisiteur($idVisiteur){
        $req = "select lf.mois as mois,
        sum(ff.montant * case when lf.idFraisForfait = 'ETP' then lf.quantite END) as 'ETP',
        sum(ff.montant * case when lf.idFraisForfait = 'KM' then lf.quantite END) as 'KM',
        sum(ff.montant * case when lf.idFraisForfait = 'NUI' then lf.quantite END) as 'NUI',
        sum(ff.montant * case when lf.idFraisForfait = 'REP' then lf.quantite END) as 'REP'
        from lignefraisforfait lf
        inner join fraisforfait ff on ff.id = lf.idFraisForfait
        where idVisiteur= :idVisiteur
        GROUP BY mois
        ORDER BY mois DESC";
        $res = PdoGsb::$monPdo->prepare($req);
        $res->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);
        $res->execute();
        $laLigne = $res->fetchAll();
        return $laLigne; 
    }

    public function getAnneePeriode(){
        $req = "select distinct(left(mois, 4)) as annee from lignefraisforfait ORDER BY annee DESC";
        $res = PdoGsb::$monPdo->prepare($req);
        $res->execute();
        $laLigne = $res->fetchAll();
        return $laLigne;
    }

    public function getMoisPeriode(){
        $req = "select distinct(right(mois, 2)) as mois from lignefraisforfait ORDER BY mois ASC";
        $res = PdoGsb::$monPdo->prepare($req);
        $res->execute();
        $laLigne = $res->fetchAll();
        return $laLigne;
    }

    public function insertFicheFrais($idVisiteur, $mois)
    {
        $req = "insert into fichefrais (idVisiteur, mois) values (:idVisiteur, :mois)";
        $res = PdoGsb::$monPdo->prepare($req);
        $res->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);
        $res->bindParam(':mois', $mois, PDO::PARAM_INT);
        $res->execute();
    }

    public function insertLigneFraisForfait($idVisiteur, $mois, $type, $quantite)
    {
        $req = "insert into lignefraisforfait (idVisiteur, mois, idFraisForfait, quantite) values (:idVisiteur, :mois, :idFraisForfait, :quantite)";
        $res = PdoGsb::$monPdo->prepare($req);
        $res->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);
        $res->bindParam(':mois', $mois, PDO::PARAM_INT);
        $res->bindParam(':idFraisForfait', $type, PDO::PARAM_STR);
        $res->bindParam(':quantite', $quantite, PDO::PARAM_INT);
        $res->execute();
    }

    public function updateLigneFraisForfait($idVisiteur, $mois, $type, $quantite){
        $req = "update lignefraisforfait set quantite= :quantite where
        idVisiteur = :idVisiteur and mois = :mois and idFraisForfait = :type ";
        $res = PdoGsb::$monPdo->prepare($req);
        $res->bindParam(':quantite', $quantite, PDO::PARAM_INT);
        $res->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);
        $res->bindParam(':mois', $mois, PDO::PARAM_INT);
        $res->bindParam(':type', $type, PDO::PARAM_STR);
        $res->execute();

    }

    public function getLaQuantite($idVisiteur, $mois, $type)
    {
        $req = "select quantite from lignefraisforfait
        where idVisiteur= :idVisiteur and mois = :mois and idFraisForfait = :type";
        $res = PdoGsb::$monPdo->prepare($req);
        $res->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);
        $res->bindParam(':mois', $mois, PDO::PARAM_INT);
        $res->bindParam(':type', $type, PDO::PARAM_STR);
        $res->execute();
        $laLigne = $res->fetch();
        return $laLigne;
    }

    public function getLigneFraisForfait($idVisiteur, $mois)
    {
        $req = "select * from lignefraisforfait where idVisiteur = :idVisiteur and mois = :mois";
        $res = PdoGsb::$monPdo->prepare($req);
        $res->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);
        $res->bindParam(':mois', $mois, PDO::PARAM_INT);
        $res->execute();
        $laLigne = $res->fetch();
        return $laLigne;
    }

    public function getFicheFrais($idVisiteur, $mois)
    {
        $req = "select idVisiteur, mois from fichefrais where idVisiteur = :idVisiteur and mois = :mois";
        $res = PdoGsb::$monPdo->prepare($req);
        $res->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);
        $res->bindParam(':mois', $mois, PDO::PARAM_INT);
        $res->execute();
        $laLigne = $res->fetchAll();
        return $laLigne;
    }



}

