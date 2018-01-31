<?php
header("Content-Type: text/html;charset=UTF-8");

$filename = file("departements.csv");

$header = $filename[0];
$arrayHeader = explode(";", $header);
$header = "";
foreach ($arrayHeader as $value) {
    $header .= "<th>$value</th>";
}

$body = "";
for ($i = 1; $i < count($filename); $i++) {
    if (trim($filename[$i]) != "") {
        $body .= "<tr>";
        $line = explode(";", $filename[$i]);
        foreach ($line as $value) {
            $body .= "<td>$value</td>";
        }
        $body .= "</tr>";
    }
}
?>

<table border="1">
    <thead>
        <tr>
            <?php echo $header; ?>
        </tr>
    </thead>
    <tbody>
        <?php echo $body; ?>
    </tbody>
</table>