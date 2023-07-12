$("#list_equipos_btn").click(function () {
    $.ajax({
        type: "POST",
        url: "models/funciones.php",
        data: {
            funcion: "listEquiposPage"
        },
        success: function (response) {
            const respuesta = JSON.parse(response);

            respuesta.forEach(resp => {
                $(location).attr("href", resp.href);
            });
        }
    });
});

$("#list_usuarios_btn").click(function () {
    $.ajax({
        type: "POST",
        url: "models/funciones.php",
        data: {
            funcion: "listUsuariosPage"
        },
        success: function (response) {
            const respuesta = JSON.parse(response);

            respuesta.forEach(resp => {
                $(location).attr("href", resp.href);
            });
        }
    });
});

$("#logout_btn").click(function () {
    $.ajax({
        type: "POST",
        url: "models/funciones.php",
        data: {
            funcion: "cerrarSesion"
        },
        success: function (response) {
            const respuesta = JSON.parse(response);

            respuesta.forEach(resp => {
                $(location).attr("href", resp.href);
            });
        }
    });
});

$("#create_equipo").click(function () {
    $.ajax({
        type: "POST",
        url: "models/funciones.php",
        data: {
            funcion: "enviarDatosEquipo",
            accion: "paginaCrear"
        },
        success: function (response) {
            const respuesta = JSON.parse(response);

            respuesta.forEach(resp => {
                $(location).attr('href', resp.href);
            });
        }
    });
});

$("#create_usuario").click(function () {
    $.ajax({
        type: "POST",
        url: "models/funciones.php",
        data: {
            funcion: "enviarDatosUsuario",
            accion: "paginaCrear"
        },
        success: function (response) {
            const respuesta = JSON.parse(response);

            respuesta.forEach(resp => {
                $(location).attr('href', resp.href);
            });
        }
    });
});

function listarEquipos() {
    const tabla = $("#list_equipo_table").DataTable({
        ajax: {
            type: "POST",
            url: "models/funciones.php",
            data: {
                funcion: "listarEquipos"
            }
        },
        columns: [
            { data: "serial" },
            { data: "seccional" },
            { data: "proc" },
            { data: "ram" },
            { data: "disco" },
            { data: "monitor" },
            { data: "teclado" },
            { data: "mouse" },
            { data: "nombreUsuario" },
            {
                "defaultContent": "<button class='edit_btn btn btn-primary btn-sm'>Edit</button>" +
                    "<button class='delete_btn btn btn-danger btn-sm'style='margin-left:0.2rem;'>Delete</button>"
            }
        ]
    });

    $("#table_main").show();

    dataEditEquipo("#list_equipo_table", tabla);
    dataDeleteEquipo("#list_equipo_table", tabla);

    mensajeEquipo();
}

function dataEditEquipo(tbody, tabla) {
    $(tbody).on("click", "button.edit_btn", function () {
        const data = tabla.row($(this).parents("tr")).data();
        editarEquipo(data);
    });
}

function dataDeleteEquipo(tbody, tabla) {
    $(tbody).on("click", "button.delete_btn", function () {
        const data = tabla.row($(this).parents("tr")).data();
        eliminarEquipo(data, tabla);
    });
}

function listarUsuarios() {
    const tabla = $("#list_usuario_table").DataTable({
        ajax: {
            type: "POST",
            url: "models/funciones.php",
            data: {
                funcion: "listarUsuarios"
            }
        },
        columns: [
            { data: "cedula" },
            { data: "nombre" },
            { data: "apellido" },
            { data: "depa" },
            { data: "muni" },
            {
                "defaultContent": "<button class='edit_btn btn btn-primary btn-sm'>Edit</button>"
            }
        ]
    });

    $("#table_main").show();

    dataEditUsuario("#list_usuario_table", tabla);

    mensajeUsuario();
}

function dataEditUsuario(tbody, tabla) {
    $(tbody).on("click", "button.edit_btn", function () {
        const data = tabla.row($(this).parents("tr")).data();
        editarUsuario(data);
    });
}

