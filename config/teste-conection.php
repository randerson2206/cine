<?php
include('config/db.php'); // Inclua o arquivo de configuração da conexão

// Teste a conexão
if ($db) {
    echo "Conexão bem-sucedida!";
} else {
    echo "Falha na conexão.";
}
?>
