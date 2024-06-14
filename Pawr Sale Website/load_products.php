<?php
$cache_file = __DIR__ . '/cache/products_cache.json';

// Serve cached data immediately
if (file_exists($cache_file)) {
    $data = json_decode(file_get_contents($cache_file), true);
    echo json_encode($data);
} else {
    // Create an empty cache file if it doesn't exist
    $data = ['products' => []];
    file_put_contents($cache_file, json_encode($data));
    echo json_encode($data);
}
?>
