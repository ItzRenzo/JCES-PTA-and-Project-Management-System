<?php
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $output = "-- Data exported from SQLite database\n";
    $output .= "-- Generated on " . date('Y-m-d H:i:s') . "\n\n";

    // Get all tables
    $tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")->fetchAll(PDO::FETCH_COLUMN);

    foreach ($tables as $table) {
        // Get row count
        $count = $db->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();

        if ($count > 0) {
            $output .= "-- Table: $table ($count rows)\n";

            // Get all rows
            $rows = $db->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($rows)) {
                // Get column names from first row
                $columns = array_keys($rows[0]);
                $columnList = '`' . implode('`, `', $columns) . '`';

                $output .= "INSERT INTO `$table` ($columnList) VALUES\n";

                $values = [];
                foreach ($rows as $row) {
                    $rowValues = [];
                    foreach ($row as $value) {
                        if ($value === null) {
                            $rowValues[] = 'NULL';
                        } elseif (is_numeric($value)) {
                            $rowValues[] = $value;
                        } else {
                            $escaped = str_replace("'", "''", $value);
                            $rowValues[] = "'" . $escaped . "'";
                        }
                    }
                    $values[] = '(' . implode(', ', $rowValues) . ')';
                }

                $output .= implode(",\n", $values) . ";\n\n";
            }
        }
    }

    file_put_contents('sqlite_export.sql', $output);
    echo "Export complete! Data saved to sqlite_export.sql\n";
    echo "Total tables: " . count($tables) . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
