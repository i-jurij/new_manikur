<?php
namespace App\Lib\Traits;

trait Delete_files
{
  public static function del_files_in_dir(string $dir, bool $recursive = true) {
    $mes = '';
    if (!is_readable($dir)) {
      $mes .= 'ERROR! Not readable "'.$dir.'".';
      return $mes;
    }
    $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
      if (is_dir($dir.DIRECTORY_SEPARATOR.$file) && $recursive === true) {
          self::del_files_in_dir($dir.DIRECTORY_SEPARATOR.$file, true);
      } else {
        if (!unlink($dir.DIRECTORY_SEPARATOR.$file)) {
          $mes .= 'ERROR! Not unlink "'.$dir/$file.'".';
          return $mes;
        } else {
          return true;
        }
      }
    }
  }
  /**
  * delete a file or files
  *
  * @param string $path2file
  * @return true or string (check if $this === true)
  */
  public static function del_file(string $path2file) {
    $mes = '';
    if (is_string($path2file)) {
      //$path2file = realpath($path2file);
      if (file_exists($path2file)) {
        if (is_writable($path2file)) {
          if (unlink($path2file)) {
            return true;
          } else {
            $mes .= 'ERROR! Not unlink "'.$path2file.'".';
            return $mes;
          }
        } else {
          $mes .= 'ERROR! File "'.$path2file.'" is not writable.';
          return $mes;
        }
      } else {
        $mes .= 'WARNING! File "'.$path2file.'" is not exists.';
        return $mes;
      }
    } else {
      $mes .= 'ERROR! Input for $this->del_file($path2file) must be sring.';
      return $mes;
    }
  }

  function del_empty_dir($dir) {
    if ( [] === ( array_diff(scandir($dir), array('.', '..')) ) ) {
      if (rmdir($dir)) {
          return true;
      } else {
        return false;
      }
    }
  }

  /**
  * delete a file or directory
  * automatically traversing directories if needed.
  * PS: has not been tested with self-referencing symlink shenanigans, that might cause a infinite recursion, i don't know.
  *
  * @param string $cmd
  * @throws \RuntimeException if unlink fails
  * @throws \RuntimeException if rmdir fails
  * @return void
  */
  public static function unlinkAllRecursive(string $path, bool $verbose = true): void
  {
      if (!is_readable($path)) {
          return;
      }
      if (is_file($path)) {
          if ($verbose) {
              echo "unlink: {$path}\n";
          }
          if (!unlink($path)) {
              throw new \RuntimeException("Failed to unlink {$path}: " . var_export(error_get_last(), true));
          }
          return;
      }
      $foldersToDelete = array();
      $filesToDelete = array();
      // we should scan the entire directory before traversing deeper, to not have open handles to each directory:
      // on very large director trees you can actually get OS-errors if you have too many open directory handles.
      foreach (new \DirectoryIterator($path) as $fileInfo) {
          if ($fileInfo->isDot()) {
              continue;
          }
          if ($fileInfo->isDir()) {
              $foldersToDelete[] = $fileInfo->getRealPath();
          } else {
              $filesToDelete[] = $fileInfo->getRealPath();
          }
      }
      unset($fileInfo); // free file handle
      foreach ($foldersToDelete as $folder) {
          self::unlinkAllRecursive($folder, $verbose);
      }
      foreach ($filesToDelete as $file) {
          if ($verbose) {
              echo "unlink: {$file}\n";
          }
          if (!unlink($file)) {
              throw new \RuntimeException("Failed to unlink {$file}: " . var_export(error_get_last(), true));
          }
      }
      if ($verbose) {
          echo "rmdir: {$path}\n";
      }
      if (!rmdir($path)) {
          throw new \RuntimeException("Failed to rmdir {$path}: " . var_export(error_get_last(), true));
      }
  }
}
?>
