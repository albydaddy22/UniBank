<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniBank - Login</title>
    <link rel="stylesheet" href="login.css?=<?php echo time();?>">
    <link rel="stylesheet" href="../../variables.css">
</head>
<body>
    <div class="maincontainer">
        <header class="navbar">
        <div class="nbcontainer">
            <div class="logo">
            <img src="../../../assets/logo lungo7.png" alt="logo Unibank">
            </div>
            <div class="menu">
                <ul>
                    <li>
                        <a href="../../index.php">Home</a>
                    </li>
                </ul>
            </div>
        </div>
        </header>
        <div class="container">
            <div class="loginbox">
                <div class="loginheader">
                    <div class="logo2">
                        <img src="../../../assets/logo.png" alt="logo Unibank">
                    </div>
                    <h2>Bentornato!</h2>
                    <p>Accedi al tuo account Unibank</p>
                </div>
                <form action="../backend/elaborazioneLogin.php" method="POST">
                    <div class="inputbox">
                        <label for="email">Email</label>
                        <input type="text" name="email" placeholder="esempio@dominio.com" required>
                    </div>
                    <div class="inputbox">
                        <label for="password">Password</label>
                        <input type="password" name="password" placeholder="●●●●●●●●●" required>
                        <a href="resetpassword.php">Password dimenticata?</a>
                    </div>
                    <button class="loginbtn" type="submit">Login</button>
                    <p class="donthaveaccount">Non hai un account? <a href="signup.php">Registrati</a></p>
                </form>
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
                            <a href="../../index.php">Home</a>
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
            <p class="copyright">© 2026 UniBank. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>