<?php
session_start();
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
            <a href="adminpanoramica.php" class="tab-item active">Panoramica</a>
            <a href="adminusers.php" class="tab-item">Gestione utenti</a>
            <a href="adminmateriali.php" class="tab-item">Gestione materiali</a>
        </div>
    </nav>

    <div class="admin-container">
        <section class="admin-stats">
            <div class="stat-card">
                <span class="stat-label">Utenti totali</span>
                <span class="stat-value blue">1.248</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Dispense pubblicate</span>
                <span class="stat-value blue">3.741</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Token in circolazione</span>
                <span class="stat-value yellow">28.430</span>
            </div>
            <div class="stat-card">
                <span class="stat-label">Acquisti totali</span>
                <span class="stat-value green">847</span>
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
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <span class="user-name">user</span>
                                        <span class="user-email">esempio@mail.com</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="status status-active">Attivo</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <span class="user-name">user</span>
                                        <span class="user-email">esempio@mail.com</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="status status-active">Attivo</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <span class="user-name">user</span>
                                        <span class="user-email">esempio@mail.com</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="status status-active">Attivo</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <span class="user-name">user</span>
                                        <span class="user-email">esempio@mail.com</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="status status-blocked">Bloccato</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="admin-column">
                <h4>Materiali recenti</h4>
                <div class="admin-box">
                    <div class="material-row">
                        <div class="material-info">
                            <h5>Dispensa 1</h5>
                            <p>di user • 15 Mar 2026</p>
                        </div>
                        <button class="delete-btn">
                            <img src="../../assets/download.png" alt="delete" style="filter: hue-rotate(140deg) saturate(3); transform: rotate(45deg);">
                        </button>
                    </div>
                    <div class="material-row">
                        <div class="material-info">
                            <h5>Dispensa 2</h5>
                            <p>di user • 14 Mar 2026</p>
                        </div>
                        <button class="delete-btn">
                            <img src="../../assets/download.png" alt="delete" style="filter: hue-rotate(140deg) saturate(3); transform: rotate(45deg);">
                        </button>
                    </div>
                    <div class="material-row">
                        <div class="material-info">
                            <h5>Dispensa 3</h5>
                            <p>di user • 13 Mar 2026</p>
                        </div>
                        <button class="delete-btn">
                            <img src="../../assets/download.png" alt="delete" style="filter: hue-rotate(140deg) saturate(3); transform: rotate(45deg);">
                        </button>
                    </div>
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
    });
</script>
</body>
</html>
