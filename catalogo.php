<?php
session_start();
include 'include/config.php';

// Lidar com solicitação de logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}

// Buscar produtos do banco de dados
$sql = "SELECT id, name, description, category, image_url FROM products";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo</title>
    <link rel="stylesheet" href="css/catalogo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <link rel="stylesheet" href="css/style1.css" />
</head>
<body>

<div class="intro-section">
        <!-- Navbar inicial -->
        <nav class="static-navbar">
            <div class="navbar-container">
                <div class="logo">
                    <a href="#">LOGO</a>
                </div>
                <ul class="navbar-links">
                    <li><a href="index.html">HOME</a></li>
                    <li><a href="catalogo.php">CATÁLAGO</a></li>
                    <li><a href="aboutus.html">SOBRE NÓS</a></li>
                    <li><a href="contact.html">CONTATO</a></li>
                </ul>

                </div>
            </div>
        </nav>



    <div class="container">
        <div class="sidebar">
            <div class="filters">
                <h2>Filtros</h2>
                <div class="search-bar">
                    <input type="text" placeholder="Buscar" id="search">
                    <i class="fas fa-magnifying-glass search-icon"></i>
                </div>
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <button id="add-product" class="styled-button" data-modal-target="#addProductModal">Adicionar produto</button>
                    <a href="?logout=true" class="styled-button" style="margin-top:10px;">Logout</a>
                <?php else: ?>

                <?php endif; ?>
                <div class="categories">
                    <div class="category">
                        <input type="checkbox" class="filter-category" value="Telhas Metálicas" id="metalicas">
                        <label for="metalicas">Telhas Metálicas</label>
                        <div class="sub-categories">
                            <input type="checkbox" class="filter-category" value="Naturais" id="naturais">
                            <label for="naturais">Naturais</label><br>
                            <input type="checkbox" class="filter-category" value="Pós-Pintadas" id="pos-pintadas">
                            <label for="pos-pintadas">Pós-Pintadas</label><br>
                            <input type="checkbox" class="filter-category" value="Simples" id="simples">
                            <label for="simples">Simples</label><br>
                            <input type="checkbox" class="filter-category" value="Termoacústicas com Poliestireno (isopor)" id="termoacusticas">
                            <label for="termoacusticas">Termoacústicas com Poliestireno (isopor)</label><br>
                        </div>
                    </div>
                    <div class="category">
                        <input type="checkbox" class="filter-category" value="Telhas Translúcidas" id="translucidas">
                        <label for="translucidas">Telhas Translúcidas</label>
                    </div>
                    <div class="category">
                        <input type="checkbox" class="filter-category" value="Perfis" id="perfis">
                        <label for="perfis">Perfis</label>
                    </div>
                    <div class="category">
                        <input type="checkbox" class="filter-category" value="Parafusos" id="parafusos">
                        <label for="parafusos">Parafusos</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="product-list" id="product-list">
            <!-- Mensagens de Sucesso ou Erro -->
            <?php if (isset($_SESSION['add_product_success'])): ?>
                <div class="success-message">
                    <?php 
                        echo $_SESSION['add_product_success']; 
                        unset($_SESSION['add_product_success']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['add_product_error'])): ?>
                <div class="error-message">
                    <?php 
                        echo $_SESSION['add_product_error']; 
                        unset($_SESSION['add_product_error']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['edit_product_success'])): ?>
                <div class="success-message">
                    <?php 
                        echo $_SESSION['edit_product_success']; 
                        unset($_SESSION['edit_product_success']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['edit_product_error'])): ?>
                <div class="error-message">
                    <?php 
                        echo $_SESSION['edit_product_error']; 
                        unset($_SESSION['edit_product_error']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['delete_product_success'])): ?>
                <div class="success-message">
                    <?php 
                        echo $_SESSION['delete_product_success']; 
                        unset($_SESSION['delete_product_success']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['delete_product_error'])): ?>
                <div class="error-message">
                    <?php 
                        echo $_SESSION['delete_product_error']; 
                        unset($_SESSION['delete_product_error']);
                    ?>
                </div>
            <?php endif; ?>

            <div id="filtered-products">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product-card" data-categories='<?php echo json_encode($product['category']); ?>'>
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                                <button class="edit-product styled-button" data-id="<?php echo $product['id']; ?>" data-modal-target="#editProductModal">Editar</button>
                                <button class="delete-product styled-button" data-id="<?php echo $product['id']; ?>" data-modal-target="#deleteProductModal">Remover</button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhum produto encontrado.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Login Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Login</h2>
            <form method="POST" action="login.php">
                <label for="username">Usuário:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Senha:</label>
                <div class="password-field">
                    <input type="password" id="password" name="password" required>
                    <span id="togglePassword" class="toggle-icon"><i class="fas fa-eye-slash"></i></span>
                </div>
                <button type="submit" name="login">Entrar</button>
                <?php if (isset($_SESSION['login_error'])): ?>
                    <div class="error-message">
                        <?php 
                            echo $_SESSION['login_error']; 
                            unset($_SESSION['login_error']);
                        ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
        <div id="addProductModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Adicionar Produto</h2>
                <form id="addProductForm" action="add_product.php" method="POST">
                    <label for="name">Nome do Produto:</label>
                    <input type="text" id="name" name="name" required>

                    <label for="description">Descrição:</label>
                    <textarea id="description" name="description"></textarea>

                    <label for="category">Categoria:</label>
                    <select id="category" name="category[]" multiple>
                        <option value="Telhas Metálicas">Telhas Metálicas</option>
                        <option value="Naturais">Naturais</option>
                        <option value="Pós-Pintadas">Pós-Pintadas</option>
                        <option value="Simples">Simples</option>
                        <option value="Termoacústicas com Poliestireno (isopor)">Termoacústicas com Poliestireno (isopor)</option>
                        <option value="Telhas Translúcidas">Telhas Translúcidas</option>
                        <option value="Perfis">Perfis</option>
                        <option value="Parafusos">Parafusos</option>
                    </select>

                    <label for="image_url">URL da Imagem:</label>
                    <input type="text" id="image_url" name="image_url">

                    <button type="submit" name="add_product">Adicionar produto</button>
                </form>
            </div>
        </div>

        <div id="editProductModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Editar Produto</h2>
                <form id="editProductForm" action="edit_product.php" method="POST">
                    <input type="hidden" id="edit_product_id" name="product_id">
                    <label for="edit_name">Nome do Produto:</label>
                    <input type="text" id="edit_name" name="name" required>

                    <label for="edit_description">Descrição:</label>
                    <textarea id="edit_description" name="description"></textarea>

                    <label for="edit_category">Categoria:</label>
                    <select id="edit_category" name="category[]" multiple>
                        <option value="Telhas Metálicas">Telhas Metálicas</option>
                        <option value="Naturais">Naturais</option>
                        <option value="Pós-Pintadas">Pós-Pintadas</option>
                        <option value="Simples">Simples</option>
                        <option value="Termoacústicas com Poliestireno (isopor)">Termoacústicas com Poliestireno (isopor)</option>
                        <option value="Telhas Translúcidas">Telhas Translúcidas</option>
                        <option value="Perfis">Perfis</option>
                        <option value="Parafusos">Parafusos</option>
                    </select>

                    <label for="edit_image_url">URL da Imagem:</label>
                    <input type="text" id="edit_image_url" name="image_url">

                    <button type="submit" name="edit_product">Salvar alterações</button>
                </form>
            </div>
        </div>

        <div id="deleteProductModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Remover Produto</h2>
                <form id="deleteProductForm" action="delete_product.php" method="POST">
                    <input type="hidden" id="delete_product_id" name="product_id">
                    <p>Tem certeza de que deseja remover este produto?</p>
                    <div class="modal-buttons">
                        <button type="submit" name="delete_product" class="styled-button">Sim, remover</button>
                        <button type="button" class="cancel-button">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const addCategorySelect = document.getElementById('category');
        const editCategorySelect = document.getElementById('edit_category');
        
        // Inicializar Choices.js para o campo de categorias de adição
        const addProductChoices = new Choices(addCategorySelect, {
            removeItemButton: true,
            placeholderValue: 'Selecione as categorias',
            searchPlaceholderValue: 'Digite para buscar',
        });

        // Inicializar Choices.js para o campo de categorias de edição
        const editChoices = new Choices(editCategorySelect, {
            removeItemButton: true,
            placeholderValue: 'Selecione as categorias',
            searchPlaceholderValue: 'Digite para buscar',
        });
        
        // Limpar seleção de categorias ao fechar o modal de criação
        const addProductModal = document.getElementById('addProductModal');
        addProductModal.addEventListener('hidden.bs.modal', () => {
            addProductChoices.clearStore();  // Limpa todas as categorias selecionadas
        });

        // Função para carregar as categorias do produto no modal de edição
        function loadProductCategories(categories) {
            editChoices.clearStore();  // Limpa as opções selecionadas anteriores
            editChoices.setChoices(categories.map(category => ({ value: category, label: category, selected: true })));
        }

        // Configurar o botão de edição para carregar dados no modal
        const editButtons = document.querySelectorAll('.edit-product');
        editButtons.forEach(button => {
            button.addEventListener('click', () => {
                const productId = button.getAttribute('data-id');
                fetch(`get_product.php?id=${productId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Preencher os campos do modal com os dados do produto
                            document.getElementById('edit_product_id').value = data.product.id;
                            document.getElementById('edit_name').value = data.product.name;
                            document.getElementById('edit_description').value = data.product.description;
                            document.getElementById('edit_image_url').value = data.product.image_url;

                            // Carregar as categorias do produto no campo de categorias do modal de edição
                            loadProductCategories(data.product.categories);

                            // Abrir o modal de edição de produto
                            new bootstrap.Modal(document.getElementById('editProductModal')).show();
                        } else {
                            alert('Erro ao obter os detalhes do produto.');
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                    });
            });
        });

        // Capturar a submissão do formulário de edição para enviar as categorias selecionadas
        const editProductForm = document.getElementById('editProductForm');
        editProductForm.addEventListener('submit', function(event) {
            event.preventDefault();

            // Capturar as categorias selecionadas no Choices.js
            const selectedCategories = editChoices.getValue(true);

            // Adicionar categorias selecionadas ao formulário antes de enviar
            const categoryInput = document.createElement('input');
            categoryInput.type = 'hidden';
            categoryInput.name = 'category[]';
            categoryInput.value = JSON.stringify(selectedCategories);

            // Remover qualquer input escondido anterior e adicionar o novo ao formulário
            editProductForm.querySelectorAll('input[name="category[]"]').forEach(e => e.remove());
            editProductForm.appendChild(categoryInput);

            // Enviar o formulário
            editProductForm.submit();
        });
    });
</script>

<footer>
            <div class="footer-content">
                <!-- Logo -->
                <div class="footer-logo">
                    <img src="logo.png" alt="Ventilux Logo">
                </div>
                
                <!-- Contato -->
                <div class="footer-contact">
                    <h3 class="footer-contact">Contato</h3>
                    <p>
                        <img src="whatsapp-icon.png" alt="WhatsApp"> (19) 99294-7782
                    </p>
                    <p>
                        <img src="email-icon.png" alt="Email"> marciocerchiari@gmail.com
                    </p>
                </div>
                
                <!-- Direitos Autorais -->
                <div class="footer-copyright">
                    Copyright © Ventilux (2024) - Todos os Direitos Reservados
                </div>
            </div>
        </footer>


</body>
</html>
