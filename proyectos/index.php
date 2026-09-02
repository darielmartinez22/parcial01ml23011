<?php

session_start();

define("CARGO_FIJO", 5);
//Arreglo multidimensional
$espacios = [

    "Cancha de Futbol" => [
        "disciplina" => "Futbol",
        "capacidad" => 22,
        "costo" => 15,
        "estado" => "Disponible"
    ],

    "Cancha de Baloncesto" => [
        "disciplina" => "Baloncesto",
        "capacidad" => 10,
        "costo" => 12,
        "estado" => "Disponible"
    ],

    "Cancha de Voleibol" => [
        "disciplina" => "Voleibol",
        "capacidad" => 12,
        "costo" => 10,
        "estado" => "Disponible"
    ]
];
//Funcion de Limpiar Texto
    function limpiarTexto($texto)
    {
    $texto = trim($texto);
    $texto = strtolower($texto);
    $texto = ucwords($texto);

    return $texto;
    }

    //Aqui se calcula el descuento que tendran los roles, que son de estudiante, docente y el visitante
    function calcularDescuento($tipo, $subtotal)
    {
    switch ($tipo) {

        case "estudiante":
            return $subtotal * 0.20;

        case "docente":
            return $subtotal * 0.10;

        case "visitante":
            return 0;

        default:
            return 0;
    }
    }
    //Calculamos el total del total y descuento
    function calcularTotal($subtotal, $descuento)
    {
    return ($subtotal - $descuento) + CARGO_FIJO;
    }
    //Primera parte para subir datos 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = limpiarTexto($_POST["nombre"] ?? "");
    $correo = trim($_POST["correo"] ?? "");
    $tipo = $_POST["tipo"] ?? "";
    $disciplina = $_POST["disciplina"] ?? "";
    $espacio = $_POST["espacio"] ?? "";

    $participantes = $_POST["participantes"] ?? 0;
    $horas = $_POST["horas"] ?? 0;

    $errores = [];
    }
    //Para que el usuario piense un poquito, en que debe de llenar todos los espacios y las validaciones que le corresponden
    if ($nombre == "") {
        $errores[] = "El nombre es obligatorio.";
    }
    //Este es un filtro de validacion
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo no es válido.";
    }

    if ($participantes <= 0) {
        $errores[] = "Los participantes deben ser mayores que cero.";
    }

    if ($horas <= 0) {
        $errores[] = "Las horas deben ser mayores que cero.";
    }

    if (!isset($espacios[$espacio])) {
        $errores[] = "Debe seleccionar un espacio.";
    }

    if (count($errores) == 0) {

        $datos = $espacios[$espacio];

        $capacidad = $datos["capacidad"];
        $costoHora = $datos["costo"];
        $estado = $datos["estado"];

        if ($participantes > $capacidad) {

            $errores[] = "El espacio solo permite $capacidad participantes.";

        } else {

            if ($disciplina != $datos["disciplina"]) {

                $errores[] = "La disciplina no corresponde al espacio.";

            } else {

            }
        }
    }
    //Parte de los calculos
    $subtotal = $costoHora * $horas;

                $descuento = calcularDescuento(
                    $tipo,
                    $subtotal
                );

                $total = calcularTotal(
                    $subtotal,
                    $descuento
                );

                $registro = [

                    "nombre" => $nombre,
                    "correo" => $correo,
                    "tipo" => $tipo,
                    "disciplina" => $disciplina,
                    "espacio" => $espacio,
                    "participantes" => $participantes,
                    "horas" => $horas,
                    "costoHora" => $costoHora,
                    "subtotal" => $subtotal,
                    "descuento" => $descuento,
                    "total" => $total
                ];


                // Crear sesión si no existe
                if (!isset($_SESSION["reservas"])) {
                    $_SESSION["reservas"] = [];
                }
                //Guarda registro
                $_SESSION["reservas"][] = $registro;
?>
<html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Reservas
    </title>
