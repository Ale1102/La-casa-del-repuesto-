/**
 * Funcionalidades del carrito de compras para La Casa del Repuesto
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar carrito
    initializeCart();
    
    // Botones de agregar al carrito
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            const productPrice = parseFloat(this.dataset.productPrice);
            const productImage = this.dataset.productImage;
            
            // Obtener cantidad (si existe un input de cantidad)
            let quantity = 1;
            const quantityInput = document.querySelector('.quantity input');
            if (quantityInput) {
                quantity = parseInt(quantityInput.value);
            }
            
            addToCart(productId, productName, productPrice, quantity, productImage);
            showAlert(`${productName} ha sido agregado al carrito`, 'success');
        });
    });
    
    // Botones de actualizar cantidad en el carrito
    const updateQuantityButtons = document.querySelectorAll('.update-quantity-btn');
    updateQuantityButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const action = this.dataset.action;
            
            updateCartItemQuantity(productId, action);
        });
    });
    
    // Botones de eliminar del carrito
    const removeFromCartButtons = document.querySelectorAll('.remove-from-cart-btn');
    removeFromCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            removeFromCart(productId);
        });
    });
    
    // Botón de vaciar carrito
    const clearCartButton = document.querySelector('.clear-cart-btn');
    if (clearCartButton) {
        clearCartButton.addEventListener('click', function() {
            clearCart();
        });
    }
    
    // Formulario de checkout
    const checkoutForm = document.querySelector('#checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (validateForm(this)) {
                processCheckout();
            }
        });
    }
});

/**
 * Inicializa el carrito de compras
 */
function initializeCart() {
    // Verificar si ya existe un carrito en localStorage
    if (!localStorage.getItem('cart')) {
        // Inicializar un carrito vacío
        localStorage.setItem('cart', JSON.stringify([]));
    }
    
    // Actualizar contador de items en el carrito
    updateCartCounter();
}

/**
 * Agrega un producto al carrito
 * 
 * @param {string} id - ID del producto
 * @param {string} name - Nombre del producto
 * @param {number} price - Precio del producto
 * @param {number} quantity - Cantidad a agregar
 * @param {string} image - URL de la imagen del producto
 */
function addToCart(id, name, price, quantity, image) {
    // Obtener el carrito actual
    const cart = JSON.parse(localStorage.getItem('cart'));
    
    // Verificar si el producto ya está en el carrito
    const existingItemIndex = cart.findIndex(item => item.id === id);
    
    if (existingItemIndex !== -1) {
        // Actualizar cantidad si el producto ya está en el carrito
        cart[existingItemIndex].quantity += quantity;
    } else {
        // Agregar nuevo producto al carrito
        cart.push({
            id: id,
            name: name,
            price: price,
            quantity: quantity,
            image: image
        });
    }
    
    // Guardar el carrito actualizado
    localStorage.setItem('cart', JSON.stringify(cart));
    
    // Actualizar contador de items en el carrito
    updateCartCounter();
}

/**
 * Actualiza la cantidad de un producto en el carrito
 * 
 * @param {string} id - ID del producto
 * @param {string} action - Acción a realizar (increase o decrease)
 */
function updateCartItemQuantity(id, action) {
    // Obtener el carrito actual
    const cart = JSON.parse(localStorage.getItem('cart'));
    
    // Encontrar el producto en el carrito
    const itemIndex = cart.findIndex(item => item.id === id);
    
    if (itemIndex !== -1) {
        if (action === 'increase') {
            // Aumentar cantidad
            cart[itemIndex].quantity += 1;
        } else if (action === 'decrease') {
            // Disminuir cantidad
            if (cart[itemIndex].quantity > 1) {
                cart[itemIndex].quantity -= 1;
            } else {
                // Eliminar producto si la cantidad llega a 0
                cart.splice(itemIndex, 1);
            }
        }
        
        // Guardar el carrito actualizado
        localStorage.setItem('cart', JSON.stringify(cart));
        
        // Actualizar la interfaz del carrito
        updateCartUI();
    }
}

/**
 * Elimina un producto del carrito
 * 
 * @param {string} id - ID del producto a eliminar
 */
function removeFromCart(id) {
    // Obtener el carrito actual
    const cart = JSON.parse(localStorage.getItem('cart'));
    
    // Filtrar el producto a eliminar
    const updatedCart = cart.filter(item => item.id !== id);
    
    // Guardar el carrito actualizado
    localStorage.setItem('cart', JSON.stringify(updatedCart));
    
    // Actualizar la interfaz del carrito
    updateCartUI();
    
    showAlert('Producto eliminado del carrito', 'info');
}

