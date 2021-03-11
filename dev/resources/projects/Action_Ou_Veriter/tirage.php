<?php
//conection a la base de donnée

session_start();
try
{
  $bdd = new PDO('mysql:host=localhost;dbname=action_ou_verite;charset=utf8', 'user_action_ou_verite', '7yHm6RS34s');
}
catch (Exception $e)
{
  die('Erreur : ' . $e->getMessage());
}
// Si une entrée de formulaire existe alors on la transform en cookie et on la supprime
if (isset($_POST['playerName1'])) {
  $_SESSION['session_nbPlayer'] = count($_POST);
  $nbRepet = 1;
  while ($nbRepet <= count($_POST)){
    $_SESSION['session_player' . $nbRepet] = ucfirst(strtolower($_POST['playerName' . $nbRepet]));
    $nbRepet ++;
  }
  header('Location: tirage.php');
}
if (isset($_SESSION['session_nbPlayer'])) {
  // on choisie un joueur aleatoire
  $selectedPlayer1 = rand(1, $_SESSION['session_nbPlayer']);
  // on choise un deuxieme joueur aleatoire
  $selectedPlayer2 = rand(1, $_SESSION['session_nbPlayer']);
  while ($selectedPlayer2 == $selectedPlayer1) {
    $selectedPlayer2 = rand(1, $_SESSION['session_nbPlayer']);
  }
  // on choise un troisieme joueur aleatoire si il y a plus de 3 joueur
  if ($_SESSION['session_nbPlayer'] >= 3) {
    $selectedPlayer3 = rand(1, $_SESSION['session_nbPlayer']);
    while (($selectedPlayer3 == $selectedPlayer1) ||  ($selectedPlayer3 == $selectedPlayer2)) {
      $selectedPlayer3 = rand(1, $_SESSION['session_nbPlayer']);
    }
  }

  $nbRepet = 1;
  // on atribue les numero des joueur a leurs nom
  while ($nbRepet <= $_SESSION['session_nbPlayer']) {
    $player{$nbRepet} = $_SESSION['session_player' . $nbRepet];
    if ($nbRepet == $selectedPlayer1) {
      $selectedPlayerName1 = $player{$nbRepet};
    }
    if ($nbRepet == $selectedPlayer2) {
      $selectedPlayerName2 = $player{$nbRepet};
    }
    if ($_SESSION['session_nbPlayer'] >= 3 AND $nbRepet == $selectedPlayer3) {
      $selectedPlayerName3 = $player{$nbRepet};
    }
    $nbRepet ++;
  }

  // Choix action ou verité
  $type = rand(1, 10);
  if ($type >= 4) {
    $input_data_type = 'action';
    // choix d'une action
    $nbAction = $bdd->query('SELECT COUNT(*) FROM actions');
    while ($donnees = $nbAction->fetch()){
      $selectedAction = rand(1, $donnees['COUNT(*)']);
    }
    $selectedActionText = $bdd->query("SELECT action FROM actions WHERE id=$selectedAction");
    while ($donnees = $selectedActionText->fetch()){
      $dataTexte = $donnees['action'];
    }
  }

  if ($type < 4) {
    $input_data_type = 'verite';
    // choix d'une verité
    $nbVerite = $bdd->query('SELECT COUNT(*) FROM verites');
    while ($donnees = $nbVerite->fetch()){
      $selectedVerite = rand(1, $donnees['COUNT(*)']);
    }
    $selectedVeriteText = $bdd->query("SELECT verite FROM verites WHERE id=$selectedVerite");
    while ($donnees = $selectedVeriteText->fetch()){
      $dataTexte = $donnees['verite'];
    }
  }
  $dataTexte = ucfirst(strtolower($dataTexte));
  $dataTexte = str_replace("(joueur)", $selectedPlayerName2, $dataTexte);
}
// si il n'y a pas d'entrée de formulaire ou redirige sur le page de formulaire
elseif (!isset($_POST['playerName1']) AND (!isset($_SESSION['session_nbPlayer']))) {
  header('Location: index.html');
}
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Action Verité</title>
    <meta name="author" content="Erwan Decoster">
    <meta name="description" content="Tirage aleatoire d'actions et de questions a realiser">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    <meta name="theme-color" content="#bd8d8d">
    <link rel="shortcut icon" href="resources/favicon-32x32.ico">
    <link rel="apple-touch-icon" href="resources/icon-192x192.png">
    <link rel="manifest" href="manifest.webmanifest">
    <link rel="stylesheet" href="css/master.css">
    <link rel="stylesheet" href="css/tirage.css">
    <script src="https://unpkg.com/typewriter-effect@latest/dist/core.js"></script>
  </head>
  <body class="<?php echo $input_data_type ?>">
    <h1><a class="left_title" href="index.html">Action Ou Verité</a></h1>
    <div class="center_bloc">
      <h2>
        <?php echo $selectedPlayerName1 ?>
      </h2>
      <div class="">
        <h3><?php echo (ucfirst(strtolower($input_data_type))); ?> : </h3>
        <p><?php echo $dataTexte; ?></p>
      </div>
      <a href="tirage.php" autofocus>Suivant</a>
    </div>
    <script type="text/javascript">
    // animation du texte
      const titleAnim = document.querySelector('h2');
      let typewriter = new Typewriter(titleAnim, {
        delay: 100,
        deleteSpeed: 20,
      });
      typewriter.typeString('<?php if (rand(1, 2) == 2 AND $_SESSION['session_nbPlayer'] >= 2) {echo (substr($selectedPlayerName2, 0, rand(1, 3)));} ?>')
      <?php echo "\n"; if (rand(1, 3) == 3 AND $_SESSION['session_nbPlayer'] >= 3) {echo ".deleteAll()\n.typeString('".(substr($selectedPlayerName3, 0, rand(1, 3)))."')\n";} ?>
      .pauseFor(100)
      .deleteAll()
      .typeString('<?php echo $selectedPlayerName1; ?>')
      .start()
    </script>
  </body>
</html>
