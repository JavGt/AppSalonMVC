let paso = 1;
let pasoInicial = 1;
let pasoFinal = 3;

const cita = {
    id :'',
    nombre : '',
    fecha : '',
    hora :'',
    servicios : []
}

document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
});

function iniciarApp() {
    mostrarSeccion();
    // cambia la seccion cuando se presione los tabs
    tabs();
    botonesPaginador(); //agrega o quita los botones del paginador
    paginaSiguiente();
    paginaAnterior();

    consultarApi(); // consulta la api en el backend de PHP

    idCliente(); //añade el nombre de la cita en el objeto
    nombreCliente(); //añade el nombre de la cita en el objeto
    seleccionarFecha(); //añade fecha de la cita en el objeto
    seleccionarHora(); //añade Hora de la cita en el objeto

    mostrarResumen();
}

function mostrarSeccion() {
    // ocultar la seccion que tenga la clase de mostrar
    const seccionAnterior = document.querySelector('.mostrar');

    if(seccionAnterior){
        seccionAnterior.classList.remove('mostrar');
    }
    
    // seleccionar la seccion con el paso
    const pasosSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasosSelector)
    
    seccion.classList.add('mostrar');

    // Quita la clase de actual al tab anterior
    const tabAnterior = document.querySelector('.actual');

    if(tabAnterior){
        tabAnterior.classList.remove('actual');
    }

    // Resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');
}

function tabs(){
    const botones = document.querySelectorAll('.tabs button');
    
    botones.forEach( boton =>{
        boton.addEventListener('click', function(e){
            paso = parseInt( e.target.dataset.paso );
            mostrarSeccion();
            botonesPaginador();
        });
    } )
}

function botonesPaginador(){
    const paginaSiguiente = document.querySelector('#siguiente');
    const paginaAnterior = document.querySelector('#anterior');

    if(paso === pasoInicial){
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    } else if(paso === pasoFinal){
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');
        mostrarResumen();
    }else{
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }    
    mostrarSeccion();
}

function paginaSiguiente(){
    const paginaSiguiente = document.querySelector('#siguiente');

    paginaSiguiente.addEventListener('click', function(){
        if(paso >= pasoFinal ) return;

        paso ++;
        botonesPaginador();
    });
}

function paginaAnterior(){
    const paginaAnterior = document.querySelector('#anterior');

    paginaAnterior.addEventListener('click', function(){

        if(paso <= pasoInicial) return;

        paso --;
        botonesPaginador();
    });
}

async function consultarApi() {

    try {
        const url = 'http://localhost:3000/api/servicios';
        const resultado = await fetch(url);
        const  servicios = await resultado.json();
        mostrarServicios(servicios);

    } catch (error) {
        console.log(error);
    }
}


function mostrarServicios( servicios ){
    servicios.forEach(servicio =>{
        const {id, nombre, precio} = servicio;

        const nombreServicio= document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio= document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

        const servicioDiv= document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;

        servicioDiv.onclick = function() {
            seleccionarServicio(servicio);
        };

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        document.querySelector('#servicios').appendChild(servicioDiv);
        // console.log(servicioDiv);
        // console.log(precioServicio);
    });
}

function seleccionarServicio(servicio){
    const { id } = servicio;
    const { servicios } = cita;

    // identificar el elemento al que se le da click
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    // comprobar si mi servicio ya fue quitado
    if( servicios.some( agregado => agregado.id === id ) ){

        // agrgar al arreglo y darle diseño
        cita.servicios = servicios.filter( agregado => agregado.id !== id );
        divServicio.classList.remove('seleccionado');
    } else {
        // quitar al arreglo y darle diseño
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');
    }
}

function idCliente(){
    cita.id = document.querySelector('#id').value;
}

function nombreCliente() {
    cita.nombre = document.querySelector('#nombre').value;
}

function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha');

    inputFecha.addEventListener('input', function( e ) {

        const dia = new Date(e.target.value).getUTCDay();
        if( [6,0].includes(dia) ){
            e.target.value = '';
            mostrarAlerta('Fin de semana no permitido', 'error', '.formulario');
        } else {
            cita.fecha = e.target.value;
        }
    } );
}

function seleccionarHora() {
    const inputHora = document.querySelector('#hora');

    inputHora.addEventListener('input', function( e ) {
        const horaCita = e.target.value;
        const hora = horaCita.split(':')[0];
        
        if( hora < 10 || hora >17 ){
            mostrarAlerta('El horario de citas es de 10am a 7pm', 'error', '.formulario');
            e.target.value = '';
        } else {
            cita.hora = e.target.value;
        }
    });
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
    // Previene que se genere mas de una alerta
    const alertaPrevia = document.querySelector('.alerta');
    if(alertaPrevia){
        alertaPrevia.remove();
    }

    // Scripting para crear la a alerta
    const alerta = document.createElement('DIV');

    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    document.querySelector(elemento).appendChild(alerta);

    if(desaparece){
        // eliminar la alerta
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }
}

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');

    // Limpiar el contenidp de resumen
    while(resumen.firstChild){
        resumen.removeChild(resumen.firstChild);
    }

    if ( Object.values(cita).includes('') || cita.servicios.length === 0 ) {
        mostrarAlerta('Faltan datos de servicios, fechas u horas', 'error', '.contenido-resumen', false);
        return;
    }

    // formatear el div de resumen
    const {nombre, fecha, hora, servicios} = cita;

    const headingServicios = document.createElement('H2')
    headingServicios.textContent = 'Resumen de Servicios';

    resumen.appendChild(headingServicios);
    
    servicios.forEach( servicio => {
        const { id, precio, nombre} = servicio;

        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio: </span>$${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    })

    const headingCita = document.createElement('H2')
    headingCita.textContent = 'Resumen de la Cita';

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML=`<span>Nombre:</span>${nombre}`;

    // Formatear la fecha en español
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 2;
    const year = fechaObj.getFullYear();

    const fechaUTC = new Date( Date.UTC(year, mes, dia) );
    
    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }
    const fechaFormateada = fechaUTC.toLocaleDateString('es-MX', opciones);

    const fechaCita = document.createElement('P');
    fechaCita.innerHTML=`<span>Fecha: </span>${fechaFormateada}`;

    const horaCita = document.createElement('P');
    horaCita.innerHTML=`<span>Hora: </span>${hora}`;

    // Boton para enviar cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar cita';
    botonReservar.onclick = reservarCita;

    resumen.appendChild(headingCita);
    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);
    
    resumen.appendChild(botonReservar);
}

async function reservarCita(){

    const { id, fecha, hora, servicios } = cita;

    const idServicio = servicios.map( servicio => servicio.id );
    // console.log(idServicio);
    // return

    const datos = new FormData();

    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('usuarioId', id);

    datos.append('servicios', idServicio);

    // console.log([...datos]);

    // return

    try { 
        // peticion hacia la api
        const url = 'http://localhost:3000/api/citas';

        const respuesta = await fetch(url, {
            method: 'POST',
            body : datos
        });

        const resultado = await respuesta.json();

        console.log(resultado.resultado);

        if(resultado.resultado){
            Swal.fire({
                icon: 'success',
                title: 'Cita creada',
                text: 'Tu cita fue creada correctamente',
                button: 'ok'
            }).then( () =>{

                setTimeout(() => {
                    window.location.reload();                    
                }, 2000);
            })          
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al guardar la cita',
          })
    }    
}