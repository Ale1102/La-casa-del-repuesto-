/**
 * Archivo JavaScript principal para La Casa del Repuesto
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar componentes
    initializeSlider();
    initializeProductQuantity();
    initializeMobileMenu();
    
    // Mostrar mensaje de bienvenida en la consola
    console.log('Bienvenido a La Casa del Repuesto - Plataforma de Comercio Electrónico');
});

/**
 * Inicializa el slider de imágenes en la página principal
 */
function initializeSlider() {
    const slider = document.querySelector('.slider');
    if (!slider) return;
    
    const slides = slider.querySelectorAll('.slide');
    const prevBtn = slider.querySelector('.prev-slide');
    const nextBtn = slider.querySelector('.next-slide');
    let currentSlide = 0;
    
    // Ocultar todos los slides excepto el primero
    for (let i = 1; i < slides.length; i++) {
        slides[i].style.display = 'none';
    }
    
    // Función para mostrar un slide específico
    function showSlide(index) {
        // Ocultar todos los slides
        for (let i = 0; i < slides.length; i++) {
            slides[i].style.display = 'none';
        }
        
        // Mostrar el slide actual
        slides[index].style.display = 'block';
    }
    
    // Evento para el botón anterior
    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            currentSlide--;
            if (currentSlide < 0) {
                currentSlide = slides.length - 1;
            }
            showSlide(currentSlide);
        });
    }
    
    // Evento para el botón siguiente
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            currentSlide++;
            if (currentSlide >= slides.length) {
                currentSlide = 0;
            }
            showSlide(currentSlide);
        });
    }
    
    // Cambiar slide automáticamente cada 5 segundos
    setInterval(function() {
        currentSlide++;
        if (currentSlide >= slides.length) {
            currentSlide = 0;
        }
        showSlide(currentSlide);
    }, 5000);
}

/**
 * Inicializa los controles de cantidad de producto
 */
function initializeProductQuantity() {
    const quantityContainers = document.querySelectorAll('.quantity');
    
    quantityContainers.forEach(container => {
        const decreaseBtn = container.querySelector('.decrease');
        const increaseBtn = container.querySelector('.increase');
        const input = container.querySelector('input');
        
        if (decreaseBtn && increaseBtn && input) {
            decreaseBtn.addEventListener('click', function() {
                let value = parseInt(input.value);
                if (value > 1) {
                    input.value = value - 1;
                }
            });
            
            increaseBtn.addEventListener('click', function() {
                let value = parseInt(input.value);
                input.value = value + 1;
            });
            
            input.addEventListener('change', function() {
                let value = parseInt(input.value);
                if (isNaN(value) || value < 1) {
                    input.value = 1;
                }
            });
        }
    });
}

/**
 * Inicializa el menú móvil
 */
function initializeMobileMenu() {
    const menuToggle = document.querySelector('.menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('active');
            menuToggle.classList.toggle('active');
        });
    }
}

/**
 * Muestra un mensaje de alerta personalizado
 * 
 * @param {string} message - El mensaje a mostrar
 * @param {string} type - El tipo de alerta (success, error, warning, info)
 */
function showAlert(message, type = 'info') {
    const alertContainer = document.createElement('div');
    alertContainer.className = `alert alert-${type}`;
    alertContainer.textContent = message;
    
    document.body.appendChild(alertContainer);
    
    // Mostrar la alerta con animación
    setTimeout(() => {
        alertContainer.classList.add('show');
    }, 10);
    
    // Ocultar la alerta después de 3 segundos
    setTimeout(() => {
        alertContainer.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(alertContainer);
        }, 300);
    }, 3000);
}

/**
 * Valida un formulario
 * 
 * @param {HTMLFormElement} form - El formulario a validar
 * @returns {boolean} - True si el formulario es válido, false en caso contrario
 */
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('error');
            
            const errorMessage = input.dataset.errorMessage || 'Este campo es obligatorio';
            let errorElement = input.parentElement.querySelector('.error-message');
            
            if (!errorElement) {
                errorElement = document.createElement('div');
                errorElement.className = 'error-message';
                input.parentElement.appendChild(errorElement);
            }
            
            errorElement.textContent = errorMessage;
        } else {
            input.classList.remove('error');
            const errorElement = input.parentElement.querySelector('.error-message');
            if (errorElement) {
                errorElement.remove();
            }
        }
    });
    
    return isValid;
}