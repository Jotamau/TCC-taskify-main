<?php
session_start();

// Verifica se o usuário está logado
$usuarioLogado = isset($_SESSION['user_id']);

// Redireciona para a página de login se o usuário não estiver logado
if (!$usuarioLogado) {
    header('Location: ../admin/login.php'); // Caminho relativo para a página de login
    exit();
}

// O restante do código para a criação de projetos
?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bem-vindo ao Taskify</title>
    <link rel="stylesheet" href="css/global.css" />
  </head>

  <body>
    <header>
      <nav>
        <a class="logo" href="/">Taskify</a>
        <div class="mobile-menu">
          <div class="line1"></div>
          <div class="line2"></div>
          <div class="line3"></div>
        </div>
        <ul class="nav-list">
          <li><a href="pages/navBar/sobre.php">Sobre</a></li>
          <li><a href="pages/navBar/projetos.php">Projetos atribuídos</a></li>
          <li><a href="pages/navBar/contato.php">Contato</a></li> 

          <div class="space"></div>

          <?php if (!$usuarioLogado): ?>
            <li><a href="admin/login.php">Login</a></li>
            <li><a href="admin/cadastro.php" id="cad">Cadastre-se</a></li>
          <?php else: ?>
            <li><a href="admin/login.php">Login</a></li>
            <li><a href="admin/cadastro.php">Cadastro</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </header>
    
    <main>
      <div class="index-box1">
        <img src="assets/imgs de introdução/index-img-main.png" alt="IMG de boas vindas ao TASKIFY">
      </div>

      <div class="aux"></div>

      <div class="index-box2">
        <div class="card1">
          <span>Perfil</span>
          <p>
            <a href="admin/config/userConfig.php">
              <img src="assets/imgs hrefs index/perfil vazio.png" alt="Imagem de perfil">
            </a>
          </p>
        </div>

        <div class="card2">
          <span>Desempenho</span>  
          <p>
            <a href="pages/mainIndex/desempenho.php">
              <img src="assets/imgs hrefs index/Gráfico exemplo.png" alt="Gráfico ilustrativo">
            </a>
          </p>
        </div>

        <div class="card3">
          <span>Criar Projetos</span>
          <p>
            <a href="pages/mainIndex/criarProjetos.php">
              <img src="assets/imgs hrefs index/Criar projetos.png" alt="Criar projetos">
            </a>
          </p>
        </div>

        <div class="card4">
          <span>Empty</span>
          <p>
            <img src="assets/imgs hrefs index/empty.png" alt="empty">
          </p>
        </div>

        <div class="card4">
          <span>Empty</span>
          <p>
            <img src="assets/imgs hrefs index/empty.png" alt="empty">
          </p>
        </div>
      </div>
    </main>

    <footer>
      <!-- O conteúdo do rodapé vai aqui -->
    </footer>

    <script src="js/global.js"></script>
  </body>
</html>
