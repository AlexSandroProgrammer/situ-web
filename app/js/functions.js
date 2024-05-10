function navigateTo (destination) {
    let url;
    switch (destination) {
        case 'usuario-invitado':
            url = 'usuario-invitado.php'; // Reemplaza 'url_de_tu_pagina_usuario_invitado' con la URL correspondiente
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
