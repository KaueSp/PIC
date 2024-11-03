// Função para mudar a imagem do carousel
let currentIndex = 0;

function moveCarousel(step) {
    const carousel = document.querySelector('.carousel');
    const items = document.querySelectorAll('.carousel-item');
    const totalItems = items.length;

    currentIndex = (currentIndex + step + totalItems) % totalItems;
    carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
}


// Função para rolagem da navbar
window.addEventListener('scroll', function() {
    const fixedNavbar = document.querySelector('.fixed-navbar');
    if (window.scrollY > 100) {
        fixedNavbar.style.top = '0';
    } else {
        fixedNavbar.style.top = '-100px';
    }
});




//CATALOGO 

document.addEventListener('DOMContentLoaded', function() {
    // Selecionar todos os modais
    const modals = document.querySelectorAll('.modal');

    // Função para abrir um modal
    function openModal(modal) {
        if (modal) {
            modal.style.display = 'flex';
        }
    }

    // Função para fechar um modal
    function closeModal(modal) {
        if (modal) {
            modal.style.display = 'none';
        }
    }

    // Selecionar botões que abrem os modais usando data-modal-target
    const openModalButtons = document.querySelectorAll('[data-modal-target]');

    // Selecionar botões de fechar dentro dos modais
    const closeModalButtons = document.querySelectorAll('.modal .close');

    // Adicionar eventos aos botões que abrem os modais
    openModalButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modalSelector = button.getAttribute('data-modal-target');
            const modal = document.querySelector(modalSelector);
            if (modal) {
                openModal(modal);
            }
        });
    });

    // Adicionar eventos aos botões de fechar (X)
    closeModalButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modal = button.closest('.modal');
            if (modal) {
                closeModal(modal);
            }
        });
    });

    // Fechar modais ao clicar fora do conteúdo do modal
    modals.forEach(modal => {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal(modal);
            }
        });
    });

    // Fechar modais ao pressionar a tecla "Esc"
    window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            modals.forEach(modal => {
                if (modal.style.display === 'flex') {
                    closeModal(modal);
                }
            });
        }
    });

    // Alternar visibilidade da senha
    const passwordField = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    if (togglePassword && passwordField) {
        togglePassword.addEventListener('click', function() {
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                togglePassword.innerHTML = '<i class="fas fa-eye"></i>';
            } else {
                passwordField.type = 'password';
                togglePassword.innerHTML = '<i class="fas fa-eye-slash"></i>';
            }
        });
    }


    
// Filtragem de produtos por nome
const searchInput = document.getElementById('search');
const categoryFilters = document.querySelectorAll('.categories input[type="checkbox"]');

function filterProducts() {
    const filterText = searchInput.value.toLowerCase();
    const selectedCategories = Array.from(categoryFilters)
        .filter(checkbox => checkbox.checked)
        .map(checkbox => checkbox.id);

    const productList = document.querySelectorAll('.product-card');
    productList.forEach(card => {
        const productName = card.querySelector('.product-name').textContent.toLowerCase();
        const productCategories = JSON.parse(card.getAttribute('data-categories'));

        const matchesText = productName.includes(filterText);
        const matchesCategory = selectedCategories.length === 0 || selectedCategories.some(cat => productCategories.includes(cat));

        if (matchesText && matchesCategory) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

if (searchInput) {
    searchInput.addEventListener('input', filterProducts);
}

categoryFilters.forEach(checkbox => {
    checkbox.addEventListener('change', filterProducts);
});

// Manipulação dos Botões de Editar e Remover
const editButtons = document.querySelectorAll('.edit-product');
const deleteButtons = document.querySelectorAll('.delete-product');
const editProductModal = document.getElementById('editProductModal');
const deleteProductModal = document.getElementById('deleteProductModal');

editButtons.forEach(button => {
    button.addEventListener('click', () => {
        const productId = button.getAttribute('data-id');
        // Fazer uma requisição AJAX para obter os detalhes do produto
        fetch(`get_product.php?id=${productId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Preencher o formulário de edição com os dados do produto
                    document.getElementById('edit_product_id').value = data.product.id;
                    document.getElementById('edit_name').value = data.product.name;
                    document.getElementById('edit_description').value = data.product.description;
                    document.getElementById('edit_image_url').value = data.product.image_url;

                    // Limpar categorias selecionadas e selecionar as categorias do produto
                    const editCategorySelect = document.getElementById('edit_category');
                    Array.from(editCategorySelect.options).forEach(option => {
                        option.selected = data.product.category.includes(option.value);
                    });

                    // Abrir o modal de edição
                    openModal(editProductModal);
                } else {
                    alert('Erro ao obter os detalhes do produto.');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
            });
    });
});

deleteButtons.forEach(button => {
    button.addEventListener('click', () => {
        const productId = button.getAttribute('data-id');
        document.getElementById('delete_product_id').value = productId;
        openModal(deleteProductModal);
    });
});

// Adicionar eventos aos botões "Cancelar"
const cancelButtons = document.querySelectorAll('.cancel-button');
cancelButtons.forEach(button => {
    button.addEventListener('click', () => {
        const modal = button.closest('.modal');
        if (modal) {
            closeModal(modal);
        }
    });
});

// Funções para abrir e fechar modais
function openModal(modal) {
    modal.style.display = 'block';
}

function closeModal(modal) {
    modal.style.display = 'none';
}

// Fechar modal ao clicar fora dele
window.addEventListener('click', (event) => {
    if (event.target.classList.contains('modal')) {
        closeModal(event.target);
    }
});

});

document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.filter-category');
    const productCards = document.querySelectorAll('.product-card');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', filterProducts);
    });

    function filterProducts() {
        const selectedCategories = Array.from(checkboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        productCards.forEach(card => {
            const productCategories = JSON.parse(card.getAttribute('data-categories'));
            const show = selectedCategories.length === 0 || selectedCategories.some(category => productCategories.includes(category));
            card.style.display = show ? 'block' : 'none';
        });
    }

    // Atalho de teclado para abrir o modal de login
    document.addEventListener('keydown', function(event) {
        if (event.ctrlKey && event.shiftKey && event.code === 'KeyF') {
            event.preventDefault();
            document.getElementById('loginModal').style.display = 'block'; // Mostrar o modal de login
        }
    });

});