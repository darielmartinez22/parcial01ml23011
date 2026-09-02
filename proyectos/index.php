<?php

session_start();

define("CARGO_FIJO", 5);

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
