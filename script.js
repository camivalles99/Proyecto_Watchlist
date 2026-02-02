document.addEventListener('DOMContentLoaded', () => {
    
    // Elementos
    const selector = document.getElementById('selectorTipo');
    const camposSerie = document.getElementById('camposSerie');

    // Escuchar cambios en el selector
    selector.addEventListener('change', () => {
        if (selector.value === 'serie') {
            camposSerie.classList.remove('hidden'); // Mostrar
        } else {
            camposSerie.classList.add('hidden'); // Ocultar
        }
    });

    // Confirmación al borrar
    const botonesBorrar = document.querySelectorAll('.btn-trash, .btn-mini-trash');
    botonesBorrar.forEach(btn => {
        btn.addEventListener('click', (e) => {
            if(!confirm("¿Seguro que quieres eliminar esto del historial?")) {
                e.preventDefault();
            }
        });
    });
});