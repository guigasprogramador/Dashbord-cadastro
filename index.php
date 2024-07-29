<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se a ação é cadastrar
    if (isset($_POST['action']) && $_POST['action'] == 'cadastrar') {
        $nome = $_POST['nome'];
        $telefone = $_POST['telefone'];
        $email = $_POST['email'];
        $endereco = $_POST['endereco'];
        $data_aquisicao = $_POST['data_aquisicao'];
        $periodicidade = $_POST['periodicidade'];
        $validade = $_POST['validade'];

        $sql = "INSERT INTO clientes (nome, telefone, email, endereco, data_aquisicao, periodicidade, validade)
                VALUES ('$nome', '$telefone', '$email', '$endereco', '$data_aquisicao', '$periodicidade', '$validade')";

        if ($conn->query($sql) === TRUE) {
            echo "Novo cliente cadastrado com sucesso";
        } else {
            echo "Erro: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrativo</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Dashboard Administrativo</h1>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#cadastro">Cadastro</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#consulta">Consulta</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#relatorio">Relatório de Validade</a>
            </li>
        </ul>
        
        <div class="tab-pane container active" id="cadastro">
            <h2 class="mt-4">Cadastro de Clientes</h2>
            <form id="formCadastro" action="index.php" method="post">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" class="form-control" id="nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone:</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="endereco">Endereço:</label>
                    <textarea class="form-control" id="endereco" name="endereco" required></textarea>
                </div>
                <div class="form-group">
                    <label for="data_aquisicao">Data de Aquisição:</label>
                    <input type="date" class="form-control" id="data_aquisicao" name="data_aquisicao" required>
                </div>
                <div class="form-group">
                    <label for="periodicidade">Periodicidade:</label>
                    <select class="form-control" id="periodicidade" name="periodicidade" required>
                        <option value="anual">Anual</option>
                        <option value="mensal">Mensal</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="validade">Validade:</label>
                    <input type="date" class="form-control" id="validade" name="validade" required>
                </div>
                <input type="hidden" name="action" value="cadastrar">
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </form>
        </div>
            
        <div class="tab-pane container fade" id="consulta">
            <h2 class="mt-4">Consulta de Clientes</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Data de Cadastro</th>
                        <th>Validade</th>
                    </tr>
                </thead>
                <tbody id="clientesTableBody">
                    <!-- Dados serão preenchidos aqui -->
                    <?php
                    $sql = "SELECT * FROM clientes";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>" . $row["id"]. "</td>
                                <td>" . $row["nome"]. "</td>
                                <td>" . $row["email"]. "</td>
                                <td>" . $row["data_aquisicao"]. "</td>
                                <td>" . $row["validade"]. "</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>Nenhum cliente encontrado</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
            
        <div class="tab-pane container fade" id="relatorio">
    <h2 class="mt-4">Relatório de Validade</h2>
    <a href="gerar_relatorio.php" class="btn btn-success mb-3" id="gerarRelatorio">Gerar Relatório</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Data de Cadastro</th>
                <th>Validade</th>
            </tr>
        </thead>
        <tbody id="relatorioTableBody">
            <!-- Dados serão preenchidos aqui -->
            <?php
            $hoje = date('Y-m-d');
            $alerta_sql = "SELECT * FROM clientes WHERE validade BETWEEN '$hoje' AND DATE_ADD('$hoje', INTERVAL 5 DAY)";
            $alerta_result = $conn->query($alerta_sql);

            if ($alerta_result->num_rows > 0) {
                while ($alerta_row = $alerta_result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . $alerta_row["id"]. "</td>
                        <td>" . $alerta_row["nome"]. "</td>
                        <td>" . $alerta_row["email"]. "</td>
                        <td>" . $alerta_row["data_aquisicao"]. "</td>
                        <td>" . $alerta_row["validade"]. "</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Nenhum cliente com validade próxima</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>

    <script>
        document.getElementById('data_aquisicao').addEventListener('change', function() {
            let dataAquisicao = new Date(this.value);
            let validade = new Date(dataAquisicao);
            validade.setFullYear(validade.getFullYear() + 1);
            document.getElementById('validade').value = validade.toISOString().split('T')[0];
        });
    </script>
</body>
</html>
