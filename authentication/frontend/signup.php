<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniBank - Registrati</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <div class="maincontainer">
            <header class="navbar">
            <div class="nbcontainer">
                <div class="logo">
                <img src="../../assets/logo lungo7.png" alt="logo Unibank">
                </div>
                <div class="menu">
                    <ul>
                        <li>
                            <a href="../index.html">Home</a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>
        <div class="container">
            <div class="loginbox">
                <div class="loginheader">
                    <div class="logo2">
                        <img src="../../assets/logo.png" alt="logo Unibank">
                    </div>
                    <h2>Crea il tuo account</h2>
                    <p>Inizia a condividere e acquistare dispense</p>
                </div>
                <form action="../backend/inserimentoDBcredenziali.php" method="POST">
                    <div class="formgrid">
                        <div class="inputbox">
                            <label for="username">Username</label>
                            <input type="text" name="username" placeholder="Username" required>
                        </div>
                        <div class="inputbox">
                            <label for="email">Email</label>
                            <input type="email" name="email" placeholder="esempio@dominio.com" required>
                        </div>
                        <div class="inputbox">
                            <label for="password">Password</label>
                            <input type="password" name="password" placeholder="●●●●●●●●●" required>
                        </div>
                        <div class="inputbox">
                            <label for="confermapassword">Conferma Password</label>
                            <input type="password" name="confermapassword" placeholder="●●●●●●●●●" required>
                        </div>
                        <div class="inputbox fullwidth">
                            <label for="universita">Università</label>
                            <select name="universita" required>
                                <option value="">Seleziona università</option>
                                <?php
                                    require_once __DIR__ . '/../../config.php';
                                    $conn = db_connect();

                                    $query = "SELECT * FROM universita";
                                    $ris = mysqli_query($conn, $query);
                                    while($row = mysqli_fetch_assoc($ris)){
                                        $id = $row['id_universita'];
                                        $nomeUniversita = $row['nome'];
                                        $cittaSede = $row['citta_sede'];
                                        echo "<option value=\"$id\">$nomeUniversita - $cittaSede</option>";
                                    }
                                    mysqli_close($conn);
                                ?>
                                
                            </select>
                        </div>
                        <div class="inputbox fullwidth">
                            <label for="facolta">Facoltà</label>
                            <select name="facolta" required>
                                <option value="">Seleziona facoltà</option>
                                <?php
                                    require_once __DIR__ . '/../../config.php';
                                    $conn = db_connect();
                                    $query = "SELECT * FROM facolta";
                                    $ris = mysqli_query($conn, $query);
                                    while($row = mysqli_fetch_assoc($ris)){
                                        $id = $row['id_facolta'];
                                        $nomeFacolta = $row['nome'];
                                        echo "<option value=\"$id\">$nomeFacolta</option>";
                                    }
                                    mysqli_close($conn);
                                ?>
                            </select>
                        </div>
                    </div>


                    <button class="loginbtn" type="submit">Registrati</button>
                    <p class="donthaveaccount">Hai già un account? <a href="login.php">Accedi</a></p>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>