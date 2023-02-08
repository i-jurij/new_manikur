<?php
namespace App\Lib\Traits;

trait Clear_file
{
    /**
     * @param string $log_folder - path to logs folder
     * @param int $keep_num_lines - the number of lines to save, starting from the end of the file
     * @return bool
     */
    public static function clearfile($log_folder, $keep_num_lines = 0) {
        // clear log file if filetime > 1 week, but leave the last seven lines
        if (file_exists($log_folder)) {
            foreach (new \DirectoryIterator($log_folder) as $fileInfo) {
                if ($fileInfo->isDot() or $fileInfo->isDir()) {
                continue;
                }
                if ($fileInfo->isFile()) {
                    $lines = file($fileInfo->getPathname()); // reads the file into an array by line
                    $keep = (!empty($keep_num_lines)) ? array_slice($lines,-$keep_num_lines) : '';
                    if (file_put_contents($fileInfo->getPathname(), $keep) === false) {
                        return false;
                    } else {
                        return true;
                    }
                }
            }
        }
    }
}
?>