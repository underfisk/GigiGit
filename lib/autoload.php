<?php
/**
 * This class is not being used
 */
final class Autoload
{
    /**
     * Initializes the required files inside the base github path provided
     * @param libpath   Receives the path for library files,folders
     */
    public final static function Initialize($libpath)
    {
        //Base directory
        $base_directory = __DIR__ . '/';
        //Lib directory
        $lib = $base_directory . $libpath;

        //is a valid path?
        if (!is_dir("{$lib}"))
            exit('</br> Directory given does not exist and couldn\'t load GigiGit');

        //get base folder files
        $libBaseFiles = self::GetFolderFiles($lib);
        
        //require them
        foreach($libBaseFiles as $f)
            require_once $f;

        //get base folders
        $libBaseFolders = self::GetFolders($lib);

        //Require the folders files and it's sub folders and sub folders files
        foreach($libBaseFolders as $folder)
        {
            $folders = self::GetFolders($folder);
            if (sizeof($folders) > 0 )
            {
                foreach($folders as $f)
                {
                    $files = self::GetFolderFiles($f);
                    if (sizeof($files) > 0)
                    {
                        foreach($files as $file)
                        require_once $file;
                    }
                }
            }

            $files = self::GetFolderFiles($folder);
            if (sizeof($files) > 0 )
            {
                foreach($files as $file)
                    require_once $file;
            }
        }

        
    }

    /**
     * Returns an array filtered of folders path
     */
    private final static function GetFolders($folder)
    {
        return array_filter(glob("{$folder}/*"),'is_dir');
    }

    /**
     * Returns an array filtered of folders files path
     */
    private final static function GetFolderFiles($folder)
    {
        return array_filter(glob("{$folder}/*.php"),'is_file');
    }
}