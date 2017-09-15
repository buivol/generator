<?php require_once '../config.php';
$settings = file_get_contents('../saved.dat');
$settings = json_decode($settings, true);
function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' гб';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' мб';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' кб';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' байт';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' байт';
    } else {
        $bytes = '0 байт';
    }
    return $bytes;
}
?><!DOCTYPE html>
<html lang="ru">
<head>
    <title>Скачать презентации</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container">
    <h2>Скачать презентации</h2>
    <div class="cont">
        <table id="download" class="table table-bordered table-hover tablesorter">
            <thead>
            <tr>
                <th>Презентация <i class="fa fa-chevron-down" aria-hidden="true"></i><i class="fa fa-chevron-up"
                                                                                        aria-hidden="true"></i></th>
                <th>Время создания <i class="fa fa-chevron-down" aria-hidden="true"></i><i class="fa fa-chevron-up"
                                                                                           aria-hidden="true"></i></th>
                <th>Размер</th>
                <th>Ссылка</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $f = scandir($config['excel']);
            foreach ($f as $file) {
                if (preg_match('/\.(xlsx)/', $file)) {
                    echo '<tr>';
                    echo "<td>{$file}</td>";
                    echo "<td>";
                    echo date("F d Y H:i:s", filemtime($config['excel'] . $file));
                    echo "</td>";
                    $size = formatSizeUnits(filesize($config['excel'] . $file));
                    $sized = formatSizeUnits(filesize($config['excel'] . substr($file, 0, -1)));
                    echo "<td>XLSX: {$size} XLS: {$sized}</td>";
                    $d1 = realpath($_SERVER['DOCUMENT_ROOT']);
                    $d2 = realpath($config['excel']);
                    $d3 = str_replace($d1, '', $d2);
                    $d3 = str_replace('\\', '/', $d3) . '/' . $file;
                    $d4 = substr($d3, 0, -1);
                    echo "<td><a href=\"{$d3}\">Скачать XLSX</a> <a href=\"{$d4}\">Скачать XLS</a></td>";
                    echo '</tr>';
                }
            }

            ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>
<script src="https://use.fontawesome.com/6d2a93d693.js"></script>
<script src="../assets/ts.js"></script>
<script src="../assets/get.js"></script>
</body>
</html>