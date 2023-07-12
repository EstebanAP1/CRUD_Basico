<?php
session_start();

if (!empty($_SESSION["user"])) {
    header("Location: main.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css
" rel="stylesheet">
    <link rel="stylesheet" href="./css/styles.css">
</head>

<body>
    <main class="container text-center justify-content-center">
        <div id="login_main" class="row justify-content-center align-items-center">
            <div id="login_logo" class="col-6">
                <h2>CRUD</h2>
            </div>
            <div id="login_section" class="col-6">
                <form id="login_form">
                    <div class="col-7">
                        <label for="inputLogin" class="form-label" style="font-weight: 600;">Usuario</label>
                        <input type="text" class="form-control form-control-sm" id="inputUser" autocomplete="off">
                    </div>
                    <div class="col-7 gy-3">
                        <label for="inputPass" class="form-label"
                            style="font-weight: 600;margin-top: .5rem;">Contraseña</label>
                        <input type="password" class="form-control form-control-sm" id="inputPass" autocomplete="off">
                    </div>
                    <div class="col-7">
                        <button type="submit" class="btn btn-sm" id="login_btn" style="margin-top: 1.5rem;">Iniciar
                            sesión</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js
"></script>
    <script>
        $("#login_form").submit(function (e) {
            e.preventDefault();

            const datos = {
                funcion: "iniciarSesion",
                user: $("#inputUser").val(),
                pass: $("#inputPass").val()
            };

            if (verificarDatosLogin(datos)) {
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
        });
        function verificarDatosLogin(inputs) {
            let verify = new Array();

            if (inputs.user == '') {
                $("#inputUser").attr('class', 'form-control form-control-sm is-invalid');
                verify.push(0);
            } else {
                $("#inputUser").attr('class', 'form-control form-control-sm');
            }
            if (inputs.pass == '') {
                $("#inputPass").attr('class', 'form-control form-control-sm is-invalid');
                verify.push(0);
            } else {
                $("#inputPass").attr('class', 'form-control form-control-sm');
            }

            if (verify.includes(0)) {
                return false;
            } else {
                return true;
            }
        }
    </script>
</body>

</html>