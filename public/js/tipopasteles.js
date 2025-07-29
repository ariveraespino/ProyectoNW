document.addEventListener("DOMContentLoaded", function () {

    const tarjetasBorde = document.querySelectorAll('.tarjeta-borde');
    const tarjetas = document.querySelectorAll('.tarjeta');
    const textos = document.querySelectorAll('.tarjeta h3');

   
    if (tarjetasBorde.length > 0 && tarjetas.length > 0 && textos.length > 0) {
        tarjetasBorde.forEach((tarjetaBorde, index) => {
            const tarjeta = tarjetas[index];
            const texto = textos[index];

            if (tarjeta && texto) { 
                let isActive = false;

                texto.style.display = 'none'; 
                tarjetaBorde.addEventListener('click', () => {
                    if (isActive) {
                        tarjeta.style.height = '30px';
                        texto.style.fontSize = '2px';
                        tarjeta.style.opacity = '0';
                        texto.style.display = 'none';
                        tarjetaBorde.style.opacity = '1';
                    } else {
                        tarjeta.style.height = '300px';
                        tarjeta.style.zIndex = '150';
                        texto.style.fontSize = '16px';
                        tarjeta.style.opacity = '1';
                        texto.style.display = 'block';
                        tarjetaBorde.style.opacity = '1';
                    }
                    isActive = !isActive;
                });
            } else {
                console.warn(`WARNING: Missing 'tarjeta' or 'h3' for tarjeta-borde at index ${index}.`);
            }
        });
    } else {
        console.warn("WARNING: Card elements (.tarjeta-borde, .tarjeta, or .tarjeta h3) not found. Card functionality won't work.");
    }



    });