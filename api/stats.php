<?php
require_once __DIR__ . '/../includes/functions.php';

checkMethod(['GET']);

$stats = getStats();
successResponse($stats, 'Statistiques récupérées');