/**
 * Vacía completamente el carrito
 */
function clearCart() {
    // Guardar un carrito vacío
    localStorage.setItem('cart', JSON.stringify([]));
    
    // Actualizar la interfaz del carrito
    updateCartUI();
    
    showAlert('El carrito ha sido vaciado', 'info');
}

/**
 * Actualiza el contador de items en el carrito
 */
function updateCartCounter() {
    const cart = JSON.parse(localStorage.getItem('cart'));
    const cartCounter = document.querySelector('.cart-counter');
    
    if (cartCounter) {
        // Calcular el total de items en el carrito
        const itemCount = cart.reduce((total, item) => total + item.quantity, 0);
        
        // Actualizar el contador
        cartCounter.textContent = itemCount;
        
        // Mostrar u ocultar el contador según si hay items
        if (itemCount > 0) {
            cartCounter.classList.add('active');
        } else {
            cartCounter.classList.remove('active');
        }
    }
}

/**
 * Actualiza la interfaz del carrito
 */
function updateCartUI() {
    // Actualizar contador
    updateCartCounter();
    
    // Actualizar tabla del carrito si estamos en la página del carrito
    const cartTable = document.querySelector('.cart-table tbody');
    if (cartTable) {
        const cart = JSON.parse(localStorage.getItem('cart'));
        
        // Limpiar tabla
        cartTable.innerHTML = '';
        
        if (cart.length === 0) {
            // Mostrar mensaje de carrito vacío
            cartTable.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center">El carrito está vacío</td>
                </tr>
            `;
            
            // Deshabilitar botón de checkout
            const checkoutButton = document.querySelector('.checkout-btn');
            if (checkoutButton) {
                checkoutButton.disabled = true;
            }
        } else {
            // Llenar tabla con productos
            cart.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td data-label="Producto">
                        <div class="cart-product">
                            <img src="${item.image}" alt="${item.name}">
                            <div>
                                <h4>${item.name}</h4>
                            </div>
                        </div>
                    </td>
                    <td data-label="Precio">$${item.price.toFixed(2)}</td>
                    <td data-label="Cantidad">
                        <div class="quantity">
                            <button type="button" class="decrease update-quantity-btn" data-product-id="${item.id}" data-action="decrease">-</button>
                            <input type="text" value="${item.quantity}" readonly>
                            <button type="button" class="increase update-quantity-btn" data-product-id="${item.id}" data-action="increase">+</button>
                        </div>
                    </td>
                    <td data-label="Subtotal">$${(item.price * item.quantity).toFixed(2)}</td>
                    <td data-label="Acciones">
                        <button type="button" class="remove-from-cart-btn" data-product-id="${item.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                
                cartTable.appendChild(row);
            });
            
            // Habilitar botón de checkout
            const checkoutButton = document.querySelector('.checkout-btn');
            if (checkoutButton) {
                checkoutButton.disabled = false;
            }
            
            // Agregar eventos a los nuevos botones
            const updateButtons = cartTable.querySelectorAll('.update-quantity-btn');
            updateButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.dataset.productId;
                    const action = this.dataset.action;
                    
                    updateCartItemQuantity(productId, action);
                });
            });
            
            const removeButtons = cartTable.querySelectorAll('.remove-from-cart-btn');
            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.dataset.productId;
                    removeFromCart(productId);
                });
            });
        }
        
        // Actualizar resumen del carrito
        updateCartSummary();
    }
    
    // Actualizar la sección de resumen del pedido en la página de checkout
    updateOrderSummary();
}

/**
 * Actualiza el resumen del carrito
 */
function updateCartSummary() {
    const summarySubtotal = document.querySelector('.summary-subtotal');
    const summaryTotal = document.querySelector('.summary-total');
    
    if (summarySubtotal && summaryTotal) {
        const cart = JSON.parse(localStorage.getItem('cart'));
        
        // Calcular subtotal
        const subtotal = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
        
        // Mostrar subtotal
        summarySubtotal.textContent = `$${subtotal.toFixed(2)}`;
        
        // Calcular total (aquí podrías agregar impuestos, envío, etc.)
        const total = subtotal;
        
        // Mostrar total
        summaryTotal.textContent = `$${total.toFixed(2)}`;
    }
}

/**
 * Actualiza el resumen del pedido en la página de checkout
 */
function updateOrderSummary() {
    const orderItems = document.querySelector('.order-items');
    
    if (orderItems) {
        const cart = JSON.parse(localStorage.getItem('cart'));
        
        // Limpiar contenido
        orderItems.innerHTML = '';
        
        if (cart.length === 0) {
            // Mostrar mensaje de carrito vacío
            orderItems.innerHTML = '<p>No hay productos en el carrito</p>';
        } else {
            // Llenar con productos
            cart.forEach(item => {
                const orderItem = document.createElement('div');
                orderItem.className = 'order-item';
                orderItem.innerHTML = `
                    <div class="order-item-details">
                        <span>${item.name} x ${item.quantity}</span>
                        <span>$${(item.price * item.quantity).toFixed(2)}</span>
                    </div>
                `;
                
                orderItems.appendChild(orderItem);
            });
        }
        
        // Actualizar subtotal y total
        const summarySubtotal = document.querySelector('.order-summary .summary-subtotal');
        const summaryTotal = document.querySelector('.order-summary .summary-total');
        
        if (summarySubtotal && summaryTotal) {
            // Calcular subtotal
            const subtotal = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
            
            // Mostrar subtotal
            summarySubtotal.textContent = `$${subtotal.toFixed(2)}`;
            
            // Calcular total
            const total = subtotal;
            
            // Mostrar total
            summaryTotal.textContent = `$${total.toFixed(2)}`;
        }
    }
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
    
    // Eliminar mensajes de error anteriores
    const errorMessages = form.querySelectorAll('.error-message');
    errorMessages.forEach(el => el.remove());
    
    inputs.forEach(input => {
        input.classList.remove('error');
        
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('error');
            
            const errorMessage = input.dataset.errorMessage || 'Este campo es obligatorio';
            const errorElement = document.createElement('div');
            errorElement.className = 'error-message';
            errorElement.textContent = errorMessage;
            input.parentElement.appendChild(errorElement);
        } else {
            // Validaciones específicas
            if (input.type === 'email' && !validateEmail(input.value)) {
                isValid = false;
                input.classList.add('error');
                
                const errorElement = document.createElement('div');
                errorElement.className = 'error-message';
                errorElement.textContent = 'Correo electrónico inválido';
                input.parentElement.appendChild(errorElement);
            }
            
            if (input.id === 'numero_tarjeta' && !validateCreditCard(input.value)) {
                isValid = false;
                input.classList.add('error');
                
                const errorElement = document.createElement('div');
                errorElement.className = 'error-message';
                errorElement.textContent = 'Número de tarjeta inválido';
                input.parentElement.appendChild(errorElement);
            }
            
            if (input.id === 'cvv' && !validateCVV(input.value)) {
                isValid = false;
                input.classList.add('error');
                
                const errorElement = document.createElement('div');
                errorElement.className = 'error-message';
                errorElement.textContent = 'CVV inválido';
                input.parentElement.appendChild(errorElement);
            }
            
            if (input.id === 'fecha_expiracion' && !validateExpiryDate(input.value)) {
                isValid = false;
                input.classList.add('error');
                
                const errorElement = document.createElement('div');
                errorElement.className = 'error-message';
                errorElement.textContent = 'Fecha de expiración inválida (MM/AA)';
                input.parentElement.appendChild(errorElement);
            }
            
            if (input.id === 'telefono' && !validatePhone(input.value)) {
                isValid = false;
                input.classList.add('error');
                
                const errorElement = document.createElement('div');
                errorElement.className = 'error-message';
                errorElement.textContent = 'Número de teléfono inválido';
                input.parentElement.appendChild(errorElement);
            }
            
            if (input.id === 'codigo_postal' && !validateZipCode(input.value)) {
                isValid = false;
                input.classList.add('error');
                
                const errorElement = document.createElement('div');
                errorElement.className = 'error-message';
                errorElement.textContent = 'Código postal inválido';
                input.parentElement.appendChild(errorElement);
            }
        }
    });
    
    return isValid;
}

/**
 * Valida un correo electrónico
 * 
 * @param {string} email - Correo electrónico a validar
 * @returns {boolean} - True si es válido, false en caso contrario
 */
function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

/**
 * Valida un número de tarjeta de crédito
 * 
 * @param {string} cardNumber - Número de tarjeta a validar
 * @returns {boolean} - True si es válido, false en caso contrario
 */
function validateCreditCard(cardNumber) {
    // Eliminar espacios y guiones
    cardNumber = cardNumber.replace(/\s+|-/g, '');
    
    // Verificar que solo contenga números y tenga entre 13 y 19 dígitos
    if (!/^\d{13,19}$/.test(cardNumber)) {
        return false;
    }
    
    // Algoritmo de Luhn para validar números de tarjeta
    let sum = 0;
    let shouldDouble = false;
    
    // Recorrer de derecha a izquierda
    for (let i = cardNumber.length - 1; i >= 0; i--) {
        let digit = parseInt(cardNumber.charAt(i));
        
        if (shouldDouble) {
            digit *= 2;
            if (digit > 9) {
                digit -= 9;
            }
        }
        
        sum += digit;
        shouldDouble = !shouldDouble;
    }
    
    return (sum % 10) === 0;
}

/**
 * Valida un código CVV
 * 
 * @param {string} cvv - Código CVV a validar
 * @returns {boolean} - True si es válido, false en caso contrario
 */
function validateCVV(cvv) {
    // El CVV debe ser un número de 3 o 4 dígitos
    return /^\d{3,4}$/.test(cvv);
}

/**
 * Valida una fecha de expiración de tarjeta
 * 
 * @param {string} expiryDate - Fecha de expiración en formato MM/AA o MM/AAAA
 * @returns {boolean} - True si es válida, false en caso contrario
 */
function validateExpiryDate(expiryDate) {
    // Verificar formato MM/AA o MM/AAAA
    if (!/^\d{2}\/\d{2}(\d{2})?$/.test(expiryDate)) {
        return false;
    }
    
    const parts = expiryDate.split('/');
    let month = parseInt(parts[0], 10);
    let year = parseInt(parts[1], 10);
    
    // Validar rango del mes
    if (month < 1 || month > 12) {
        return false;
    }
    
    // Si el año tiene 2 dígitos, convertir a 4 dígitos
    if (year < 100) {
        year += 2000;
    }
    
    // Obtener la fecha actual
    const now = new Date();
    const currentYear = now.getFullYear();
    const currentMonth = now.getMonth() + 1; // getMonth() devuelve 0-11
    
    // Verificar que la fecha no haya expirado
    if (year < currentYear || (year === currentYear && month < currentMonth)) {
        return false;
    }
    
    return true;
}

/**
 * Valida un número de teléfono
 * 
 * @param {string} phone - Número de teléfono a validar
 * @returns {boolean} - True si es válido, false en caso contrario
 */
function validatePhone(phone) {
    // Eliminar espacios, guiones y paréntesis
    phone = phone.replace(/\s+|-|$$|$$/g, '');
    
    // Validar que solo contenga números y tenga entre 8 y 15 dígitos
    return /^\d{8,15}$/.test(phone);
}

/**
 * Valida un código postal
 * 
 * @param {string} zipCode - Código postal a validar
 * @returns {boolean} - True si es válido, false en caso contrario
 */
function validateZipCode(zipCode) {
    // Para El Salvador, el código postal tiene 4 dígitos
    return /^\d{4}$/.test(zipCode);
}

/**
 * Procesa el checkout
 */
function processCheckout() {
    // Obtener los datos del formulario
    const form = document.getElementById('checkout-form');
    const formData = new FormData(form);
    
    // Obtener el carrito
    const cart = JSON.parse(localStorage.getItem('cart'));
    
    // Crear objeto con los datos del pedido
    const orderData = {
        customer: {
            nombre: formData.get('nombre'),
            apellido: formData.get('apellido'),
            email: formData.get('email'),
            telefono: formData.get('telefono'),
            direccion: formData.get('direccion'),
            ciudad: formData.get('ciudad'),
            codigo_postal: formData.get('codigo_postal')
        },
        payment: {
            metodo_pago: formData.get('metodo_pago'),
            numero_tarjeta: formData.get('numero_tarjeta') || '',
            fecha_expiracion: formData.get('fecha_expiracion') || '',
            cvv: formData.get('cvv') || ''
        },
        items: cart,
        subtotal: cart.reduce((total, item) => total + (item.price * item.quantity), 0),
        total: cart.reduce((total, item) => total + (item.price * item.quantity), 0),
        notas: formData.get('notas') || '',
        fecha: new Date().toISOString()
    };
    
    // Aquí se implementaría la lógica para enviar los datos al servidor
    // Por ejemplo, mediante una petición AJAX
    
    // Simulación de procesamiento
    showAlert('Procesando su pedido...', 'info');
    
    // Simular una petición al servidor
    setTimeout(() => {
        // Guardar el pedido en localStorage para referencia (en una aplicación real, esto se haría en el servidor)
        const orders = JSON.parse(localStorage.getItem('orders') || '[]');
        orderData.id = Date.now().toString();
        orderData.estado = 'pendiente';
        orders.push(orderData);
        localStorage.setItem('orders', JSON.stringify(orders));
        
        // Vaciar carrito después de procesar el pedido
        clearCart();
        
        // Mostrar mensaje de éxito
        showAlert('¡Pedido realizado con éxito!', 'success');
        
        // Redirigir a página de confirmación
        setTimeout(() => {
            window.location.href = 'confirmacion.php';
        }, 1500);
    }, 2000);
}