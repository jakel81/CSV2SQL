<html>
    <head>
        <title>CSV2SQL</title>
    </head>
    <body>
        <h2>CSV2SQL</h2>

        <form name="csv2sql" method="post">
            <fieldset>
                Nom du fichier d'écriture :<br />
                <input type="text" name="toFile" /><br />
                <br />
                Nom de la table d'insertion :<br />
                <input type="text" name="tableName" /><br />
                <br />
                Fichier CSV :<br />
                <input type="file" name="fromFile" /><br />
                <br />
                <input type="submit" value="Convertir en SQL" />
            </fieldset>
        </form>

        <?php
        $fromFile = filter_input(INPUT_POST, 'fromFile');
        $tableName = filter_input(INPUT_POST, 'tableName');
        $toFile = filter_input(INPUT_POST, 'toFile');

        if (($fromFile === null) || ($fromFile === '')) {
            echo "<h2>Veuillez choisir un fichier !!</h2>";
        } else {
            $csvData = file_get_contents($fromFile);
            if ($tableName == NULL || $tableName == "") {
                echo "<h2>Veuillez indiquer le nom de la table !!</h2>";
            } else {
                echo "<h2>Requête SQL :</h2>";
            }

            $csvArray = explode("\n", $csvData);
            $columnNames = explode(";", $csvArray[0]);
            $baseQuery = "INSERT INTO $tableName";
            //Récupèration des colonnes à partir de la 1ere ligne
            $baseQuery .= " (";
            $first = true;
            foreach ($columnNames as $columnName) {
                if ($first === false) {
                    $baseQuery .= " , ";
                }
                $columnName = trim($columnName);
                $baseQuery .= " $columnName ";
                $first = false;
            }
            $baseQuery .= " ) ";
            $baseQuery .= " ";
            // On parcours le fichier et on recupère chaque enregistrement
            $length = count($csvArray) - 1;
            $sql = "";
            for ($i = 1; $i < $length; $i++) {
                $valueQuery = "VALUES ('";
                $first = true;
                $dataRow = explode(";", $csvArray[$i]);
                foreach ($dataRow as $dataValue) {
                    if ($first === false) {
                        $valueQuery .= "' , '";
                    }
                    $dataValue = trim($dataValue);
                    $valueQuery .= "$dataValue";
                    $first = false;
                }
                $valueQuery .= "')";
                // On concatene les bouts de requetes ensemble
                $query = $baseQuery . $valueQuery . ";";
                echo "$query <br />";
                $sql .= "$query \n";
            }



            //Ecriture dans le fichier
            if (($toFile == null) || ($toFile == '')) {
                echo "<h2>Veuillez saisir le nom du fichier de destination.</h2>";
            } else {
                $fh = fopen($toFile, 'a+');
                fwrite($fh, $sql);
                fclose($fh);
                echo "<h2>Requêtes SQL écrite dans le fichier.</h2>";
            }
        }
        ?>
    </body>
</html>