<?PHP

header('Content-type: text/plain; charset=utf8', true);

$localBinary = "./bin/ESP32.bin"; // Path to the expected binary
$latestVersion = "2.0";

function check_header($name, $value = false) {// Check if the header is present
    if(!isset($_SERVER[$name])) {
        return false;
    }
    if($value && $_SERVER[$name] != $value) {
        return false;
    }
    return true;
}

function sendFile($path) {
    header($_SERVER["SERVER_PROTOCOL"].' 200 OK', true, 200);
    header('Content-Type: application/octet-stream', true);
    header('Content-Disposition: attachment; filename='.basename($path));
    header('Content-Length: '.filesize($path), true);
    header('x-MD5: '.md5_file($path), true);
    readfile($path);
}

if(!check_header('HTTP_USER_AGENT', 'ESP32-http-Update')) {
    header($_SERVER["SERVER_PROTOCOL"].' 403 Forbidden', true, 403);
    echo "only for ESP32 updater! Wrong user agent.\n";
    exit();
}

if(
    !check_header('HTTP_X_ESP32_STA_MAC') ||
    !check_header('HTTP_X_ESP32_AP_MAC') ||
    !check_header('HTTP_X_ESP32_FREE_SPACE') ||
    !check_header('HTTP_X_ESP32_SKETCH_SIZE') ||
    !check_header('HTTP_X_ESP32_SKETCH_MD5') ||
    !check_header('HTTP_X_ESP32_CHIP_SIZE') ||
    !check_header('HTTP_X_ESP32_SDK_VERSION') ||
    !check_header('HTTP_X_ESP32_VERSION')
) {
    header($_SERVER["SERVER_PROTOCOL"].' 403 Forbidden', true, 403);
    echo "only for ESP32 updater! Wrong header.\n";
    exit();
}

//sendFile($localBinary);

if($latestVersion != $_SERVER['HTTP_X_ESP32_VERSION']){// If client not running latest version
    sendFile($localBinary);
} else {
    header($_SERVER["SERVER_PROTOCOL"].' 304 Not Modified', true, 304); // If client already running latest version
}