function eliminarEquipo(data, tabla) {
    $.ajax({
        type: "POST",
        url: "models/funciones.php",
        data: {
            funcion: "confirmDeleteEquipo",
            serial: data.serial
        },
        success: function (response) {
            const respuesta = JSON.parse(response);

            respuesta.forEach(resp => {
                Swal.fire({
                    title: resp.title,
                    icon: resp.icon,
                    showCancelButton: resp.showCancelButton,
                    confirmButtonColor: resp.confirmButtonColor,
                    cancelButtonColor: resp.cancelButtonColor,
                    cancelButtonText: resp.cancelButtonText,
                    confirmButtonText: resp.confirmButtonText
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "models/funciones.php",
                            data: {
                                funcion: "eliminarEquipo",
                                id_equipo: data.id_equipo
                            },
                            success: function (response) {
                                tabla.destroy();
                                listarEquipos();
                                mensajeEquipo();
                            }
                        });
                    }
                })
            });
        }
    });
}

function crearEquipo() {
    const datos = {
        funcion: "agregarEquipo",
        serial: $("#inputSerial").val(),
        id_usuario: $("#inputUsuario").val(),
        procesador: $("#inputProc").val(),
        ram: $("#inputRam").val(),
        disco: $("#inputDisco").val(),
        monitor: $("#inputMonitor").val(),
        teclado: $("#inputTeclado").val(),
        mouse: $("#inputMouse").val()
    };

    if (verificarDatosEquipo(datos)) {
        $(function () {
            $.ajax({
                type: "POST",
                url: "models/funciones.php",
                data: datos,
                success: function (response) {
                    const respuesta = JSON.parse(response);

                    respuesta.forEach(resp => {
                        if (resp.action) {
                            $(location).attr("href", resp.href);
                        } else {
                            Swal.fire({
                                icon: resp.icon,
                                title: resp.title,
                                showConfirmButton: resp.btn,
                                timer: resp.timer
                            });
                        }
                    });
                }
            });
        });
    }
}

function crearUsuario() {
    const datos = {
        funcion: "agregarUsuario",
        cedula: $("#inputCedula").val(),
        nombre: $("#inputNombre").val(),
        apellido: $("#inputApellido").val(),
        departamento: $("#inputDpto").val(),
        municipio: $("#inputMunicipio").val()
    };

    if (verificarDatosUsuario(datos)) {
        $.ajax({
            type: "POST",
            url: "models/funciones.php",
            data: datos,
            success: function (response) {
                const respuesta = JSON.parse(response);

                respuesta.forEach(resp => {
                    if (resp.action) {
                        $(location).attr("href", resp.href);
                    } else {
                        Swal.fire({
                            icon: resp.icon,
                            title: resp.title,
                            showConfirmButton: resp.btn,
                            timer: resp.timer
                        });
                    }
                });
            }
        });
    }
}

function editarEquipo(data) {
    $.ajax({
        type: "POST",
        url: "models/funciones.php",
        data: {
            funcion: "enviarDatosEquipo",
            accion: "paginaEdit",
            serial: data.serial,
            id_usuario: data.id_usuario,
            id_proc: data.id_proc,
            id_ram: data.id_ram,
            id_disco: data.id_disco,
            monitor: data.monitor,
            teclado: data.teclado,
            mouse: data.mouse
        },
        success: function (response) {
            const respuesta = JSON.parse(response);

            respuesta.forEach(resp => {
                $(location).attr("href", resp.href);
            });
        }
    });
}

function editarUsuario(data) {
    $.ajax({
        type: "POST",
        url: "models/funciones.php",
        data: {
            funcion: "enviarDatosUsuario",
            accion: "paginaEdit",
            id_usuario: data.id_usuario,
            cedula: data.cedula,
            nombre: data.nombre,
            apellido: data.apellido,
            id_depa: data.id_depa,
            id_muni: data.id_muni
        },
        success: function (response) {
            const respuesta = JSON.parse(response);

            respuesta.forEach(resp => {
                $(location).attr("href", resp.href);
            });
        }
    });
}

