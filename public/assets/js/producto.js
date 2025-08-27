

// Función para cambiar la imagen principal al hacer clic en las miniaturas
function changeMainImage(src) {
    const mainImage = document.getElementById('mainImage');
    mainImage.src = src;
}

// Función para configurar el efecto de zoom dinámico
function setupZoomEffect() {
    const mainImage = document.getElementById('mainImage');
    const container = document.querySelector('.main-image-container');

    // Escucha el movimiento del mouse sobre el contenedor de la imagen principal
    container.addEventListener('mousemove', function(e) {
        const { left, top, width, height } = container.getBoundingClientRect();
        const mouseX = e.clientX - left;
        const mouseY = e.clientY - top;

        // Calcula el desplazamiento del zoom según la posición del cursor
        const offsetX = mouseX / width * 10 - 5; // Ajusta la velocidad del zoom aquí
        const offsetY = mouseY / height * 10 - 5; // Ajusta la velocidad del zoom aquí

        // Aplica transformación de escala y desplazamiento en función del cursor
        mainImage.style.transform = `scale(1.5) translate(${-offsetX}px, ${-offsetY}px)`;
    });

    // Restablece la imagen a su estado original cuando el cursor sale del contenedor
    container.addEventListener('mouseleave', function() {
        mainImage.style.transform = 'scale(1) translate(0, 0)';
    });
}

// Llama a la función para configurar el efecto de zoom dinámico
setupZoomEffect();
