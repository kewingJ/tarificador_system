<?php
	include_once '../includes/config.php';
	include_once '../includes/security.php';

    error_reporting(E_ALL);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    
	session_start();
	require_once '../includes/auth_check.php';
	require_ajax_auth();
	$id = $_SESSION['id_u'];

    function xml_escape($value) {
        return htmlspecialchars((string) $value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    $xml_output = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xml_output .= "<AddressBook>\n";

	$query = mysqli_query($link, "SELECT * FROM tbla_book_phonebook
                                WHERE activo_p = 1 ORDER BY tbla_book_phonebook.id_phonebook DESC");
    $i = 1;
    while($row = mysqli_fetch_array($query)){
    	$xml_output .= "\t<Contact>\n";
    	$xml_output .= "\t\t<LastName>" . xml_escape($row['last_name']) . "</LastName>\n";
    	$xml_output .= "\t\t<FirstName>" . xml_escape($row['first_name']) ."</FirstName>\n";
    	$xml_output .= "\t\t\t<Phone>\n\t\t\t\t<phonenumber>" . xml_escape($row['phone_number']) . "</phonenumber>\n";
    	$xml_output .= "\t\t\t\t<accountindex>" . xml_escape($row['account_index']) . "</accountindex>\n";
    	$xml_output .= "\t\t\t</Phone>\n";
        $xml_output .= "\t\t\t<Groups>\n";
        $xml_output .= "\t\t\t\t<groupid>" . xml_escape($row['type']) . "</groupid>\n";
        $xml_output .= "\t\t\t</Groups>\n";
    	$xml_output .= "\t</Contact>\n";
    }
    $xml_output .= "</AddressBook>";

    $fp = fopen('../gs_phonebook.xml', 'wb');
    fwrite($fp, $xml_output);
    fclose($fp);
    // print($xml_output);
    echo 'bien';
    exit;
?>