<?php
include("../config/conexion.php");
$bd = new dataBase;

if ($_POST["funcion"] == "validarLogin") {
    session_start();

    if (empty($_SESSION["user"])) {
        echo "0";
    }
}

if ($_POST["funcion"] == "iniciarSesion") {
    $user = $_POST["user"];
    $pass = $_POST["pass"];
    // $passE = password_hash($pass, PASSWORD_DEFAULT);

    $conn = $bd->conn();

    // INSERTAR ADMIN CON CONTRASEÑA ENCRIPTADA
    // $sql = "INSERT INTO administradores(user, pass) VALUES (?, ?)";
    // $stmt = $conn->prepare($sql);
    // $stmt->bind_param("ss", $user, $passE);
    // $stmt->execute();

    $sql = "SELECT * FROM administradores WHERE user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if (password_verify($pass, $row["pass"])) {
            session_start();

            $_SESSION["user"] = $user;
            $_SESSION["mensajeEquipo"] = "sesion";

            $json[] = array(
                "action" => true,
                "href" => "main.php"
            );

            echo json_encode($json);
        } else {
            $json[] = array(
                "action" => false,
                "icon" => 'error',
                "title" => 'Usuario o contraseña incorrectos',
                "timer" => '1000',
                "btn" => false,
                'status' => false,
                'msg' => 'Error en contraseña'
            );

            echo json_encode($json);
        }
    } else {
        $json[] = array(
            "action" => false,
            "icon" => 'error',
            "title" => 'Usuario o contraseña incorrectos',
            "timer" => '1000',
            "btn" => false,
            "status" => false,
            "msg" => 'Usuario no encontrado'
        );

        echo json_encode($json);
    }
    $conn->close();
}

if ($_POST["funcion"] == "listEquiposPage") {
    $json[] = array(
        "href" => "main.php"
    );
    echo json_encode($json);
}

if ($_POST["funcion"] == "listUsuariosPage") {
    $json[] = array(
        "href" => "list_usuarios.php"
    );
    echo json_encode($json);
}

if ($_POST["funcion"] == "cerrarSesion") {
    session_start();
    session_destroy();

    $json[] = array(
        "href" => "index.php"
    );
    echo json_encode($json);
}

if ($_POST["funcion"] == "enviarDatosEquipo") {
    session_start();

    if ($_POST["accion"] == "paginaEdit") {
        $json[] = array(
            "funcion" => $_POST["funcion"],
            "accion" => $_POST["accion"],
            "serial" => $_POST["serial"],
            "id_usuario" => $_POST["id_usuario"],
            "id_proc" => $_POST["id_proc"],
            "id_ram" => $_POST["id_ram"],
            "id_disco" => $_POST["id_disco"],
            "monitor" => $_POST["monitor"],
            "teclado" => $_POST["teclado"],
            "mouse" => $_POST["mouse"]
        );
    } else {
        $json[] = array(
            "funcion" => $_POST["funcion"],
            "accion" => $_POST["accion"],
        );
    }
    $_SESSION["enviarDatosEquipo"] = $json;

    $json[] = array(
        "href" => "crear_editar_equipo.php"
    );
    echo json_encode($json);
}

if ($_POST["funcion"] == "recibirDatosEquipo") {
    session_start();

    $json = $_SESSION["enviarDatosEquipo"];
    $jsonstring = json_encode($json);

    echo $jsonstring;
}

if ($_POST["funcion"] == "enviarDatosUsuario") {
    session_start();

    if ($_POST["accion"] == "paginaEdit") {
        $json[] = array(
            "funcion" => $_POST["funcion"],
            "accion" => $_POST["accion"],
            "id_usuario" => $_POST["id_usuario"],
            "cedula" => $_POST["cedula"],
            "nombre" => $_POST["nombre"],
            "apellido" => $_POST["apellido"],
            "id_depa" => $_POST["id_depa"],
            "id_muni" => $_POST["id_muni"]
        );
    } else {
        $json[] = array(
            "funcion" => $_POST["funcion"],
            "accion" => $_POST["accion"],
        );
    }
    $_SESSION["enviarDatosUsuario"] = $json;

    $json[] = array(
        "href" => "crear_editar_usuario.php"
    );
    echo json_encode($json);
}

