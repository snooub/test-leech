<?php

// them 1 cho count
$file = fopen("data/count.txt", "w");
fwrite($file, 0);
fclose($file);

echo "0kie";
echo "<p><a href='run.php'>Run now</a></p>";