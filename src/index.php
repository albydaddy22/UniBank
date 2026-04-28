<?php
session_start();
require_once __DIR__ . '/../config.php';

$conn = db_connect();

$queryHeader = '
    SELECT d.id_dispensa, d.titolo, d.prezzo, u.username
    FROM dispense d, utenti u
    WHERE d.id_utente = u.id_utente
    ORDER BY d.data_caricamento DESC
    LIMIT 3
';
$resultHeader = mysqli_query($conn, $queryHeader);

$queryHero ='
    SELECT d.id_dispensa, d.titolo, d.descrizione, d.prezzo, u.username, m.nome as materia, f.nome as facolta, uni.nome as universita
    FROM dispense d, utenti u, materiaperfacolta mpf, materia m, facolta f, universita uni
    WHERE d.id_utente = u.id_utente
    AND d.id_materiaperfacolta = mpf.id_materiaperfacolta
    AND mpf.id_materia = m.id_materia
    AND mpf.id_facolta = f.id_facolta
    AND u.id_universita = uni.id_universita
    ORDER BY d.data_caricamento DESC
    LIMIT 4
';
$resultHero = mysqli_query($conn, $queryHero);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../assets/logowhitebg.png">
    <title>UniBank - HomePage</title>
    <link rel="stylesheet" href="index.css?=<?php echo time();?>">
    <link rel="stylesheet" href="variables.css?=<?php echo time();?>">
</head>
<body>
    <header class="navbar">
        <div class="nbcontainer">
            <div class="logo">
                <img src="../assets/logo%20lungo7.png" alt="logo Unibank">
            </div>
            <div class="menu">
                <ul>
                    <li>
                        <a href="" class="listelement">Sfoglia</a>
                    </li>
                    <li>
                        <a href="" class="listelement">Contattaci</a>
                    </li>
                    <?php
                    if(!isset($_SESSION['is_logged']) || $_SESSION['is_logged'] != true){ ?>
                    <li>
                        <a href="authentication/frontend/login.php">
                            <button class="loginbtn">Login</button>
                        </a>
                    </li>
                    <?php } ?>
                    <?php
                    if(!isset($_SESSION['is_logged']) || $_SESSION['is_logged'] != true){?>
                    <li>
                        <a href="authentication/frontend/signup.php">
                            <button class="signupbtn">Registrati</button>
                        </a>
                    </li>
                    <?php } ?>
                    <?php
                    if(isset($_SESSION['is_logged']) && $_SESSION['is_logged'] == true){ ?>
                    <li>
                        <div class="profileicon">
                            <img src="../assets/user.png" alt="user">
                        </div>
                        <div class="userpopup">
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
                            <img src="../assets/unitoken.png" alt="UT"></span>
                            <a href="profile/profile.php" class="mioprofile">Visualizza profilo</a>
                            <a href="authentication/backend/logout.php"><button class="logoutbtn">Logout</button></a>
                        </div>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </header>
    <div class="container">
        <div class="hpheader">
            <div class="hpheadercontainer">
                <div class="textbox">
                    <h1>Il <span>mercato</span> del sapere universitario</h1>
                    <p>Condividi le tue dispense e guadagna UniToken. Acquista materiali di qualità dai tuoi colleghi universitari.</p>
                    <div class="btnbox">
                        <button class="startbtn">Inizia Gratis</button>
                        <button class="sfogliabtn">Sfoglia dispense</button>
                    </div>
                </div>
                <div class="headerdispense">
                    <?php
                    while($disp = mysqli_fetch_assoc($resultHeader)){
                        echo '<div class="hddispensabox">';
                        echo '<span class="hdnomedispensa">' . htmlspecialchars($disp['titolo']) . '</span>';
                        echo '<span class="hdprezzodispensa">' . $disp['prezzo'] . ' <img class="ut" src="../assets/unitoken.png" alt="UT"></span>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="hero">
            <div class="herocontainer">
                <div class="dispenserecenti">
                    <h2>Dispense Recenti</h2>
                    <a href="" class="veditutte">Vedi tutte →</a>
                </div>
                <div class="herodispense">
                    <?php
                    $count = 0;
                    while($disp = mysqli_fetch_assoc($resultHero)){
                        $count++;
                        echo '<div class="hrdispensabox">';
                        echo '<div class="dbtextbox">';
                        echo '<img class="dbdocument" src="../assets/document.png" alt="">';
                        echo '<h4 class="dbnomedispensa">' . htmlspecialchars($disp['titolo']) . '</h4>';
                        echo '<p class="dbcorso">' . htmlspecialchars($disp['materia']) . '</p>';
                        echo '<p class="dbuniversita">' . htmlspecialchars($disp['universita']) . '</p>';
                        echo '<p class="dbfacolta">' . htmlspecialchars($disp['facolta']) . '</p>';
                        echo '<p class="dbuser">di ' . htmlspecialchars($disp['username']) . '</p>';
                        echo '</div>';
                        echo '<div class="dbbuyfield">';
                        echo '<span class="dbprezzodispensa">' . $disp['prezzo'] . ' <img class="ut" src="../assets/unitoken.png" alt="UT"></span>';
                        echo '<form action="./acquistaDispense/elaborazioneAcquisto.php" method="POST">';
                        echo '<input type="hidden" name="id_dispensa" value="' . $disp['id_dispensa'] . '">';
                        echo '<button type="submit" class="buybtn">Compra</button>';
                        echo '</form>';
                        echo '</div>';
                        echo '</div>';
                    }
                    if($count == 0){
                        echo '<p>Nessuna dispensa disponibile al momento.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <div class="ftcontainer">
            <div class="ftcolumn">
                <h2>Pages</h2>
                <ul>
                    <li>
                        <a href="login.php">Login</a>
                    </li>
                    <li>
                        <a href="signup.php">Registrazione</a>
                    </li>
                    <li>
                        <a href="../../index.html">Home</a>
                    </li>
                    <li>
                        <a href="../../../install/install.html">Installazione</a>
                    </li>
                    <li>
                        <a href="#">Dashboard</a>
                    </li>
                    <li>
                        <a href="#">Cerca Materiale</a>
                    </li>
                    <li>
                        <a href="#">Profilo</a>
                    </li>
                </ul>
            </div>
            <div class="ftcolumn">
                <h2>Manca la tua università o la tua facoltà?</h2>
                <a href="#"><button class="contactbtn">Contattaci</button></a>
            </div>
        </div>
        <p class="copyright">© 2026 UniBank™. All rights reserved.</p>
    </footer>
</body>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.nbcontainer');
    function ombraNavbar() {
        if (window.scrollY === 0) {
            navbar.classList.add('no-shadow');
        } else {
            navbar.classList.remove('no-shadow');
        }
    }
    ombraNavbar();
    window.addEventListener('scroll', ombraNavbar);
});
</script>
</html>