if ($_POST["funcion"] == "recibirDatosUsuario") {
    session_start();

    $json = $_SESSION["enviarDatosUsuario"];
    $jsonstring = json_encode($json);

    echo $jsonstring;
}

if ($_POST["funcion"] == "listarEquipos") {
    $conn = $bd->conn();

    $sw = false;

    $sql = "SELECT e.id_equipo, e.id_usuario, e.serial, e.monitor, e.teclado, e.mouse, p.id_proc, p.proc, d.id_disco, d.disco, r.id_ram, r.ram, dep.depa, CONCAT(u.nombre, ' ', u.apellido) AS nombre
            FROM equipos e
            INNER JOIN usuarios u ON u.id_usuario = e.id_usuario
            INNER JOIN departamentos dep ON dep.id_depa = u.id_depa
            INNER JOIN procesadores p ON p.id_proc = e.id_proc
            INNER JOIN discos d ON d.id_disco = e.id_disco
            INNER JOIN ram r ON r.id_ram = e.id_ram";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $sw = true;
        $json[] = array(
            "id_equipo" => $row["id_equipo"],
            "id_usuario" => $row["id_usuario"],
            "seccional" => $row["depa"],
            "serial" => $row["serial"],
            "id_proc" => $row["id_proc"],
            "proc" => $row["proc"],
            "id_disco" => $row["id_disco"],
            "disco" => $row["disco"],
            "id_ram" => $row["id_ram"],
            "ram" => $row["ram"],
            "monitor" => $row["monitor"],
            "teclado" => $row["teclado"],
            "mouse" => $row["mouse"],
            "nombreUsuario" => $row["nombre"]
        );
    }
    $conn->close();

    //Para datatable
    if ($sw) {
        $arrResponse["data"] = $json;
    } else {
        $arrResponse["data"] = [];
    }

    echo json_encode($arrResponse);
}

if ($_POST["funcion"] == "listarUsuarios") {
    $conn = $bd->conn();

    $sw = false;

    $sql = "SELECT * FROM usuarios u
            INNER JOIN municipios m ON u.id_muni = m.id_muni
            INNER JOIN departamentos d ON LEFT(m.id_muni, 2) = d.id_depa
            WHERE cedula != 0";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $sw = true;
        $json[] = array(
            "id_usuario" => $row["id_usuario"],
            "cedula" => $row["cedula"],
            "nombre" => $row["nombre"],
            "apellido" => $row["apellido"],
            "id_depa" => $row["id_depa"],
            "depa" => $row["depa"],
            "id_muni" => $row["id_muni"],
            "muni" => $row["muni"]
        );
    }

    $conn->close();

    if ($sw) {
        $arrResponse["data"] = $json;
    } else {
        $arrResponse["data"] = [];
    }

    echo json_encode($arrResponse);
}

if ($_POST["funcion"] == "agregarEquipo") {
    $serial = $_POST["serial"];
    $procesador = $_POST["procesador"];
    $disco = $_POST["disco"];
    $ram = $_POST["ram"];
    $monitor = $_POST["monitor"];
    $teclado = $_POST["teclado"];
    $mouse = $_POST["mouse"];
    $id_usuario = $_POST["id_usuario"];

    if ($serial == '' || $procesador == '' || $disco == '' || $ram == '' || $monitor == '' || $teclado == '' || $mouse == '' || $id_usuario == '') {
        $json[] = array(
            "action" => false,
            "icon" => 'question',
            "title" => 'Campo vacío',
            "btn" => false,
            "timer" => 1000
        );

        echo json_encode($json);
    } else {
        $conn = $bd->conn();

        if (buscarSerial($serial, $bd)) {
            $sql = "INSERT INTO equipos (serial, id_proc, id_disco, id_ram, monitor, teclado, mouse, id_usuario)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssssss', $serial, $procesador, $disco, $ram, $monitor, $teclado, $mouse, $id_usuario);
            $stmt->execute();
            $conn->close();

            session_start();
            $_SESSION["mensajeEquipo"] = "crear";

            $json[] = array(
                "action" => true,
                "href" => "main.php"
            );
            echo json_encode($json);
        }
    }
}

