<?php
function loadJsonData($file) {
    $jsonFile = '../' . $file . '.json';
    return json_decode(file_get_contents($jsonFile), true);
}

function saveJsonData($file, $data) {
    $jsonFile =  '../' . $file . '.json';
    file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
}

function isActive($page) {
    $current_page = basename($_SERVER['PHP_SELF']);
    return $current_page === $page ? 'active' : '';
}

function getProgress($contentType, $contentId) {
    $progress = loadJsonData('progress');
    $key = $contentType . '_' . $contentId;
    return isset($progress[$key]) ? $progress[$key] : 0;
}

function updateProgress($contentType, $contentId, $newProgress) {
    $progress = loadJsonData('progress');
    $key = $contentType . '_' . $contentId;
    $progress[$key] = $newProgress;
    saveJsonData('progress', $progress);
}
?>