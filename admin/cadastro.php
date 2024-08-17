<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexão com o banco de dados
    $servername = "localhost:3306";
    $username = "root";
    $password = "";
    $dbname = "taskify";

    // Criando conexão com MySQL
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificando a conexão
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Capturando os dados do formulário
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    // Verificando se o email já existe
    $sql_email_check = "SELECT * FROM usuarios WHERE email = ?";
    $stmt_email_check = $conn->prepare($sql_email_check);
    $stmt_email_check->bind_param("s", $email);
    $stmt_email_check->execute();
    $result_email_check = $stmt_email_check->get_result();

    // Verificando se o username já existe
    $sql_username_check = "SELECT * FROM usuarios WHERE username = ?";
    $stmt_username_check = $conn->prepare($sql_username_check);
    $stmt_username_check->bind_param("s", $username);
    $stmt_username_check->execute();
    $result_username_check = $stmt_username_check->get_result();

    // Criptografando a senha
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Verificações antes de inserir os dados
    if ($password != $password2) {
        echo("As senhas não coincidem.");
    } elseif ($result_email_check->num_rows > 0) {
        echo("Email já existente.");
    } elseif ($result_username_check->num_rows > 0) {
        echo("Nome de usuário já existente.");
    } else {
        // Inserindo os dados no banco de dados
        $stmt = $conn->prepare("INSERT INTO usuarios (email, username, senha) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $username, $hashed_password);

        if ($stmt->execute()) {
            echo "Novo registro inserido com sucesso!";
        } else {
            echo "Erro ao inserir registro: " . $stmt->error;
        }

        $stmt->close();
    }

    $stmt_email_check->close();
    $stmt_username_check->close();
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
    <title>Cadastre-se</title>
</head>
<body>

    <header>
        <nav>
            <a class="logo" href="../index.php">Taskify</a>
        </nav>
    </header>

    <main>
        <div class="container">
            <h2>Cadastre-se</h2>
            <form action="" method="post">
                <div class="input-field">
                    <input type="email" name="email" id="email" placeholder="E-mail" autocomplete="off" required>
                </div>
                <div class="input-field">
                    <input type="text" name="username" id="username" placeholder="Nome" autocomplete="off" required>
                </div>
                <div class="input-field">
                    <input type="password" name="password" id="password" placeholder="Senha" autocomplete="off" required>
                </div>
                <div class="input-field">
                    <input type="password" name="password2" id="password2" placeholder="Confirme sua senha" autocomplete="off" required>
                </div>
                <input type="submit" value="Continue">
            </form>
            <div class="altent">
                <span>Ou</span>
                <div class="social-fields">
                    <div class="social-field facebook">
                        <a href="#">
                            <i class="fa-brands fa-facebook"></i>
                            Continue com o Facebook
                        </a>
                    </div>
                </div>
                <div class="social-fields">
                    <div class="social-field google">
                        <a href="#">
                            <i class="fa-brands fa-google"></i>
                            Continue com o Google
                        </a>
                    </div>
                </div>
            </div>
            <div class="ponte">
                <a href="http://localhost/TCC-taskify-main/admin/login.php">Já tenho uma conta</a>
            </div>
        </div>
    </main>

    <footer></footer>

    <script src="/js/cadlog.js"></script>
</body>
</html>
