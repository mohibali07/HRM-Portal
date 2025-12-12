<?php
$sql = file_get_contents('database.sql');
preg_match_all('/CREATE TABLE\s+[`]?([a-zA-Z0-9_]+)[`]?/i', $sql, $matches);
$tables = $matches[1];
sort($tables);
file_put_contents('tables.json', json_encode(array_unique($tables), JSON_PRETTY_PRINT));
?>