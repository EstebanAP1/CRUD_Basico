<?php
session_start();
if (empty($_SESSION["user"])) {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD JQuery</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css
" rel="stylesheet">
    <link rel="stylesheet" href="./css/styles.css">
</head>

<body onload="recibirDatosUsuario()">
    <header class="container-xxl text-center">
        <nav class="row" id="head_div">
            <div class="col-1" id="logo_head">
                <h3 style="margin:0px;color:black;">CRUD</h3>
            </div>
            <div class="col-1" id="logo_head">
                <button type="button" class="btn" id="list_equipos_btn">Equipos</button>
            </div>
            <div class="col-1" id="logo_head">
                <button type="button" class="btn" id="list_usuarios_btn">Usuarios</button>
            </div>
            <div class="col-8 text-end">
                <button type="button" class="btn" id="logout_btn">Cerrar Sesión</button>
            </div>
        </nav>
    </header>
    <main class="container-xxl text-center">
        <section class="form_main">
            <div class="row gy-3 align-items-center justify-content-center">
                <div class="col-auto">
                    <label for="inputCedula" class="visually-hidden">Cédula</label>
                    <div class="input-group">
                        <div class="input-group-text" style="background-color: blueviolet;color:black;border:hidden;">
                            Cédula</div>
                        <input type="number" class="form-control" id="inputCedula" autocomplete="off">
                    </div>
                </div>
                <div class="col-auto">
                    <label for="inputNombre" class="visually-hidden">Nombre</label>
                    <div class="input-group">
                        <div class="input-group-text" style="background-color: blueviolet;color:black;border:hidden;">
                            Nombre</div>
                        <input type="text" class="form-control" id="inputNombre" autocomplete="off">
                    </div>
                </div>
                <div class="col-auto">
                    <label for="inputApellido" class="visually-hidden">Apellido</label>
                    <div class="input-group">
                        <div class="input-group-text" style="background-color: blueviolet;color:black;border:hidden;">
                            Apellido</div>
                        <input type="text" class="form-control" id="inputApellido" autocomplete="off">
                    </div>
                </div>
                <div class="col-auto">
                    <label for="inputDpto" class="visually-hidden">Departamento</label>
                    <div class="input-group">
                        <div class="input-group-text" style="background-color: blueviolet;color:black;border:hidden;">
                            Departamento</div>
                        <select class="form-select" id="inputDpto" onchange="llenarSelectMunicipio(this.value)">
                        </select>
                    </div>
                </div>
                <div class="col-auto">
                    <label for="inputMunicipio" class="visually-hidden">Municipio</label>
                    <div class="input-group">
                        <div class="input-group-text" style="background-color: blueviolet;color:black;border:hidden;">
                            Municipio</div>
                        <select class="form-select" id="inputMunicipio">
                            <option selected value="">Seleccionar</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row align-items-center justify-content-center">
                <div class="col-auto gy-5">
                    <button type="submit" class="btn form_btn" id="submit_usuario_btn">Crear</button>
                    <button type="button" class="btn btn-danger" id="back_usuario_btn">Volver</button>
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js
"></script>
    <script src="./controllers/scripts.js"></script>
</body>

</html>