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
    <title>UniBank - Gestione Materiali</title>
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
                            }else{$initials = 'U'; }
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
            <a href="adminpanoramica.php" class="tab-item">Panoramica</a>
            <a href="adminusers.php" class="tab-item">Gestione utenti</a>
            <a href="adminmateriali.php" class="tab-item active">Gestione materiali</a>
        </div>
    </nav>

    <div class="admin-container">
        <section class="admin-header-actions">
            <h4>Gestione Materiali</h4>
            <div class="search-bar">
                <input type="text" placeholder="Cerca materiale per titolo o autore...">
                <button class="search-btn">Cerca</button>
            </div>
        </section>

        <section class="admin-content-full">
            <div class="admin-box no-padding">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>TITOLO</th>
                            <th>AUTORE</th>
                            <th>DATA CARICAMENTO</th>
                            <th>ACQUISTI</th>
                            <th>STATO</th>
                            <th>AZIONI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $query = "
                                    SELECT d.id_dispensa, d.titolo, d.data_caricamento, d.approvata, f.nome AS nomeFacolta, u.username AS nomeUser, u.bloccato,
                                           (SELECT COUNT(*) FROM acquisti WHERE id_dispensa = d.id_dispensa) AS numDownloads
                                    FROM dispense d, facolta f, utenti u
                                    WHERE d.id_utente = u.id_utente
                                    AND u.id_facolta = f.id_facolta
                            ";
                            $ris = mysqli_query($conn, $query);
                            while($riga = mysqli_fetch_assoc($ris)){
                                $data = substr($riga['data_caricamento'], 0, 10);
                                $downloads = $riga['numDownloads'] ?? 0;
                                
                                echo '<tr>';
                                echo '    <td>';
                                echo '        <div class="material-info-table">';
                                echo '            <span class="material-title">'.$riga['titolo'].'</span>';
                                echo '            <span class="material-faculty">'.$riga['nomeFacolta'].'</span>';
                                echo '        </div>';
                                echo '    </td>';
                                echo '    <td>'.$riga['nomeUser'].'</td>';
                                echo '    <td>'.$data.'</td>';
                                echo '    <td><span class="download-count">'.$downloads.'</span></td>';
                                
                                if($riga['approvata'] == 0 && $riga['bloccato'] == 0){
                                    echo '<td><span class="status status-pending">In revisione</span></td>';
                                    echo '<td>';
                                    echo '    <div class="action-buttons">';
                                    echo '        <a href="funzioniAdmin/approvaDispensa.php?id_dispensa='.$riga['id_dispensa'].'"><button class="approve-btn">Approva</button></a>';
                                    echo '        <a href="funzioniAdmin/valutaDispensaAI.php?id_dispensa='.$riga['id_dispensa'].'"><button class="view-btn" style="background-color: #8a2be2; border-color: #8a2be2;">Valuta AI</button></a>';
                                    echo '        <a href="../downloadDispense/downloadDispensa.php?id_dispensa='.$riga['id_dispensa'].'"><button class="view-btn">Vedi</button></a>';
                                    echo '        <a href="funzioniAdmin/eliminaDispensa.php?id_dispensa='.$riga['id_dispensa'].'" onclick="return confirm(\'Sei sicuro di voler eliminare questa dispensa? L\\\'azione non è reversibile.\')"><button class="delete-btn-table">Elimina</button></a>';
                                    echo '    </div>';
                                    echo '</td>';
                                    echo '</tr>';
                                    continue;
                                }else if($riga['approvata'] == 1 && $riga['bloccato'] == 0){
                                    echo '<td><span class="status status-active">Approvato</span></td>';
                                }else{
                                    echo '<td><span class="status status-blocked">Bloccata</span></td>';
                                }
                                echo '    <td>';
                                echo '        <div class="action-buttons">';
                                echo '            <a href="../downloadDispense/downloadDispensa.php?id_dispensa='.$riga['id_dispensa'].'"><button class="view-btn">Vedi</button></a>';
                                echo '            <a href="funzioniAdmin/eliminaDispensa.php?id_dispensa='.$riga['id_dispensa'].'" onclick="return confirm(\'Sei sicuro di voler eliminare questa dispensa? L\\\'azione non è reversibile.\')"><button class="delete-btn-table">Elimina</button></a>';
                                echo '        </div>';
                                echo '    </td>';
                                echo '</tr>';
                            }
                        ?>
                    </tbody>
                </table>
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
    });
</script>
</body>
</html>
