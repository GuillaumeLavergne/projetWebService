<?php

//définition des variables nécessaire à la base de donnée
$bdd = "glavergne001_pro";
$host = "lakartxela.iutbayonne.univ-pau.fr";
$user = "glavergne001_pro";
$pass = "glavergne001_pro"; 

//connection à la base de donnée
$link=mysqli_connect($host,$user,$pass,$bdd) or die ("impossible de se connecter");

//Definition du nom de la table
$nomTable="Lieux";

//vider la base
$link->query("DELETE FROM $nomTable") or die ("non guillaume");

//les regions de france
$tabRegion=Array("Nouvelle-Aquitaine","Auvergne-Rhône-Alpes","Bourgogne-Franche-Comté","Bretagne","Centre-Val de Loire","Corse","Grand Est","Guadeloupe","Guyane","Hauts-de-France","Île-de-France","La Réunion",
				"Martinique","Mayotte","Normandie","Occitanie","Pays de la Loire","Provence-Alpes-Côte d'Azur","Saint-Pierre-et-Miquelon");	
$cpt=1;

					
//json des monuments francais
for($region=0;$region<count($tabRegion);$region++)
{
	$url="https://data.culture.gouv.fr/api/records/1.0/search/?dataset=liste-des-immeubles-proteges-au-titre-des-monuments-historiques&q=&rows=10000&&refine.region=$tabRegion[$region]";
	$json=file_get_contents($url);
	$jsonFinal=json_decode($json,true);

	//Declarer des arrays
	$tabNom=Array("vide");//Nom du monument
	$tabLong=Array("vide");//Longitude du monument
	$tabLatt=Array("vide");//Lattitude du monument
	$tabVille=Array("vide");//Ville ou se trouve le monument

	//Insertion des valeurs
	foreach($jsonFinal['records'] as $ligne)
	{
		$Nom=$ligne['fields']['appellation_courante'];//inserer le lieu dans une chaine de caractére
		$Ville=$ligne['fields']['commune'];//inserer la ville dans une chaine de caractére
		
		$Nom=normaliser($Nom);
		$Ville=normaliser($Ville);
	
		$tabNom[$cpt]=$Nom;
		$tabVille[$cpt]=$Ville;
		
		if(isset($ligne['fields']['coordonnees_finales'][0]) && isset($ligne['fields']['coordonnees_finales'][1]))
		{
			$tabLong[$cpt]=$ligne['fields']['coordonnees_finales'][0];
			$tabLatt[$cpt]=$ligne['fields']['coordonnees_finales'][1];
		}
		else
		{
			$tabLong[$cpt]=0;
			$tabLatt[$cpt]=0;
		}
		
		//inserer en base de donnée
		$link->query("INSERT INTO $nomTable VALUES(
			'$cpt',
			'$tabNom[$cpt]',
			'$tabVille[$cpt]',
			$tabLong[$cpt],
			$tabLatt[$cpt]);") or die ("raté insertion");
			
		$cpt++;
	}
	
};
//Recuperer les donnees
$resultat=mysqli_query($link,"SELECT * FROM $nomTable") or die("Impossible de selectionner des valeurs");

//debut affichage du tableau
echo"	<table width=100%>
<tr>
		<td><big>Identifiant</big></td>
		<td><big>Nom</big></td>
		<td><big>Ville</big></td>
		<td><big>Longitude</big></td>
		<td><big>Lattitude</big></td>
<tr>";

//afficher la table
while($valeur = mysqli_fetch_assoc($resultat))
{	
	echo"	<tr>
			<td>".$valeur["Identifiant"]."</td>
			<td>".$valeur["Nom"]."</td>
			<td>".$valeur["Ville"]."</td>
			<td>".$valeur["Longitude"]."</td>
			<td>".$valeur["Lattitude"]."</td>
			</tr>
			";
}

//fermeture du tableau
echo"</table>";




function normaliser($val)
{
	$search  = array('\'','À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
	$replace = array(' ','A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
	$val = str_replace($search, $replace, $val);
	return $val;
}
?>