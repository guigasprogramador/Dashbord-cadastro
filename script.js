$(document).ready(function() {
    // Cadastro de Clientes
    $('#formCadastro').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: 'index.php',
            data: $(this).serialize(),
            success: function(response) {
                alert(response);
                $('#formCadastro')[0].reset();
            }
        });
    });

    // Consulta de Clientes
    function carregarClientes() {
        $.ajax({
            url: 'consulta.php',
            method: 'GET',
            success: function(response) {
                var clientes = JSON.parse(response);
                var tbody = $('#clientesTableBody');
                tbody.empty();
                
                clientes.forEach(function(cliente) {
                    var tr = $('<tr>');
                    tr.append('<td>' + cliente.id + '</td>');
                    tr.append('<td>' + cliente.nome + '</td>');
                    tr.append('<td>' + cliente.telefone + '</td>');
                    tr.append('<td>' + cliente.email + '</td>');
                    tr.append('<td>' + cliente.endereco + '</td>');
                    tr.append('<td>' + cliente.data_aquisicao + '</td>');
                    tr.append('<td>' + cliente.periodicidade + '</td>');
                    tr.append('<td>' + cliente.data_cadastro + '</td>');
                    tr.append('<td>' + cliente.validade + '</td>');
                    tbody.append(tr);
                });
            }
        });
    }

    carregarClientes();

    // Gerar Relatório de Validade
    $('#gerarRelatorio').on('click', function() {
        $.ajax({
            url: 'relatorio.php',
            method: 'GET',
            success: function(response) {
                var clientes = JSON.parse(response);
                var tbody = $('#relatorioTableBody');
                tbody.empty();
                
                clientes.forEach(function(cliente) {
                    var tr = $('<tr>');
                    tr.append('<td>' + cliente.id + '</td>');
                    tr.append('<td>' + cliente.nome + '</td>');
                    tr.append('<td>' + cliente.telefone + '</td>');
                    tr.append('<td>' + cliente.email + '</td>');
                    tr.append('<td>' + cliente.endereco + '</td>');
                    tr.append('<td>' + cliente.data_aquisicao + '</td>');
                    tr.append('<td>' + cliente.periodicidade + '</td>');
                    tr.append('<td>' + cliente.data_cadastro + '</td>');
                    tr.append('<td>' + cliente.validade + '</td>');
                    tbody.append(tr);
                });

                gerarPDF(clientes);
            }
        });
    });

    function gerarPDF(clientes) {
        var doc = new jsPDF();

        doc.text("Relatório de Clientes com Validade Próxima", 10, 10);
        var columns = ["ID", "Nome", "Telefone", "Email", "Endereço", "Data de Aquisição", "Periodicidade", "Data de Cadastro", "Validade"];
        var rows = [];

        clientes.forEach(function(cliente) {
            rows.push([
                cliente.id, cliente.nome, cliente.telefone, cliente.email, cliente.endereco, 
                cliente.data_aquisicao, cliente.periodicidade, cliente.data_cadastro, cliente.validade
            ]);
        });

        doc.autoTable(columns, rows, { startY: 20 });
        doc.save('relatorio_validade.pdf');
    }
});
