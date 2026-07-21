<?php
	include_once '../includes/config.php';
	include_once '../includes/security.php';
	session_start();
	require_once '../includes/auth_check.php';
	require_ajax_auth();
	$id = $_SESSION['id_u'];

    function xml_escape($value) {
        return htmlspecialchars((string) $value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    $xml_output = "<SnomIPPhoneDirectory>\n";
    $xml_output .= "\t<Title>Menu</Title>\n";
    $xml_output .= "\t<Prompt>Prompt</Prompt>\n";

	$query = mysqli_query($link, "SELECT * FROM tbla_book_phonebook p
                                INNER JOIN tbla_book_tipo_extencion t
                                ON p.type = t.id_tipo_ex
                                WHERE p.activo_p = 1 ORDER BY p.id_phonebook DESC");
    $i = 1;
    while($row = mysqli_fetch_array($query)){
        $nombre_ex = strtolower($row['nombre_ex']);
    	$xml_output .= "\t<DirectoryEntry>\n";
    	$xml_output .= "\t\t<Name>".xml_escape($row['last_name'].' '.$row['first_name'])."</Name>\n";
    	$xml_output .= "\t\t<Telephone>" . xml_escape($row['phone_number']) . "</Telephone>\n";
    	$xml_output .= "\t</DirectoryEntry>\n";
    }
    $xml_output .= "\t<SoftKeyItem>\n";
    $xml_output .= "\t\t<Name>#</Name>\n";
    $xml_output .= "\t\t<URL>http://www.snom.com/minibrowser/start.xml</URL>\n";
    $xml_output .= "\t</SoftKeyItem>\n";
    $xml_output .= "\t<SoftKeyItem>\n";
    $xml_output .= "\t\t<Name>*</Name>\n";
    $xml_output .= "\t\t<URL>http://www.snom.com/minibrowser/menu.xml</URL>\n";
    $xml_output .= "\t</SoftKeyItem>\n";

    $xml_output .= "</SnomIPPhoneDirectory>\n";

    $fp = fopen('../gs_phonebookSnomIp.xml', 'wb');
    fwrite($fp, $xml_output);
    fclose($fp);
    // print($xml_output);
    echo 'bien';
    exit;
?>