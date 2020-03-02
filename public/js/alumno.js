$(document).ready(function(){
    cargarTabla();

    //Doesn't work with Ajax html call.
    $('.eliminar').click(function(e){
        e.preventDefault();
        alert('funcionaaaaa');
    });

    $('.agregar').click(function(e){
        e.preventDefault();
        $('#alumnoform').submit();

        swal({
            type: 'success',
            title: 'Agregado Exitosamente'
        }).then((result) => {
            window.location.href = '../../alumno';
        });
    });

    $('.modificar').click(function(e){
        e.preventDefault();

        $('#alumnoform').submit();

        swal({
            type: 'success',
            title: 'Modificado Exitosamente'
        }).then((result) => {
            window.location.href = '../../alumno';
        });

        //Los '/' botan la aplicación.

        /*var run = document.getElementsByName('run')[0].value;
        var nombre = document.getElementsByName('nombre')[0].value;
        var apellido = document.getElementsByName('apellido')[0].value;
        parametros = {};

        $.post("../../alumno/modificar/"+run+"/"+nombre+"/"+apellido, parametros, function(data){
            if(data['data'] == 1)
            {
                swal({
                    type: 'success',
                    title: 'Modificado Exitosamente'
                }).then((result) => {
                    window.location.href = '../../alumno';
                });
            }
            else
            {
                swal({
                    type: 'error',
                    html: 'Ocurrió un error: '+data['data'],
                    title: 'Error'
                });
            }
        });*/
    });
});

function cargarTabla(){
    $('#listaAlumnos').html('<div class="text-center"><img src="img/loading.gif"></div>');
    var parametros = {};
    /*$.get("alumno/cargar", parametros, function(data){
        $('#listaAlumnos').html(data);
    });*/

        $.ajax({
            type: 'GET',
            url: 'alumno/cargar',
            dataType: 'json',
            async: true,
            success:
            function(data, status){
                $('#listaAlumnos').html('');

                if(data.length > 0)
                {
                    for(i = 0; i < data.length; i++)
                    { 
                        alumno = data[i]; 
                        var e = $('<tr><td id = "run"></td><td id = "nombre"></td><td id = "apellido"></td><td class = "acciones"></td></tr>'); 
                        $('#run', e).html(alumno['run']); 
                        $('#nombre', e).html(alumno['nombre']); 
                        $('#apellido', e).html(alumno['apellido']);
                        $('.acciones', e).html( '<a class="btn btn-warning" href="alumno/edit/'+alumno['run']+'"><span class="fa fa-pencil"></span> Modificar</a>'+
                                                '<button class="eliminar btn btn-danger" onClick=eliminar("'+alumno['run']+'")><span class="fa fa-trash"></span> Eliminar</button>'); 
                        $('#listaAlumnos').append(e);
                    }
                }
                else
                {
                    $('#listaAlumnos').html('Sin resultados'); 
                }
            },
            error:
            function(data){
                $('#listaAlumnos').html('Sin resultados'); 
            }
        });
}

function eliminar(run){
        swal({
            type: 'warning',
            text: '¿Desea eliminar este alumno?',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Sí',
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: false}).then(
            function()
            {
                parametros = {};
                $.post("alumno/eliminar/"+run, parametros, function(data){
                    if(data['data'] == 1)
                    {
                        swal({
                            type: 'success',
                            title: 'Eliminado Exitosamente'
                        }).then((result) => {
                            cargarTabla();
                        });
                    }
                    else
                    {
                        swal({
                            type: 'error',
                            html: 'Ocurrió un error',
                            title: 'Error'
                        });
                    }
                });
            },
            function(){}
        );
}