if ($_POST["funcion"] == "agregarUsuario") {
    $cedula = $_POST["cedula"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $departamento = $_POST["departamento"];
    $municipio = $_POST["municipio"];

    if ($cedula == '' || $nombre == '' || $apellido == '' || $departamento == '' || $municipio == '') {
        $json[] = array(
            "action" => false,
            "icon" => 'question',
            "title" => 'Campo vacío',
            "btn" => false,
            "timer" => 1000
        );

        echo json_encode($json);
    } else {
        $conn = $bd->conn();

        if (buscarCedula($cedula, $bd)) {
            $sql = "INSERT INTO usuarios(cedula, nombre, apellido, id_depa, id_muni)
            VALUES (?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $cedula, $nombre, $apellido, $departamento, $municipio);
            $stmt->execute();
            $conn->close();

            session_start();
            $_SESSION["mensajeUsuario"] = "crear";

            $json[] = array(
                "action" => true,
                "href" => "list_usuarios.php"
            );
            echo json_encode($json);
        }
    }
}

if ($_POST["funcion"] == "buscarEquipo") {
    $conn = $bd->conn();

    $sql = "SELECT e.id_equipo, e.id_usuario, e.serial, e.monitor, e.teclado, e.mouse, p.id_proc, p.proc, d.id_disco, d.disco, r.id_ram, r.ram, dep.depa, CONCAT(u.nombre, ' ', u.apellido) AS nombre
            FROM equipos e
            INNER JOIN usuarios u ON u.id_usuario = e.id_usuario
            INNER JOIN departamentos dep ON dep.id_depa = u.id_depa
            INNER JOIN procesadores p ON p.id_proc = e.id_proc
            INNER JOIN discos d ON d.id_disco = e.id_disco
            INNER JOIN ram r ON r.id_ram = e.id_ram
            WHERE e.serial = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $json[] = array(
            "id_equipo" => $row["id_equipo"],
            "id_usuario" => $row["id_usuario"],
            "seccional" => $row["depa"],
            "serial" => $row["serial"],
            "id_proc" => $row["id_proc"],
            "proc" => $row["proc"],
            "id_disco" => $row["id_disco"],
            "disco" => $row["disco"],
            "id_ram" => $row["id_ram"],
            "ram" => $row["ram"],
            "monitor" => $row["monitor"],
            "teclado" => $row["teclado"],
            "mouse" => $row["mouse"],
            "nombreUsuario" => $row["nombre"]
        );
    }
    $conn->close();
}

if ($_POST["funcion"] == "editarEquipo") {
    $serial = $_POST["serial"];
    $id_usuario = $_POST["id_usuario"];
    $id_proc = $_POST["id_proc"];
    $id_disco = $_POST["id_disco"];
    $id_ram = $_POST["id_ram"];
    $monitor = $_POST["monitor"];
    $teclado = $_POST["teclado"];
    $mouse = $_POST["mouse"];

    if ($serial == '' || $id_proc == '' || $id_disco == '' || $id_ram == '' || $monitor == '' || $teclado == '' || $mouse == '' || $id_usuario == '') {
        $json[] = array(
            "action" => false,
            "icon" => 'question',
            "title" => 'Campo vacío',
            "btn" => false,
            "timer" => 1000
        );

        echo json_encode($json);
    } else {
        $conn = $bd->conn();

        $sql = "UPDATE equipos SET id_proc = ?, id_ram = ?, id_disco = ?,
     monitor = ?, teclado = ?, mouse = ?, id_usuario = ? WHERE serial = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssss', $id_proc, $id_ram, $id_disco, $monitor, $teclado, $mouse, $id_usuario, $serial);
        $stmt->execute();
        $conn->close();

        session_start();
        $_SESSION["mensajeEquipo"] = "editar";

        $json[] = array(
            "action" => true,
            "href" => "main.php"
        );
        echo json_encode($json);
    }
}

