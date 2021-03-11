<?php
// Connection a la base de donnée
try
{
  $bdd = new PDO('mysql:host=localhost;dbname=action_ou_verite;charset=utf8', 'user_action_ou_verite', '7yHm6RS34s');
}
catch (Exception $e)
{
  die('Erreur : ' . $e->getMessage());
}
// Verification si le formulaire a deja etais envoyer
if (isset($_POST['input_data_type']) AND $_POST['input_data_type'] = 'action') {
  if (isset($_POST['add_action']) AND (!empty($_POST['add_action']))) {
    if (isset($_COOKIE['typePreviouslySend']) AND $_COOKIE['typePreviouslySend'] = 'verite') {
      setcookie("typePreviouslySend", "", time() - 3600, "/");
    }
    setcookie("typePreviouslySend", action, time() + (60 * 10), "/");
    $AddAction = ucfirst(strtolower($_POST['add_action']));
    $req = $bdd->prepare('INSERT INTO actions(action) VALUES(:action)');
    $req->execute(array(
      'action' => $AddAction
    ));
    echo 'L\'action a bien été ajouté !';
    header('Location: add_data.php');
  }
}
if (isset($_POST['input_data_type']) AND $_POST['input_data_type'] = 'verite') {
  if (isset($_POST['add_verite']) AND (!empty($_POST['add_verite']))) {
    if (isset($_COOKIE['typePreviouslySend']) AND $_COOKIE['typePreviouslySend'] = 'action') {
      setcookie("typePreviouslySend", "", time() - 3600, "/");
    }
    setcookie("typePreviouslySend", verite, time() + (60 * 10), "/");
    $AddVerite = ucfirst(strtolower($_POST['add_verite']));
    $req = $bdd->prepare('INSERT INTO verites(verite) VALUES(:verite)');
    $req->execute(array(
      'verite' => $AddVerite
    ));
    echo 'La verité a bien été ajouté !';
    header('Location: add_data.php');
  }
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
  <link rel="stylesheet" href="css/form.css">
  <link rel="stylesheet" href="css/add_data.css">
  <script src="https://unpkg.com/swup@latest/dist/swup.min.js"></script>
</head>
<body class="action_ou_verite">
  <h1><a class="left_title" href="index.html">Action Ou Verité</a></h1>
  <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post">
    <div class="radio">
      <input class="action" type="radio" name="input_data_type" id="action" value="action" minlength="5" required
      <?php
      if (isset($_COOKIE['typePreviouslySend']) AND $_COOKIE['typePreviouslySend'] == 'action'){
        echo "checked";
      }
      elseif (!isset($_COOKIE['typePreviouslySend'])) {
        echo "checked";
      }
      ?>
      >
      <label class="action" for="action">Action</label>

      <input class="verite" type="radio" name="input_data_type" id="verite" value="verite" minlength="5" required
      <?php
      if (isset($_COOKIE['typePreviouslySend']) AND $_COOKIE['typePreviouslySend'] == 'verite')
      {echo "checked";}
      ?>
      >
      <label class="verite" for="verite">Vérité</label>
    </div>
    <p>Ecris (joueur) pour qu'un nom soit ecris a cet endroit pendant la partie</p>
    <div class="subm add_action">
      <input type="text" id="add_action" name="add_action" minlength="10" autocomplete="off" autofocus>
      <label for="add_action">Nouvelle action</label>
    </div>
    <div class="subm add_verite">
      <input type="text" id="add_verite" name="add_verite" minlength="10" autocomplete="off" autofocus>
      <label for="add_verite">Nouvelle vérité</label>
    </div>
    <div class="subm send_data">
      <input type="submit" name="" value="Ajouter">
    </div>
  </form>
  <script type="text/javascript">
  const swup = new Swup();
  </script>
  <script type="text/javascript">
    const body = document.querySelector("body");
    const action = document.querySelector("#verite");
    const add_action = document.querySelector(".add_action");
    const add_action_input = document.querySelector("#add_action");
    const verite = document.querySelector("#action");
    const add_verite = document.querySelector(".add_verite");
    const add_verite_input = document.querySelector("#add_verite");
    const send_data = document.querySelector(".send_data");
    // php change stat
    <?php
    if (isset($_COOKIE['typePreviouslySend']) AND $_COOKIE['typePreviouslySend'] == 'action') {
      echo "
      // action
      body.className = \"action\";
      add_verite.style.display = \"none\";
      add_action.style.display = \"block\";
      send_data.style.display = \"block\";
      ";
    }
    if (isset($_COOKIE['typePreviouslySend']) AND $_COOKIE['typePreviouslySend'] == 'verite') {
      echo "
      // verite
      body.className = \"verite\";
      add_action.style.display = \"none\";
      add_verite.style.display = \"block\";
      add_verite_input.required =  \"required\";
      send_data.style.display = \"block\";
      ";
    }
    else {
      echo "
      // action par defaut
      body.className = \"action\";
      add_verite.style.display = \"none\";
      add_action.style.display = \"block\";
      add_action_input.required =  \"required\";
      send_data.style.display = \"block\";
      ";
    }
    ?>
    // click detection
    action.addEventListener("change", function() {
      body.className = "verite";
      add_action.style.display = "none";
      add_action_input.required =  "";
      add_verite.style.display = "block";
      add_verite_input.required =  "required";
      send_data.style.display = "block";
    });
    verite.addEventListener("change", function() {
      body.className = "action";
      add_verite.style.display = "none";
      add_verite_input.required =  "";
      add_action.style.display = "block";
      add_action_input.required =  "required";
      send_data.style.display = "block";
    });
  </script>
</body>
</html>
