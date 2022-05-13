<?php
/*
	EA2 Grundgerüst

	Hier im oberen Abschnitt der Datei können Sie die Verarbeitung der
	$_GET oder $_POST Variablen durchführen und auf die verschiedenen
	Aktionen reagieren.
	Auch das Lesen und Schreiben der Werte in eine Datei kann hier oben stehen.
	Das Behandeln einer Aktion startet i.d.R. mit einer if-Abfrage, um festzustellen, was geklickt wurde.
	
	Sie benötigen folgende Aktionen:
	
	Lesen der Waren aus der Datei (wenn bereits Waren vorhanden sind). x
	Hinzufügen einer neuen Ware (wenn Formular ausgefüllt). x
	Markieren einer Ware (wenn Link "markieren" geklickt).
	Löschen  einer Ware (wenn Link "löschen" geklickt). 
	Schreiben aller Waren in die Datei (wird immer komplett neu geschrieben). x
	
*/

// Array definieren
$arrArtikel = array();

// als Beispiel einen Artikel einfügen
//$arrArtikel[0] = array("anzahl" => 12, "artikel" => "Katzenfutter Dosen", "markiert" => "0");

if(!empty($_POST)) {
// weiteren Artikel aus POST hinten anhängen
$arrArtikel[] = $_POST;
$fh = fopen("einkauf.txt", "w");

// speichern von Daten in JSON
$json = json_encode($arrArtikel);
fputs($fh, $json);
fclose($fh);
} else {
  echo "Es sind noch keine Artikel hinterlegt. Bitte eintragen!";
}
// lesen
$dateiInhalt = file_get_contents("einkauf.txt");
$arrArtikel = json_decode($dateiInhalt, true);

// artikel löschen
if(isset($_GET["action"]) && $_GET["action"] == "delete" && isset($_GET["id"])) {
	unset($arrArtikel[intval($_GET["id"])]);
}

// artikel markieren
if(isset($_GET["action"]) && $_GET["action"] == "mark" && isset($_GET["id"])) {
	if($arrArtikel[intval($_GET["id"])]["markiert"] == "1") {
		$arrArtikel[intval($_GET["id"])]["markiert"] = "0";
	} 
	else {
		$arrArtikel[intval($_GET["id"])]["markiert"] = "1";
	}
}

/*
	Ab hier erfolgt die HTML-Ausgabe
*/
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ISP - Einsendeaufgabe 2</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="./css/main.css" />
</head>
<body>
  <header>
    <h2>EA2 - Einkaufsliste</h2>
  </header>
  <main>
    <ul id="todolist">
<?php
	/*
		Die beiden folgenden Listeneinträge <li></li> sind als Beispiel hier eingefügt.
		Sie müssen eine Schleife bauen, die alle vorhandenen Waren schreibt.
		In der Schleife ist dann nur ein Listeneintrag, den anderen können Sie löschen.
		Bei jedem Listeneintrag ist dann die ID oder der Zähler der Ware anzugeben.
		Außerdem muss die Menge und der Name der Ware in den <span>-Tags ausgegeben werden.		
		Eine foreach-Schleife wird im Chat erklärt. Weitere Möglichkeiten: for oder while
	*/
	
  // Schleife Beginn mit Bedingung einfügen
  
  if (empty ($arrArtikel)):
    echo "<p> Sie haben noch keine Artikel hinzugefügt </p>";
  else:
	for($i = 0; $i <count($arrArtikel); $i++) :
  
?>
      <li>
        <a href="index.php?action=mark&id=<?php echo $i;?>" class="done" title="Ware als eingekauft markieren"></a>
        <span> <?php echo $arrArtikel["anzahl"]."<br>"; ?> </span>
        <span> <?php echo $arrArtikel["artikel"]."<br>";?> </span>
        <a href="index.php?action=delete&id=<?php echo $i; ?>" class="delete" title="Ware aus Liste löschen">löschen</a>
      </li>
      
<?php
	// Schleife Ende einfügen
  endfor;
  endif;
?>
    </ul>
    <div class="spacer"></div>
    <form id="add-todo" action="index.php" method="post">
      <input type="text" placeholder="anzahl" name="anzahl">
      <input type="text" placeholder="Text für neue Ware" name="artikel">
      <input type="submit" value="hinzufügen">
    </form>
  </main>
  <footer>
    <p>Müller, Hendrik - Hochschule Emden Leer</p>
  </footer>
</body>
</html>