function recibirDatosEquipo() {
    $.ajax({
        type: "POST",
        url: "models/funciones.php",
        data: {
            funcion: "recibirDatosEquipo"
        },
        success: function (response) {
            const respuesta = JSON.parse(response);

            respuesta.forEach(resp => {
                if (resp.accion == "paginaEdit") {
                    $("#inputSerial").prop('readonly', true);
                    $("#inputSerial").addClass("onlyRead");
                    $("#submit_equipo_btn").text("Editar");
                    $("#submit_equipo_btn").attr("onclick", "confirmEditEquipo()");

                    llenarSelectEquipo(resp.id_proc, resp.id_disco, resp.id_ram, resp.id_usuario);

                    $("#inputSerial").val(resp.serial);
                    $("#inputMonitor").val(resp.monitor);
                    $("#inputTeclado").val(resp.teclado);
                    $("#inputMouse").val(resp.mouse);
                } else if (resp.accion == "paginaCrear") {
                    llenarSelectEquipo();
                    $("#submit_equipo_btn").attr("onclick", "crearEquipo()");
                }
            });
        }
    });
}

function recibirDatosUsuario() {
    $.ajax({
        type: "POST",
        url: "models/funciones.php",
        data: {
            funcion: "recibirDatosUsuario"
        },
        success: function (response) {
            const respuesta = JSON.parse(response);

            respuesta.forEach(resp => {
                if (resp.accion == "paginaEdit") {
                    $("#inputCedula").prop('readonly', true);
                    $("#inputCedula").addClass("onlyRead");
                    $("#submit_usuario_btn").text("Editar");
                    $("#submit_usuario_btn").attr("onclick", "confirmEditUsuario()");

                    llenarSelectUsuario(resp.id_depa, resp.id_muni);

                    $("#inputCedula").val(resp.cedula);
                    $("#inputNombre").val(resp.nombre);
                    $("#inputApellido").val(resp.apellido);
                } else if (resp.accion == "paginaCrear") {
                    llenarSelectUsuario();
                    $("#submit_usuario_btn").attr("onclick", "crearUsuario()");
                }
            });
        }
    });
}

function llenarSelectEquipo(id_proc, id_disco, id_ram, id_usuario) {
    $.ajax({
        type: "POST",
        url: "models/funciones.php",
        data: {
            funcion: "llenarSelectEquipo"
        },
        success: function (response) {
            const respuesta = JSON.parse(response);

            let templateProc = `<option selected value="">Seleccionar</option>`;
            let templateRam = `<option selected value="">Seleccionar</option>`;
            let templateDisco = `<option selected value="">Seleccionar</option>`;
            let templateUsuario = `<option selected value="">Seleccionar</option>`;
            respuesta.forEach(resp => {
                selects = {
                    procesador: resp.proc,
                    disco: resp.disco,
                    ram: resp.ram,
                    usuario: resp.usuario
                };
            });

            selects.procesador.forEach(resp => {
                templateProc += `
                <option value="${resp.id_proc}">${resp.proc}</option>
                `;
            });
            selects.ram.forEach(resp => {
                templateRam += `
                <option value="${resp.id_ram}">${resp.ram}</option>
                `;
            });
            selects.disco.forEach(resp => {
                templateDisco += `
                <option value="${resp.id_disco}">${resp.disco}</option>
                `;
            });
            selects.usuario.forEach(resp => {
                templateUsuario += `
                <option value="${resp.id_usuario}">${resp.nombre + ' ' + resp.apellido}</option>
                `;
            });


            $("#inputProc").html(templateProc);
            $("#inputRam").html(templateRam);
            $("#inputDisco").html(templateDisco);
            $("#inputUsuario").html(templateUsuario);

            $("#inputProc").val(id_proc);
            $("#inputDisco").val(id_disco);
            $("#inputRam").val(id_ram);
            $("#inputUsuario").val(id_usuario);
        }
    });
}

