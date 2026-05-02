<?php
session_start();
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
                            <th>DOWNLOADS</th>
                            <th>STATO</th>
                            <th>AZIONI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="material-info-table">
                                    <span class="material-title">Nome Dispensa</span>
                                    <span class="material-faculty">Facolta</span>
                                </div>
                            </td>
                            <td>Peter Drum</td>
                            <td>15/03/2026</td>
                            <td><span class="download-count">128</span></td>
                            <td><span class="status status-active">Approvato</span></td> <!-- devi cambiare oltre al testo (approvato, in revisione ecc..) anche le classi (class="")-->
                            <td>
                                <div class="action-buttons">
                                    <button class="view-btn">Vedi</button>
                                    <button class="delete-btn-table">Elimina</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="material-info-table">
                                    <span class="material-title">Nome Dispensa</span>
                                    <span class="material-faculty">Facolta</span>
                                </div>
                            </td>
                            <td>Elena Lombardi</td>
                            <td>14/03/2026</td>
                            <td><span class="download-count">45</span></td>
                            <td><span class="status status-active">Approvato</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="view-btn">Vedi</button>
                                    <button class="delete-btn-table">Elimina</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="material-info-table">
                                    <span class="material-title">Nome Dispensa</span>
                                    <span class="material-faculty">Facolta</span>
                                </div>
                            </td>
                            <td>Nicola Rendina</td>
                            <td>13/03/2026</td>
                            <td><span class="download-count">0</span></td>
                            <td><span class="status status-pending">In revisione</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="approve-btn">Approva</button>
                                    <button class="view-btn">Vedi</button>
                                    <button class="delete-btn-table">Elimina</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="material-info-table">
                                    <span class="material-title">Nome Dispensa</span>
                                    <span class="material-faculty">Facolta</span>
                                </div>
                            </td>
                            <td>Mamma</td>
                            <td>10/03/2026</td>
                            <td><span class="download-count">210</span></td>
                            <td><span class="status status-active">Approvato</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="view-btn">Vedi</button>
                                    <button class="delete-btn-table">Elimina</button>
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
