<?php
session_start();
?>

<!doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="../../assets/logowhitebg.png">
    <title>UniBank - Profilo</title>
    <link rel="stylesheet" href="profile.css?=<?php echo time();?>">
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
                    <a href="#" class="listelement">Sfoglia</a>
                </li>
                <li>
                    <a href="#" class="listelement">Contattaci</a>
                </li>
                <?php if(!isset($_SESSION['is_logged']) || $_SESSION['is_logged'] != true){ ?>
                <li>
                    <a href="../authentication/frontend/login.php">
                        <button class="loginbtn">Login</button>
                    </a>
                </li>
                <li>
                    <a href="../authentication/frontend/signup.php">
                        <button class="signupbtn">Registrati</button>
                    </a>
                </li>
                <?php } else { ?>
                <li>
                    <div class="profileicon">
                        <img src="../../assets/user.png" alt="user">
                    </div>
                    <div class="userpopup">
                        <span>Ciao, <?php echo htmlspecialchars($_SESSION['username'] ?? 'user'); ?></span>
                        <span>Saldo: <?php echo htmlspecialchars($_SESSION['saldo'] ?? '0'); ?>
                            <img src="../../assets/unitoken.png" alt="UT"></span>
                        <a href="#" class="mioprofile">Visualizza profilo</a>
                        <a href="../authentication/backend/logout.php"><button class="logoutbtn">Logout</button></a>
                    </div>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</header>

<main class="profilepage">
    <div class="pfcontainer">
        <section class="pfheadercard">
            <div class="pfuser">
                <div class="pfavatar">
                    <?php
                    $initials = '';
                    if(isset($_SESSION['username'])){
                        $name = trim($_SESSION['username']);
                        $initials = strtoupper(substr($name,0,1));
                    } else { $initials = 'U'; }
                    ?>
                    <span><?php echo $initials; ?></span>
                </div>
                <div class="pfmeta">
                    <h3 class="pfname"><?php echo htmlspecialchars($_SESSION['username'] ?? 'user'); ?></h3>
                    <p class="pfuniv">Università • Facoltà</p>
                    <span class="pfbadge"><img src="../../assets/unitoken.png" alt="UT"> <?php echo htmlspecialchars($_SESSION['saldo'] ?? '45'); ?> UniToken</span>
                </div>
            </div>
            <div class="pfactions">
                <button class="editbtn">✎ Modifica profilo</button>
            </div>
        </section>

        <section class="pfstats">
            <div class="statcard">
                <span class="statlabel">Dispense caricate</span>
                <span class="statvalue">12</span>
            </div>
            <div class="statcard">
                <span class="statlabel">Dispense acquistate</span>
                <span class="statvalue">8</span>
            </div>
            <div class="statcard">
                <span class="statlabel">Token guadagnati</span>
                <span class="statvalue green">156</span>
            </div>
            <div class="statcard">
                <span class="statlabel">Token spesi</span>
                <span class="statvalue yellow">72</span>
            </div>
        </section>

        <section class="pfsections">
            <div class="pfcolumn">
                <h4>Le mie dispense</h4>
                <div class="pfbox">
                    <div class="pfboxrow">
                        <div>
                            <h5>Dispensa 1</h5>
                            <p>Corso 1</p>
                        </div>
                        <span class="pricebadge"><img src="../../assets/unitoken.png" alt="UT"> 12 token</span>
                    </div>\
                </div>
                <div class="pfbox">
                    <div class="pfboxrow">
                        <div>
                            <h5>Dispensa 2</h5>
                            <p>Corso 2</p>
                        </div>
                        <span class="pricebadge"><img src="../../assets/unitoken.png" alt="UT"> 15 token</span>
                    </div>
                </div>
            </div>
            <div class="pfcolumn">
                <h4>Dispense acquistate</h4>
                <div class="pfbox">
                    <div class="pfboxrow">
                        <div>
                            <h5>Dispensa 3</h5>
                            <p>di user</p>
                        </div>
                        <button class="downloadbtn">⬇ Download</button>
                    </div>
                </div>
                <div class="pfbox">
                    <div class="pfboxrow">
                        <div>
                            <h5>Dispensa 4</h5>
                            <p>di user</p>
                        </div>
                        <button class="downloadbtn">⬇ Download</button>
                    </div>
                </div>
            </div>
        </section>

        <div class="logoutsection">
            <a href="../authentication/backend/logout.php"><button class="exitbtn">→ Esci dall'account</button></a>
        </div>
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