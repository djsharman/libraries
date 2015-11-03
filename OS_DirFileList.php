<?php 


/**
* get recursive directory listing
*/

namespace djsharman\libraries;
class OS_DirFileList {
    private $recursive = true;
    private $directory;
    private $ignore_list;
    private $file_list;      //array of the files found
    private $file_count;
    private $include_list;

    public function __construct($directory, $ignore_list, $include_list) {
        $this->directory = $directory;
        $this->file_list = array();
        $this->ignore_list = array("..", ".") + $ignore_list;
        $this->file_count = 0;
        $this->include_list = $include_list;
    }

    public static function &getFileList($directory, $ignore_list=array(), $recursive=true, $include_list=null ) {
        $RDFL = new self($directory, $ignore_list, $include_list);
        $RDFL->setRecursive($recursive);
        $RDFL->processDir($directory);
        return $RDFL->file_list;

    }

    private function processDir($path) {

        $ignore = $this->ignore_list;
        $include = $this->include_list;

        $dh = @opendir( $path );
		if($dh !== false) {
	        // Loop through the directory
	        while( false !== ( $file = readdir( $dh ) ) ){
	            // Check that this file is not to be ignored
	            if( !in_array( $file, $ignore ) ){
	                // is it a directory, if so we need to keep reading down
	                if( is_dir( "$path/$file" ) ){
	                    if($this->recursive == true) {
	                        // recursively process this dir
	                        $this->processDir( "$path/$file" );
	                    }
	                } else {
	                	// process file to see if its extension is in the include list.
	                	if(!empty($include)) {
		                	$path_parts = pathinfo($file);
		                	$file_ext = $path_parts['extension'];
		                	if(in_array($file_ext, $include)) {
		                		$this->addFileToList("$path/$file");
		                	}
	                	} else {
	                    	$this->addFileToList("$path/$file");
	                	}
	                }
	            }
	        }
	        closedir( $dh );
		} else {
			// directory is incorrect
			$thisL->file_list = false;	
		}
    }

    private function addFileToList($file) {
        $this->file_list[$this->file_count] = $file;
        $this->file_count++;
    }
    
    public function setRecursive($recursive) { 
        $this->recursive = $recursive;
    }
    
    public function getRecursive() {
        return $this->recursive;
    } 

    public function setFile_list($file_list) { 
        $this->file_list = $file_list;
    }
    
    public function getFile_list() {
        return $this->file_list;
    }  

    public function setFile_count($file_count) { 
        $this->file_count = $file_count;
    }
    
    public function getFile_count() {
        return $this->file_count;
    }  
    
    public function setIncludeList($include_list) {
    	$this->include_list = $include_list;
    }
    
    public function getIncludeList() {
    	return $this->include_list;
    }
    
}
?>