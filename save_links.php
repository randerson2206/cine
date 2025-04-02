<?php include('views/header.php'); ?>

<h1 class="text-center mb-4">Links Salvos</h1>

<ul id="saved-links-list">
    <!-- Os links salvos serão carregados aqui -->
</ul>

<script>
    // Função para mostrar os links salvos
    function showSavedLinks() {
        let savedLinks = JSON.parse(localStorage.getItem('savedLinks')) || [];
        let html = '';
        
        savedLinks.forEach(link => {
            html += `<li><a href="${link}" target="_blank">${link}</a></li>`;
        });
        
        document.getElementById('saved-links-list').innerHTML = html;
    }

    // Chama a função para mostrar os links salvos
    showSavedLinks();
</script>

<?php include('views/footer.php'); ?>
