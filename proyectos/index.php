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
?>
