<?php
// Ejecutar el comando Asterisk y capturar la salida
$output = [];
exec("asterisk -rx 'core show hints'", $output);

// Inicializar un array asociativo para almacenar los resultados
$results = [];

// Procesar cada línea de la salida
foreach ($output as $line) {
    // Utilizar una expresión regular para extraer las extensiones y sus estados
    if (preg_match('/^(\d+)@[\w-]+\s*:\s*\w+\/(\d+)\s*State:(\w+)/', $line, $matches)) {
        $extension = $matches[1];
        $state = $matches[3];
        $results[$extension] = ['estado' => $state];
    }
}

// Convertir el array a JSON
$jsonData = json_encode($results, JSON_PRETTY_PRINT);

// Guardar el JSON en un archivo
file_put_contents('hints.json', $jsonData);

echo "Datos guardados en hints.json\n";
?>
