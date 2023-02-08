<?php
namespace App\Lib\Traits;

trait Clear_logs
{
    /**
     * @param string $log_folder - path to logs folder
     * @param int $keep_num_lines - number of rows to keep
     */
    public static function clear_logs($log_folder, $keep_num_lines) {
        // clear log file if filetime > 1 week, but leave the last seven lines
        if (file_exists($log_folder)) {
            foreach (new \DirectoryIterator($log_folder) as $fileInfo) {
                if ($fileInfo->isDot() or $fileInfo->isDir()) {
                continue;
                }
                if ($fileInfo->isFile()) {
                    $lines = file($fileInfo->getPathname()); // reads the file into an array by line
                    $keep = array_slice($lines,-40); // keep the last 40 elements of the array start at end
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