function llenarSelectUsuario(id_depa, id_muni) {
    $.ajax({
        type: "POST",
        url: "models/funciones.php",
        data: {
            funcion: "llenarSelectUsuario"
        },
        success: function (response) {
            const respuesta = JSON.parse(response);

            let template = `<option selected value="">Seleccionar</option>`;
            respuesta.forEach(resp => {
                template += `<option value="${resp.id_depa}">${resp.depa}</option>"`;
            });

            $("#inputDpto").html(template);
            $("#inputDpto").val(id_depa);

            if (id_depa != undefined) {
                llenarSelectMunicipio(id_depa, id_muni);
            }
        }
    });
}

function llenarSelectMunicipio(id_depa, id_muni) {
    $.ajax({
        type: "POST",
        url: "models/funciones.php",
        data: {
            funcion: "llenarSelectMunicipio",
            depa: id_depa
        },
        success: function (response) {
            const respuesta = JSON.parse(response);

            let template = `<option selected value="">Seleccionar</option>`;
            respuesta.forEach(resp => {
                template += `<option value="${resp.id_muni}">${resp.muni}</option>"`;
            });

            $("#inputMunicipio").html(template);
            $("#inputMunicipio").val(id_muni);
        }
    });
}

function confirmEditEquipo() {
    const datos = {
        funcion: "editarEquipo",
        serial: $("#inputSerial").val(),
        id_usuario: $("#inputUsuario").val(),
        id_proc: $("#inputProc").val(),
        id_ram: $("#inputRam").val(),
        id_disco: $("#inputDisco").val(),
        monitor: $("#inputMonitor").val(),
        teclado: $("#inputTeclado").val(),
        mouse: $("#inputMouse").val()
    };

    if (verificarDatosEquipo(datos)) {
        $.ajax({
            type: "POST",
            url: "models/funciones.php",
            data: datos,
            success: function (response) {
                const respuesta = JSON.parse(response);

                respuesta.forEach(resp => {
                    if (resp.action) {
                        $(location).attr("href", resp.href);
                    } else if (!resp.action) {
                        Swal.fire({
                            icon: resp.icon,
                            title: resp.title,
                            showConfirmButton: resp.btn,
                            timer: resp.timer
                        });
                    }
                });
            }
        });
    }

}

function confirmEditUsuario() {
    const datos = {
        funcion: "editarUsuario",
        cedula: $("#inputCedula").val(),
        nombre: $("#inputNombre").val(),
        apellido: $("#inputApellido").val(),
        id_depa: $("#inputDpto").val(),
        id_muni: $("#inputMunicipio").val()
    }
    if (verificarDatosUsuario(datos)) {
        $.ajax({
            type: "POST",
            url: "models/funciones.php",
            data: datos,
            success: function (response) {
                const respuesta = JSON.parse(response);

                respuesta.forEach(resp => {
                    if (resp.action) {
                        $(location).attr("href", resp.href);
                    } else if (!resp.action) {
                        Swal.fire({
                            icon: resp.icon,
                            title: resp.title,
                            showConfirmButton: resp.btn,
                            timer: resp.timer
                        });
                    }
                });
            }
        });
    }
}

function mensajeEquipo() {
    $.ajax({
        type: "POST",
        url: "models/funciones.php",
        data: {
            funcion: "mensajeEquipo"
        },
        success: function (response) {
            const respuesta = JSON.parse(response);

            respuesta.forEach(resp => {
                if (resp.action) {
                    Swal.fire({
                        icon: resp.icon,
                        title: resp.title,
                        showConfirmButton: resp.btn,
                        timer: resp.timer
                    });
                }
            });
        }
    });
}

function mensajeUsuario() {
    $.ajax({
        type: "POST",
        url: "models/funciones.php",
        data: {
            funcion: "mensajeUsuario"
        },
        success: function (response) {
            const respuesta = JSON.parse(response);

            respuesta.forEach(resp => {
                if (resp.action) {
                    Swal.fire({
                        icon: resp.icon,
                        title: resp.title,
                        showConfirmButton: resp.btn,
                        timer: resp.timer
                    });
                }
            });
        }
    });
}

