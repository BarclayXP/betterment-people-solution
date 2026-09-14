<?php
// Router for PHP's built-in web server, used by start-site.bat:
//   php -S 127.0.0.1:8081 router.php
// It blocks private folders and hidden files, then lets PHP serve everything else as normal.
// (On Apache hosting the .htaccess files in those folders do the same job.)

function router_is_blocked(string $path): bool
{
    // Null bytes and colons (Windows alternate data streams) have no place in a page address.
    if (strpbrk($path, "\0:") !== false) {
        return true;
    }

    $segments = [];
    foreach (preg_split('#[\\\\/]+#', $path, -1, PREG_SPLIT_NO_EMPTY) as $segment) {
        if ($segment === '.') {
            continue;
        }
        if ($segment === '..') {
            array_pop($segments);
            continue;
        }
        if ($segment[0] === '.') {
            return true; // Hidden files and folders such as .htaccess and .venv.
        }
        // Windows ignores trailing dots and spaces, so "storage." opens "storage".
        $segments[] = rtrim($segment, '. ');
    }

    if ($segments === []) {
        return false;
    }

    $root = realpath(__DIR__);
    $first = strtolower($segments[0]);
    $private = ['includes', 'partials', 'storage', 'router.php', 'start-site.bat'];
    if (in_array($first, $private, true)) {
        return true;
    }

    // Catch other spellings Windows accepts for the same folder, such as short names (STORAG~1).
    $firstReal = realpath($root . DIRECTORY_SEPARATOR . $segments[0]);
    if ($firstReal !== false) {
        foreach ($private as $name) {
            $privateReal = realpath($root . DIRECTORY_SEPARATOR . $name);
            if ($privateReal !== false && strcasecmp($firstReal, $privateReal) === 0) {
                return true;
            }
        }
    }

    return false;
}

// Split off the query string by hand: parse_url() misreads paths that start with "//".
$requestPath = rawurldecode(explode('?', $_SERVER['REQUEST_URI'], 2)[0]);
if (router_is_blocked($requestPath)) {
    http_response_code(404);
    echo 'Not found';
    return true;
}

return false;