if ($_POST["funcion"] == "editarUsuario") {
    $cedula = $_POST["cedula"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $id_depa = $_POST["id_depa"];
    $id_muni = $_POST["id_muni"];

    if ($cedula == '' || $nombre == '' || $apellido == '' || $id_depa == '' || $id_muni == '') {
        $json[] = array(
            "action" => false,
            "icon" => 'question',
            "title" => 'Campo vacío',
            "btn" => false,
            "timer" => 1000
        );

        echo json_encode($json);
    } else {
        $conn = $bd->conn();

        $sql = "UPDATE usuarios SET nombre = ?, apellido = ?, id_depa = ?,
        id_muni = ? WHERE cedula = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nombre, $apellido, $id_depa, $id_muni, $cedula);
        $stmt->execute();
        $conn->close();

        session_start();
        $_SESSION["mensajeUsuario"] = "editar";

        $json[] = array(
            "action" => true,
            "href" => "list_usuarios.php"
        );
        echo json_encode($json);
    }
}

if ($_POST["funcion"] == "llenarSelectEquipo") {
    $conn = $bd->conn();

    $sql = "SELECT * FROM procesadores";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $jsonProc[] = array(
            "id_proc" => $row["id_proc"],
            "proc" => $row["proc"]
        );
    }

    $sql = "SELECT * FROM discos";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $jsonDisco[] = array(
            "id_disco" => $row["id_disco"],
            "disco" => $row["disco"]
        );
    }

    $sql = "SELECT * FROM ram";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $jsonRam[] = array(
            "id_ram" => $row["id_ram"],
            "ram" => $row["ram"]
        );
    }

    $sql = "SELECT * FROM usuarios";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $jsonUsuario[] = array(
            "id_usuario" => $row["id_usuario"],
            "cedula" => $row["cedula"],
            "nombre" => $row["nombre"],
            "apellido" => $row["apellido"],
            "id_depa" => $row["id_depa"],
            "id_muni" => $row["id_muni"]
        );
    }

    $conn->close();

    $json[] = array(
        "proc" => $jsonProc,
        "disco" => $jsonDisco,
        "ram" => $jsonRam,
        "usuario" => $jsonUsuario
    );

    echo json_encode($json);
}

if ($_POST["funcion"] == "llenarSelectUsuario") {
    $conn = $bd->conn();

    $sql = "SELECT * FROM departamentos";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $json[] = array(
            "id_depa" => $row["id_depa"],
            "depa" => $row["depa"]
        );
    }

    $conn->close();

    echo json_encode($json);
}

if ($_POST["funcion"] == "llenarSelectMunicipio") {
    $depa = $_POST["depa"];

    $conn = $bd->conn();

    $sql = "SELECT * FROM municipios m
            INNER JOIN departamentos d ON LEFT(m.id_muni, 2) = d.id_depa
            WHERE id_depa = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $depa);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $json[] = array(
            "id_muni" => $row["id_muni"],
            "muni" => $row["muni"]
        );
    }

    $conn->close();

    echo json_encode($json);
}

if ($_POST["funcion"] == "eliminarEquipo") {
    $id_equipo = $_POST["id_equipo"];

    $conn = $bd->conn();

    $sql = "DELETE FROM equipos WHERE id_equipo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $id_equipo);
    $stmt->execute();
    $conn->close();

    session_start();
    $_SESSION["mensajeEquipo"] = "eliminar";
}