$("#back_equipo_btn").click(function () {
    $.ajax({
        type: "POST",
        url: "models/funciones.php",
        data: {
            funcion: "listEquiposPage"
        },
        success: function (response) {
            const respuesta = JSON.parse(response);

            respuesta.forEach(resp => {
                $(location).attr("href", resp.href);
            });
        }
    });
});

$("#back_usuario_btn").click(function () {
    $.ajax({
        type: "POST",
        url: "models/funciones.php",
        data: {
            funcion: "listUsuariosPage"
        },
        success: function (response) {
            const respuesta = JSON.parse(response);

            respuesta.forEach(resp => {
                $(location).attr("href", resp.href);
            });
        }
    });
});

function verificarDatosEquipo(inputs) {
    let verify = new Array();

    if ($("#submit_equipo_btn").text() == "Crear") {
        if (inputs.serial == '') {
            $("#inputSerial").attr('class', 'form-control is-invalid');
            verify.push(0);
        } else {
            $("#inputSerial").attr('class', 'form-control');
        }
    }

    if (inputs.id_usuario == '') {
        $("#inputUsuario").attr('class', 'form-select is-invalid');
        verify.push(0);
    } else {
        $("#inputUsuario").attr('class', 'form-select');
    }
    if (inputs.procesador == '') {
        $("#inputProc").attr('class', 'form-select is-invalid');
        verify.push(0);
    } else {
        $("#inputProc").attr('class', 'form-select');
    }
    if (inputs.ram == '') {
        $("#inputRam").attr('class', 'form-select is-invalid');
        verify.push(0);
    } else {
        $("#inputRam").attr('class', 'form-select');
    }
    if (inputs.disco == '') {
        $("#inputDisco").attr('class', 'form-select is-invalid');
        verify.push(0);
    } else {
        $("#inputDisco").attr('class', 'form-select');
    }
    if (inputs.monitor == '') {
        $("#inputMonitor").attr('class', 'form-control is-invalid');
        verify.push(0);
    } else {
        $("#inputMonitor").attr('class', 'form-control');
    }
    if (inputs.teclado == '') {
        $("#inputTeclado").attr('class', 'form-control is-invalid');
        verify.push(0);
    } else {
        $("#inputTeclado").attr('class', 'form-control');
    }
    if (inputs.mouse == '') {
        $("#inputMouse").attr('class', 'form-control is-invalid');
        verify.push(0);
    } else {
        $("#inputMouse").attr('class', 'form-control');
    }

    if (verify.includes(0)) {
        return false;
    } else {
        return true;
    }
}

function verificarDatosUsuario(inputs) {
    let verify = new Array();

    if ($("#submit_usuario_btn").text() == "Crear") {
        if (inputs.cedula == '') {
            $("#inputCedula").attr('class', 'form-control is-invalid');
            verify.push(0);
        } else {
            $("#inputCedula").attr('class', 'form-control');
        }
    }

    if (inputs.nombre == '') {
        $("#inputNombre").attr('class', 'form-control is-invalid');
        verify.push(0);
    } else {
        $("#inputNombre").attr('class', 'form-control');
    }
    if (inputs.apellido == '') {
        $("#inputApellido").attr('class', 'form-control is-invalid');
        verify.push(0);
    } else {
        $("#inputApellido").attr('class', 'form-control');
    }
    if (inputs.departamento == '') {
        $("#inputDpto").attr('class', 'form-select is-invalid');
        verify.push(0);
    } else {
        $("#inputDpto").attr('class', 'form-select');
    }
    if (inputs.municipio == '') {
        $("#inputMunicipio").attr('class', 'form-select is-invalid');
        verify.push(0);
    } else {
        $("#inputMunicipio").attr('class', 'form-select');
    }

    if (verify.includes(0)) {
        return false;
    } else {
        return true;
    }
}