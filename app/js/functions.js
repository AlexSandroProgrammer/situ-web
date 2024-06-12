function navigateTo (destination) {
    let url;
    switch (destination) {
        case 'consultar':
            url = 'consultar.php'; // Reemplaza 'url_de_tu_pagina_usuario_invitado' con la URL correspondiente
            break;
        case 'volver-al-blog':
            url = 'https://senaempresalagranja.blogspot.com/'; // Reemplaza 'url_de_tu_pagina_blog' con la URL correspondiente
            break;
        default:
            url = '#'; // URL por defecto, puedes cambiarlo según necesites
            break;
    }
    window.location.href = url;
}


function multiplenumber(e) {
    key = e.keyCode || e.which;

    teclado = String.fromCharCode(key).toLowerCase();

    numeros = "0123456789";

    especiales = "8-37-38-46-164-46";

    teclado_especial = false;

    for (var i in especiales) {
        if (key == especiales[i]) {
            teclado_especial = true;
            alert("Debe ingresar solo numeros y debes tener en cuenta la cantidad de numeros requeridos");
            break;

        }
    }

    if (numeros.indexOf(teclado) == -1 && !teclado_especial) {
        alert("Debe ingresar solo numeros y debes tener en cuenta la cantidad de numeros requeridos");
        return false;
    }
}

function maxlengthNumber(obj) {
    if (obj.value.length > obj.maxLength) {
        obj.value = obj.value.slice(0, obj.maxLength);
        alert("Debe ingresar solo numeros y debes tener en cuenta la cantidad de numeros requeridos");
    }
}