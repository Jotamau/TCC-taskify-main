<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato TASKIFY</title>
    <link rel="stylesheet" href="../../css/info.css">
</head>
<body>
    <header>
        <nav>
            <a class="logo" href="../../index.php">Taskify</a>
        </nav>
    </header>

    <main>

        <div class="container"> 
            <h2>Contato</h2>

            <div class="notice">
                <span> Olá, usuário! Ajude-nos a melhorar nosso site.</span>
            </div>

            <form action="https://formsubmit.co/taskifycompany@gmail.com" method="post">
                
                <textarea name="mensagem" id="mensagem" cols="30" rows="10" placeholder="Digite sua mensagem" required></textarea>

                <input type="submit" value="Enviar" >
                
                <input type="hidden" name="redirectTo" value="http://127.0.0.1:3000/pages/ctt-thx.html">

            </form>
        </div>

    </main>


    <footer>

    </footer>
</body>
</html>