<?php
session_start();
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
                        <a href="authentication/backend/logout.php"><button class="logoutbtn">Logout</button></a>
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
                <input type="text" placeholder="Cerca utente per nome o email...">
                <button class="search-btn">Cerca</button>
            </div>
        </section>

        <section class="admin-content-full">
            <div class="admin-box no-padding">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>UTENTE</th>
                            <th>DATA ISCRIZIONE</th>
                            <th>TOKEN</th>
                            <th>STATO</th>
                            <th>AZIONI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <span class="user-name">user</span>
                                    <span class="user-email">user@esempio.it</span>
                                </div>
                            </td>
                            <td>12/03/2026</td>
                            <td><span class="token-count">450</span></td>
                            <td><span class="status status-active">Attivo</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="edit-btn">Modifica</button>
                                    <button class="block-btn">Blocca</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <span class="user-name">user</span>
                                    <span class="user-email">user@email.com</span>
                                </div>
                            </td>
                            <td>05/03/2026</td>
                            <td><span class="token-count">35</span></td>
                            <td><span class="status status-active">Attivo</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="edit-btn">Modifica</button>
                                    <button class="block-btn">Blocca</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <span class="user-name">user</span>
                                    <span class="user-email">user@email.com</span>
                                </div>
                            </td>
                            <td>01/03/2026</td>
                            <td><span class="token-count">0</span></td>
                            <td><span class="status status-blocked">Bloccato</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="unblock-btn">Sblocca</button>
                                    <button class="edit-btn">Modifica</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <span class="user-name">user</span>
                                    <span class="user-email">user@esempio.com</span>
                                </div>
                            </td>
                            <td>25/02/2026</td>
                            <td><span class="token-count">1250</span></td>
                            <td><span class="status status-active">Attivo</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="edit-btn">Modifica</button>
                                    <button class="block-btn">Blocca</button>
                                </div>
                            </td>
                        </tr>
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
