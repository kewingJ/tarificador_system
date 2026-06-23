<?php
	include_once '../includes/config.php';
	include_once '../includes/security.php';
	session_start();
	$id = $_SESSION['id_u'];

    $xml_output = "<CiscoIPPhoneDirectory>\n";
	$xml_output .= "\t<Title>Cisco Coporate Directory</Title>\n";
    $xml_output .= "\t<Prompt>Select the User</Prompt>\n";

	$query = mysqli_query($link, "SELECT * FROM tbla_book_phonebook p
                                INNER JOIN tbla_book_tipo_extencion t
                                ON p.type = t.id_tipo_ex
                                WHERE p.activo_p = 1 ORDER BY p.id_phonebook DESC");
    $i = 1;
    while($row = mysqli_fetch_array($query)){
        $nombre_ex = strtolower($row['nombre_ex']);
    	$xml_output .= "\t<DirectoryEntry>\n";
    	$xml_output .= "\t\t<Name>".$row['last_name'].' '.$row['first_name']."</Name>\n";
    	$xml_output .= "\t\t<Telephone>" . $row['phone_number'] . "</Telephone>\n";
    	$xml_output .= "\t</DirectoryEntry>\n";
    }
    $xml_output .= "</CiscoIPPhoneDirectory>\n";

    $fp = fopen('../gs_phonebookCisco.xml', 'wb');
    fwrite($fp, $xml_output);
    fclose($fp);
    // print($xml_output);
    echo 'bien';
    exit;
?>