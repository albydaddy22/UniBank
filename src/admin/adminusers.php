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
    <title>UniBank - Gestione Utenti</title>
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
            <a href="adminpanoramica.php" class="tab-item">Panoramica</a>
            <a href="adminusers.php" class="tab-item active">Gestione utenti</a>
            <a href="adminmateriali.php" class="tab-item">Gestione materiali</a>
        </div>
    </nav>

    <div class="admin-container">
        <section class="admin-header-actions">
            <h4>Gestione Utenti</h4>
            <div class="search-bar">
                <input type="text" placeholder="Cerca utente per username o email">
                <button class="search-btn">Cerca</button>
            </div>
        </section>

        <section class="admin-content-full">
            <div class="admin-box no-padding">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>UTENTE</th>
                            <th>RUOLO</th>
                            <th>DATA ISCRIZIONE</th>
                            <th>TOKEN</th>
                            <th>STATO</th>
                            <th>AZIONI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $query = "SELECT * FROM utenti";
                            $ris = mysqli_query($conn, $query);
                            while($riga = mysqli_fetch_assoc($ris)){
                                echo '<tr>';
                                echo    '<td>';
                                echo        '<div class="user-info">';
                                echo            '<span class="user-name">'.$riga['username'].'</span>';
                                echo            '<span class="user-email">'.$riga['email'].'</span>';
                                echo        '</div>';
                                echo    '</td>';
                                echo    '<td>';
                                if($riga['ruolo'] == 0){
                                    echo '<span class="status status-user">Utente</span>';
                                }else{
                                    echo '<span class="status status-admin">Admin</span>';
                                }
                                echo    '</td>';
                                echo    '<td>' . substr($riga['data_iscrizione'], 0, 10) . '</td>';
                                echo    '<td><span class="token-count">' . ($riga['saldo'] ?? 0) . '</span></td>';
                                echo    '<td>';
                                if($riga['bloccato'] == 0){
                                    echo '<span class="status status-active">Attivo</span>';
                                }else{
                                    echo '<span class="status status-blocked">Bloccato</span>';
                                }
                                echo    '</td>';
                                echo    '<td>';
                                echo        '<div class="action-buttons">';
                                echo            '<button class="edit-btn">Modifica</button>';
                                if($riga['bloccato'] == 0){
                                    echo        '<button class="block-btn">Blocca</button>';
                                }else{
                                    echo        '<button class="unblock-btn">Sblocca</button>';
                                }
                                if($riga['ruolo'] == 0){
                                    echo        '<button class="approve-btn">Promuovi</button>';
                                }else{
                                    echo        '<button class="view-btn">Retrocedi</button>';
                                }
                                echo            '<button class="delete-btn-table">Elimina</button>';
                                echo        '</div>';
                                echo    '</td>';
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
