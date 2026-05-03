<?php
session_start();
require_once __DIR__ . '/../../config.php';
$conn = db_connect();
?>
<!doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../assets/logowhitebg.png">
    <title>UniBank - Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css?=<?php echo time();?>">
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
                        <a href="../authentication/backend/logout.php"><button class="logoutbtn">Logout</button></a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>

<main class="admin-page">
    <nav class="admin-tabs">
        <div class="tabs-container">
            <a href="adminpanoramica.php" class="tab-item active">Panoramica</a>
            <a href="adminusers.php" class="tab-item">Gestione utenti</a>
            <a href="adminmateriali.php" class="tab-item">Gestione materiali</a>
        </div>
    </nav>

    <div class="admin-container">
        <section class="admin-stats">
            <div class="stat-card">
                <?php
                    $query = "SELECT COUNT(*) AS numUtenti FROM utenti";
                    $ris = mysqli_query($conn,$query);
                    $riga = mysqli_fetch_assoc($ris);
                    $query2 = "SELECT COUNT(*) AS numAdmin FROM utenti WHERE ruolo = 1";#utenti ruolo = 0 admin = 1, 
                    #conto gli admin cosi poi faccio totale utenti - num ADMIN cosi da avere un numero preciso di utenti che non siano admin
                    $ris2 = mysqli_query($conn,$query2);
                    $riga2 = mysqli_fetch_assoc($ris2);
                    $numUtentiNoAdmin = $riga['numUtenti'] - $riga2['numAdmin'];
                    echo '<span class="stat-label" id="utenti-label">Numero utenti</span>';
                    echo '<span class="stat-value blue" id="utenti-value"
                              data-utenti="'.$numUtentiNoAdmin.'"
                              data-admin="'.$riga2['numAdmin'].'"
                              data-totale="'.$riga['numUtenti'].'">'.$numUtentiNoAdmin.'</span>';
                ?>
                <div class="stat-pills" id="utenti-pills">
                    <button class="stat-pill active" data-target="utenti" data-label="Numero utenti">Utenti</button>
                    <button class="stat-pill"        data-target="admin"  data-label="Numero admin">Admin</button>
                    <button class="stat-pill"        data-target="totale" data-label="Numero totale utenti + admin">Totale</button>
                </div>
            </div>
            <div class="stat-card">
                <?php
                    $query = "SELECT COUNT(*) AS numDispense FROM dispense";
                    $ris = mysqli_query($conn,$query);
                    $riga = mysqli_fetch_assoc($ris);
                    echo '<span class="stat-label">Dispense pubblicate</span>';
                    echo '<span class="stat-value blue">'.$riga['numDispense'].'</span>';
                ?>               
            </div>
            <div class="stat-card">
                <span class="stat-label">Token totali posseduti dagli utenti</span>
                <?php
                    $query = "
                            SELECT SUM(saldo) AS totale
                            FROM utenti
                            WHERE ruolo = 0
                    ";#utenti ruolo = 0 admin = 1
                    $ris = mysqli_query($conn,$query);
                    $riga = mysqli_fetch_assoc($ris);
                    echo '<span class="stat-value yellow">'.$riga['totale'].'</span>'
                ?>            
            </div>
            <div class="stat-card">
                <span class="stat-label">Acquisti totali</span>
                <?php
                    $query = "SELECT COUNT(*) AS numAcquisti FROM acquisti";
                    $ris = mysqli_query($conn,$query);
                    $riga = mysqli_fetch_assoc($ris);
                    echo '<span class="stat-value green">'.$riga['numAcquisti'].'</span>';
                ?>   
            </div>
        </section>

        <section class="admin-content">
            <div class="admin-column">
                <h4>Gestione utenti</h4>
                <div class="admin-box no-padding">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>UTENTE</th>
                                <th>STATO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $query = "
                                    SELECT username,email,bloccato,ruolo,uni.nome AS uniNome, f.nome AS nomeFacolta
                                    FROM utenti,universita uni, facolta f
                                    WHERE utenti.id_universita = uni.id_universita
                                    AND utenti.id_facolta = f.id_facolta
                                ";
                                $ris = mysqli_query($conn,$query);
                                while($riga = mysqli_fetch_assoc($ris)){
                                    if($riga['ruolo'] == 0){
                                        echo '<tr>';
                                        echo    '<td>';
                                        echo        '<div class="user-info">';
                                        echo        ' <span class="user-name">'.$riga['username'].'</span>';
                                        echo        ' <span class="user-email">'.$riga['email'].'</span>';
                                        echo        ' <span class="user-email">'.$riga['uniNome'].'</span>';
                                        echo        ' <span class="user-email">'.$riga['nomeFacolta'].'</span>';
                                        echo        '</div>';
                                        echo    '</td>';
                                        echo    '<td>';
                                        if($riga['bloccato'] == 0){
                                            echo '<span class="status status-active">Attivo</span>';
                                        }else{
                                            echo '<span class="status status-blocked">Bloccato</span>';
                                        }
                                        echo    '</td>';
                                        echo '</tr>';
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="admin-column">
                <h4>Materiali recenti</h4>
                <div class="admin-box">
                    <?php
                        $query = "
                            SELECT * 
                            FROM dispense,utenti
                            WHERE utenti.id_utente = dispense.id_utente
                            AND utenti.bloccato = 0
                            ORDER BY data_caricamento DESC
                            LIMIT 5
                        ";
                        $ris = mysqli_query($conn,$query);
                        $cont = 0;
                        while($riga = mysqli_fetch_assoc($ris)){
                            $cont++;
                            echo '<div class="material-row">';
                            echo    '<div class="material-info">';
                            echo        '<h5>'.$riga['titolo'].'<h5>';
                            echo        '<p>di '. $riga['username'] .' • '. substr($riga['data_caricamento'],0,10) . '</p>';
                            echo    '</div>';
                            echo     '<button class="delete-btn">';
                            echo        '<img src="../../assets/download.png" alt="delete" style="filter: hue-rotate(140deg) saturate(3); transform: rotate(45deg);">';
                            echo     '</button>';
                            echo '</div>';  
                        }
                        if($cont == 0){
                            echo '<div class="material-info">';;
                            echo '<p>Non sono state ancora caricate dispense</p>';
                            echo '</div>';
                        }
                    ?>
                </div>
            </div>
        </section>
    </div>
</main>

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

        const valueEl = document.getElementById('utenti-value');
        const labelEl = document.getElementById('utenti-label');
        const pills   = document.querySelectorAll('#utenti-pills .stat-pill');

        pills.forEach(pill =>{
            pill.addEventListener('click', function(){
                pills.forEach(p => p.classList.remove('active'));
                this.classList.add('active');

                const target   = this.dataset.target;  // 'utenti' 'admin' 'totale'
                const newVal   = valueEl.dataset[target];
                const newLabel = this.dataset.label;

                valueEl.style.transition = 'opacity 0.15s';
                labelEl.style.transition = 'opacity 0.15s';
                valueEl.style.opacity    = '0';
                labelEl.style.opacity    = '0';
                setTimeout(() =>{
                    valueEl.textContent  = newVal;
                    labelEl.textContent  = newLabel;
                    valueEl.style.opacity = '1';
                    labelEl.style.opacity = '1';
                }, 150);
            });
        });
    });
</script>
</body>
</html>
