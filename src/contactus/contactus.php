<?php
session_start();
require_once __DIR__ . '/../../config.php';

$conn = db_connect();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../../assets/logowhitebg.png">
    <title>UniBank - Contattaci</title>
    <link rel="stylesheet" href="contactus.css?=<?php echo time();?>">
    <link rel="stylesheet" href="../variables.css?=<?php echo time();?>">
</head>
<body>
    <header class="navbar">
        <div class="nbcontainer">
            <div class="logo">
                <img src="../../assets/logo%20lungo7.png" alt="logo Unibank">
            </div>
            <div class="menu">
                <ul>
                    <li>
                        <a href="../index.php" class="listelement">Home</a>
                    </li>
                    <?php
                    if(!isset($_SESSION['is_logged']) || $_SESSION['is_logged'] != true){ ?>
                        <li>
                            <a href="../authentication/frontend/login.php">
                                <button class="loginbtn">Login</button>
                            </a>
                        </li>
                    <?php } ?>
                    <?php
                    if(!isset($_SESSION['is_logged']) || $_SESSION['is_logged'] != true){?>
                        <li>
                            <a href="../authentication/frontend/signup.php">
                                <button class="signupbtn">Registrati</button>
                            </a>
                        </li>
                    <?php } ?>
                    <?php
                    if(isset($_SESSION['is_logged']) && $_SESSION['is_logged'] == true){ ?>
                        <li>
                            <div class="profileicon">
                                <img src="../../assets/user.png" alt="user">
                            </div>
                            <div class="userpopup">
                                <div class="uppfavatar">
                                    <?php
                                    $initials = '';
                                    if(isset($_SESSION['username'])){
                                        $name = trim($_SESSION['username']);
                                        $initials = strtoupper(substr($name,0,1));
                                    }else{ $initials = 'U'; }
                                    ?>
                                    <span><?php echo $initials; ?></span>
                                </div>
                                <span>Ciao, <?php echo $_SESSION['username'] ?></span>
                                <span>Saldo:
                                    <?php
                                    $query = "SELECT saldo FROM utenti WHERE id_utente = {$_SESSION['user_id']}";
                                    $ris = mysqli_query($conn, $query);
                                    if($ris){
                                        $row = mysqli_fetch_assoc($ris);
                                        echo htmlspecialchars($row['saldo']);
                                    }else{
                                        echo '0';
                                    }
                                    ?>
                                    <img src="../../assets/unitoken.png" alt="UT"></span>
                                <a href="../profile/profile.php" class="mioprofile"><button class="visprofilebtn">Visualizza profilo</button></a>
                                <a href="../authentication/backend/logout.php"><button class="logoutbtn">Logout</button></a>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="content">
            <div class="infobox">
                <h4 style="display: flex; justify-content: flex-start; align-items: center; gap: 10px; color: var(--color-yellow-primary); margin-bottom: 15px; font-size: 18px;"><img style="width: 20px" src="../../assets/info.png" alt="i"> Hai bisogno di aiuto?</h4>
                <p class="infop">Hai domande, problemi o suggerimenti?
                    Compila il modulo di contatto e ti risponderemo il prima possibile. Il nostro team è sempre disponibile per aiutarti.</p>
                <div class="suggestedpricebox">
                    <p>Tempi di risposta</p>
                    <h2 style="color: var(--color-yellow-primary)">Entro 24-48 ore</h2>
                    <p>Cerchiamo di rispondere rapidamente a tutte le richieste.</p>
                </div>
                <ul style="list-style: none; padding: 0; display: flex; flex-direction: column; gap: 10px;">
                    <li class="li"">
                    <span style="color: var(--color-yellow-primary)">•</span>
                    <p class="infop">Descrivi il problema in modo chiaro</p>
                    </li>
                    <li class="li">
                        <span style="color: var(--color-yellow-primary)">•</span>
                        <p class="infop">Inserisci un'email valida per ricevere risposta</p>
                    </li>
                    <li class="li"">
                    <span style="color: var(--color-yellow-primary)">•</span>
                    <p class="infop">Ti aggiorneremo via mail appena riceveremo il messaggio</p>
                    </li>
                </ul>
            </div>
            <div class="uploadbox">

            </div>
        </div>
    </div>
</body>
</html>
