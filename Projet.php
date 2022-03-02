<?php

?>

<html>
<head>

</head>
<body>
	<form>
	<label for="ChoixLieu"> Choisissez le mode de recherche pour votre destination :</label>
	<select name="ChoixLieu" id="ChoixLieu">
		<option>Region</option>
		<option>Ville</option>
	</select>
	
	<label for="ChoixDate"> Choisissez une date pour votre sejour :</label>
	<input type="date" name="ChoixDate" id="ChoixDate"></input>
	
	<select name="temps" id="temps">
		<option>Ensoleille</option>
		<option>Pas de pluie</option>
		<option>N'importe</option>
	</select>
	
	<label for="ChoixDate"> Choisissez une fourchette de prix pour votre sejour :</label>
	<input type="number" name="min" id="min"></input> <p>-</p> <input type="number" name="max" id="max"></input>
	
	
	</form>
</body>
<html>