</head>
 <style>
        body {
            font-family: Arial;
            background: #eeeeee;
            margin: 30px;
        }

        .contenedor {
            width: 600px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        h1, h2 {
            text-align: center;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background: black;
            color: white;
            border: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #999;
            padding: 6px;
            text-align: center;
        }

        th {
            background: #ddd;
        }

        .error {
            background: #ffdddd;
            padding: 10px;
            margin-bottom: 15px;
        }

    </style>
<body>

<div class="contenedor">

    <h1>Reserva de Espacios Deportivos</h1>

    <?php

    // Mostrar errores
    if (count($errores) > 0) {

        echo "<div class='error'>";

        foreach ($errores as $error) {

            echo htmlspecialchars($error);

        }

        echo "</div>";
    }

    ?>


    <form method="POST" action="">

        <label>Nombre:</label>

        <input
            type="text"
            name="nombre"
            required
        >


        <label>Correo electrónico:</label>

        <input
            type="email"
            name="correo"
            required
        >


        <label>Tipo de usuario:</label>

        <select name="tipo" required>

            <option value="">Seleccione</option>

            <option value="estudiante">
                Estudiante
            </option>

            <option value="docente">
                Docente
            </option>

            <option value="visitante">
                Visitante
            </option>

        </select>
        <label>Disciplina:</label>

        <select name="disciplina" required>

            <option value="">Seleccione</option>

            <option value="Futbol">
                Futbol
            </option>

            <option value="Baloncesto">
                Baloncesto
            </option>

            <option value="Voleibol">
                Voleibol
            </option>

        </select>


        <label>Espacio:</label>

        <select name="espacio" required>

            <option value="">Seleccione</option>

            <option value="Cancha de Futbol">
                Cancha de Futbol
            </option>

            <option value="Cancha de Baloncesto">
                Cancha de Baloncesto
            </option>

            <option value="Cancha de Voleibol">
                Cancha de Voleibol
            </option>

        </select>


        <label>Cantidad de participantes:</label>

        <input
            type="number"
            name="participantes"
            min="1"
            required
        >

        <label>Cantidad de horas:</label>

        <input
            type="number"
            name="horas"
            min="1"
            required
        >

        <button type="submit">
            Reservar
        </button>

    </form>

    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST" && count($errores) == 0) {

        echo "<h2>Comprobante de Reserva</h2>";

        // Mostrar último registro
        $ultimo = end($_SESSION["reservas"]);

        echo "<p><strong>Nombre:</strong> "
            . htmlspecialchars($ultimo["nombre"])
            . "</p>";

        echo "<p><strong>Correo:</strong> "
            . htmlspecialchars($ultimo["correo"])
            . "</p>";

        echo "<p><strong>Tipo:</strong> "
            . htmlspecialchars($ultimo["tipo"])
            . "</p>";

        echo "<p><strong>Disciplina:</strong> "
            . htmlspecialchars($ultimo["disciplina"])
            . "</p>";

        echo "<p><strong>Espacio:</strong> "
            . htmlspecialchars($ultimo["espacio"])
            . "</p>";

        echo "<p><strong>Participantes:</strong> "
            . $ultimo["participantes"]
            . "</p>";

        echo "<p><strong>Horas:</strong> "
            . $ultimo["horas"]
            . "</p>";

        echo "<p><strong>Costo por hora:</strong> $"
            . number_format($ultimo["costoHora"], 2)
            . "</p>";

        echo "<p><strong>Subtotal:</strong> $"
            . number_format($ultimo["subtotal"], 2)
            . "</p>";

        echo "<p><strong>Descuento:</strong> $"
            . number_format($ultimo["descuento"], 2)
            . "</p>";

        echo "<p><strong>Cargo fijo:</strong> $"
            . number_format(CARGO_FIJO, 2)
            . "</p>";

        echo "<p><strong>Total a pagar:</strong> $"
            . number_format($ultimo["total"], 2)
            . "</p>";
    }

    ?>

    <?php

    if (isset($_SESSION["reservas"]) &&
        count($_SESSION["reservas"]) > 0) {

        echo "<h2>Reservas Registradas</h2>";

        echo "<table>";

        echo "<tr>";

        echo "<th>Nombre</th>";
        echo "<th>Disciplina</th>";
        echo "<th>Espacio</th>";
        echo "<th>Participantes</th>";
        echo "<th>Horas</th>";
        echo "<th>Total</th>";

        echo "</tr>";

        foreach ($_SESSION["reservas"] as $reserva) {

            echo "<tr>";

            echo "<td>"
                . htmlspecialchars($reserva["nombre"])
                . "</td>";

            echo "<td>"
                . htmlspecialchars($reserva["disciplina"])
                . "</td>";

            echo "<td>"
                . htmlspecialchars($reserva["espacio"])
                . "</td>";

            echo "<td>"
                . $reserva["participantes"]
                . "</td>";

            echo "<td>"
                . $reserva["horas"]
                . "</td>";

            echo "<td>$"
                . number_format($reserva["total"], 2)
                . "</td>";

        }
        }
        ?>
