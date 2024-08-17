<?php
session_start(); // Certifique-se de que a sessão está iniciada

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Configuração da conexão com o banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "taskify";

    // Criando a conexão com o MySQL
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificando a conexão
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Capturando os dados do formulário
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Preparando a consulta para verificar o usuário
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificando se o usuário existe
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['senha'];

        // Verificando se a senha corresponde
        if (password_verify($password, $hashed_password)) {
            // Definindo a sessão do usuário
            $_SESSION['user_id'] = $row['id'];
            header("Location: ../index.php"); // Redireciona para a página inicial após login
            exit();
        } else {
            echo "Usuário ou senha incorretos.";
        }
    } else {
        echo "Usuário ou senha incorretos.";
    }

    // Fechando a conexão
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/cadlog.css">
    <script src="https://kit.fontawesome.com/e77ab0727d.js" crossorigin="anonymous"></script>
    <title>Faça Login</title>
</head>

<body>

    <header>
        <nav>
            <a class="logo" href="../index.php">Taskify</a>
        </nav>
    </header>


    <main>

        <div class="container">
            <h2> Login </h2>
            <form action="" method="POST">
                <div class="input-field">
                    <input type="text" name="username" id="username" placeholder="Nome" autocomplete="off" required>
                </div>

                <div class="input-field">
                    <input type="password" name="password" id="password" placeholder="Senha" autocomplete="off" required>
                </div>

                <input type="submit" value="Continue">

            </form>
            <div class="altent">

                <span> Ou </span>

                <div class="social-fields">
                    <div class="social-field facebook">
                        <a href="#">
                            <i class="fa-brands fa-facebook"></i>
                            Entre com o Facebook
                        </a>
                    </div>
                </div>

                <div class="social-fields">
                    <div class="social-field google">
                        <a href="#">
                            <i class="fa-brands fa-google"></i>
                            Entre com o Google
                        </a>
                    </div>
                </div>
            </div>

            <div class="ponte"> <a href="http://localhost/TCC-taskify-main/admin/cadastro.php"> Não tenho uma conta </a></div>
        </div>

    </main>


    <footer>

    </footer>

    <script src="/js/cadlog.js"></script>
</body>

</html>
