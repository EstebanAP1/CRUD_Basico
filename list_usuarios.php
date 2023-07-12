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

    <link href="//cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link href="//cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/styles.css">
</head>

<body onload="listarUsuarios()">
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
    <main class="container-xxl">
        <section id="table_main" style="display:none;">
            <div class="col-auto" style="margin-left:2rem;margin-bottom:1rem;">
                <button type="button" class="btn" id="create_usuario">Crear</button>
            </div>

            <table id="list_usuario_table" class="cell-border compact stripe">
                <thead id="talbe_head">
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Departamento</th>
                        <th>Municipio</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody id="user_table_body">
                </tbody>
            </table>
        </section>
    </main>

    <script src="//cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
    <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="./controllers/scripts.js"></script>
</body>

</html>