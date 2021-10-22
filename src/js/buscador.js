document.addEventListener('DOMContentLoaded', iniciarApp );

function iniciarApp(){
    buscarPorFecha();
}

function buscarPorFecha(){
    const fecha = document.querySelector('#fecha');

    fecha.addEventListener('input', function(e){
        const fechaSelect = e.target.value;
        console.log(fechaSelect);
        window.location = `?fecha=${fechaSelect}`
    })
}