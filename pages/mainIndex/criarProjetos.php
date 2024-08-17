<?php
session_start();

// Configurações do banco de dados
$host = 'localhost:3306';
$dbname = 'taskify';
$username = 'root'; // Ajuste conforme necessário
$password = '';

// Conexão com o banco de dados usando PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../admin/login.php'); // Caminho ajustado para acessar 'login.php' na pasta 'admin'
    exit;
}

$user_id = $_SESSION['user_id'];

// Inserir nova tarefa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['descricao'])) {
    $descricao = $_POST['descricao'];
    $status = 'todo';

    $query = $pdo->prepare("INSERT INTO tarefas (usuario_id, descricao, status) VALUES (:usuario_id, :descricao, :status)");
    $query->execute(['usuario_id' => $user_id, 'descricao' => $descricao, 'status' => $status]);
}

// Atualizar tarefa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit-task-id'])) {
    $tarefaId = $_POST['edit-task-id'];
    $novaDescricao = $_POST['edit-descricao'];

    $query = $pdo->prepare("UPDATE tarefas SET descricao = :descricao WHERE id = :id AND usuario_id = :usuario_id");
    $query->execute(['descricao' => $novaDescricao, 'id' => $tarefaId, 'usuario_id' => $user_id]);
}

// Apagar tarefa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete-task-id'])) {
    $tarefaId = $_POST['delete-task-id'];

    $query = $pdo->prepare("DELETE FROM tarefas WHERE id = :id AND usuario_id = :usuario_id");
    $query->execute(['id' => $tarefaId, 'usuario_id' => $user_id]);
}

// Atualizar o status da tarefa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle-status-id'])) {
    $tarefaId = $_POST['toggle-status-id'];

    // Obter o status atual da tarefa
    $query = $pdo->prepare("SELECT status FROM tarefas WHERE id = :id AND usuario_id = :usuario_id");
    $query->execute(['id' => $tarefaId, 'usuario_id' => $user_id]);
    $tarefa = $query->fetch(PDO::FETCH_ASSOC);

    // Alternar o status
    $novoStatus = ($tarefa['status'] === 'todo') ? 'done' : 'todo';

    $updateQuery = $pdo->prepare("UPDATE tarefas SET status = :status WHERE id = :id AND usuario_id = :usuario_id");
    $updateQuery->execute(['status' => $novoStatus, 'id' => $tarefaId, 'usuario_id' => $user_id]);
}

// Buscar tarefas do usuário logado
$query = $pdo->prepare("SELECT * FROM tarefas WHERE usuario_id = :usuario_id");
$query->execute(['usuario_id' => $user_id]);
$tarefas = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/todo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Criar Projetos</title>
</head>
<body>

<header>
    <nav>
        <a class="logo" href="../../index.php">Taskify</a>
    </nav>  
</header>

<main>
    <div class="todo-container">
        <div id="todo-topo">
            <h1>To do task</h1>
        </div>

        <!-- Formulário para adicionar tarefa -->
        <form id="todo-form" method="POST" action="">
            <p>Adicione sua tarefa</p>
            <div class="form-control">
                <input type="text" name="descricao" id="todo-input" placeholder="O que você vai fazer?" required />
                <button type="submit">
                    <i class="fa-thin fa-plus"></i>
                </button>
            </div>
        </form>

        <!-- Formulário para editar tarefa (aparece ao clicar em editar) -->
        <form id="edit-form" class="hide" method="POST" action="">
            <p>Edite sua tarefa</p>
            <div class="form-control">
                <input type="text" name="edit-descricao" id="edit-input" required />
                <input type="hidden" name="edit-task-id" id="edit-task-id" />
                <button type="submit">
                    <i class="fa-solid fa-check-double"></i>
                </button>
            </div>
            <button type="button" id="cancel-edit-btn">Cancelar</button>
        </form>

        <!-- Barra de ferramentas (pesquisa e filtro) -->
        <div id="toolbar">
            <div id="search">
                <h4>Pesquisar:</h4>
                <form>
                    <input type="text" id="search-input" placeholder="Buscar..." />
                    <button id="erase-button">
                        <i class="fa-solid fa-delete-left"></i>
                    </button>
                </form>
            </div>
            <div id="filter">
                <h4>Filtrar:</h4>
                <select id="filter-select">
                    <option value="all">Todos</option>
                    <option value="done">Feitos</option>
                    <option value="todo">A fazer</option>
                </select>
            </div>
        </div>

        <!-- Listagem de tarefas -->
        <div id="todo-list">
            <?php foreach ($tarefas as $tarefa): ?>
                <div class="todo <?= $tarefa['status'] === 'done' ? 'done' : '' ?>" data-id="<?= $tarefa['id'] ?>">
                    <h3><?= htmlspecialchars($tarefa['descricao']) ?></h3>
                    <form method="POST" action="" class="inline-form">
                        <input type="hidden" name="toggle-status-id" value="<?= $tarefa['id'] ?>" />
                        <button type="submit" class="toggle-status">
                            <i class="fa-solid fa-check"></i>
                        </button>
                    </form>
                    <button class="edit-todo" data-id="<?= $tarefa['id'] ?>" data-descricao="<?= htmlspecialchars($tarefa['descricao']) ?>">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <form method="POST" action="" class="inline-form">
                        <input type="hidden" name="delete-task-id" value="<?= $tarefa['id'] ?>" />
                        <button type="submit" class="remove-todo">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<footer>

</footer>

<script>
// JavaScript para mostrar o formulário de edição com os dados da tarefa
document.querySelectorAll('.edit-todo').forEach(button => {
    button.addEventListener('click', function () {
        const taskId = this.dataset.id;
        const descricao = this.dataset.descricao;

        document.getElementById('edit-form').classList.remove('hide');
        document.getElementById('todo-form').classList.add('hide');

        document.getElementById('edit-input').value = descricao;
        document.getElementById('edit-task-id').value = taskId;
    });
});

// Botão de cancelar edição
document.getElementById('cancel-edit-btn').addEventListener('click', function () {
    document.getElementById('edit-form').classList.add('hide');
    document.getElementById('todo-form').classList.remove('hide');
});

// Funções de filtro e busca
document.getElementById('search-input').addEventListener('input', function () {
    const search = this.value.toLowerCase();
    document.querySelectorAll('.todo').forEach(todo => {
        const text = todo.querySelector('h3').innerText.toLowerCase();
        todo.style.display = text.includes(search) ? 'flex' : 'none';
    });
});

document.getElementById('erase-button').addEventListener('click', function (e) {
    e.preventDefault();
    document.getElementById('search-input').value = '';
    document.getElementById('search-input').dispatchEvent(new Event('input'));
});

document.getElementById('filter-select').addEventListener('change', function () {
    const filterValue = this.value;
    document.querySelectorAll('.todo').forEach(todo => {
        const isDone = todo.classList.contains('done');
        switch (filterValue) {
            case 'all':
                todo.style.display = 'flex';
                break;
            case 'done':
                todo.style.display = isDone ? 'flex' : 'none';
                break;
            case 'todo':
                todo.style.display = !isDone ? 'flex' : 'none';
                break;
        }
    });
});
</script>

</body>
</html>
