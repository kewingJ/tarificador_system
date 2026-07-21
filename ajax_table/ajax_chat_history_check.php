<?php
session_start();
require_once '../includes/auth_check.php';
require_ajax_auth();
include_once '../includes/config.php';

require('ssp.class.php');

header('Content-Type: application/json; charset=utf-8');

$db = SSP::db($sql_details_ejabberd);
$stmt = $db->query('SELECT MAX(`id`) AS max_id FROM `archive`');
$row = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode(array(
    'max_id' => isset($row['max_id']) ? (int) $row['max_id'] : 0
));