if ($_POST["funcion"] == "mensajeEquipo") {
    session_start();
    if ($_SESSION["mensajeEquipo"] == "sesion") {
        $jsonMessage[] = array(
            "action" => true,
            "icon" => 'success',
            "title" => 'Sesión iniciada',
            "btn" => false,
            "timer" => 1000
        );
        $_SESSION["mensajeEquipo"] = "0";
    } else if ($_SESSION["mensajeEquipo"] == "crear") {
        $jsonMessage[] = array(
            "action" => true,
            "icon" => 'success',
            "title" => 'Equipo agregado',
            "btn" => false,
            "timer" => 1000
        );
        $_SESSION["mensajeEquipo"] = "0";
    } else if ($_SESSION["mensajeEquipo"] == "editar") {
        $jsonMessage[] = array(
            "action" => true,
            "icon" => 'success',
            "title" => 'Equipo actualizado',
            "btn" => false,
            "timer" => 1000
        );
        $_SESSION["mensajeEquipo"] = "0";
    } else if ($_SESSION["mensajeEquipo"] == "eliminar") {
        $jsonMessage[] = array(
            "action" => true,
            "icon" => 'success',
            "title" => 'Equipo eliminado',
            "btn" => false,
            "timer" => 1000
        );
        $_SESSION["mensajeEquipo"] = "0";
    } else {
        $jsonMessage[] = array(
            "action" => false
        );
    }

    echo json_encode($jsonMessage);
}

if ($_POST["funcion"] == "mensajeUsuario") {
    session_start();
    if ($_SESSION["mensajeUsuario"] == "crear") {
        $jsonMessage[] = array(
            "action" => true,
            "icon" => 'success',
            "title" => 'Usuario agregado',
            "btn" => false,
            "timer" => 1000
        );
        $_SESSION["mensajeUsuario"] = "0";
    } else if ($_SESSION["mensajeUsuario"] == "editar") {
        $jsonMessage[] = array(
            "action" => true,
            "icon" => 'success',
            "title" => 'Usuario actualizado',
            "btn" => false,
            "timer" => 1000
        );
        $_SESSION["mensajeUsuario"] = "0";
    } else {
        $jsonMessage[] = array(
            "action" => false
        );
    }

    echo json_encode($jsonMessage);
}

if ($_POST["funcion"] == "confirmDeleteEquipo") {
    $jsonMessage[] = array(
        "title" => 'Seguro que deseas eliminar el equipo con serial: ' . $_POST["serial"] . ' ?',
        "icon" => 'warning',
        "showCancelButton" => true,
        "confirmButtonColor" => '#3085d6',
        "cancelButtonColor" => '#d33',
        "cancelButtonText" => 'Cancelar',
        "confirmButtonText" => 'Eliminar'
    );

    echo json_encode($jsonMessage);
}

function buscarSerial($serial, $bd)
{
    $conn = $bd->conn();

    $sql = "SELECT serial FROM equipos WHERE serial = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $serial);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $sw = false;

        $json[] = array(
            "action" => false,
            "icon" => 'error',
            "title" => 'Serial ya ingresado',
            "btn" => false,
            "timer" => 1000
        );

        echo json_encode($json);
    } else {
        $sw = true;
    }
    $conn->close();
    return $sw;
}

function buscarCedula($cedula, $bd)
{
    $conn = $bd->conn();

    $sql = "SELECT * FROM usuarios WHERE cedula = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $sw = false;

        $json[] = array(
            "action" => false,
            "icon" => 'error',
            "title" => 'Cedula ya ingresada',
            "btn" => false,
            "timer" => 1000
        );

        echo json_encode($json);
    } else {
        $sw = true;
    }

    $conn->close();
    return $sw;
}