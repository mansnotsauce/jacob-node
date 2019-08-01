<?php

class files {
	# 6/15/2018 - safe_path()
	# Protect against directory traversal
	# Gets rid of ../ and ./ and checks if this filename is within a safe zone
	# 
	# There are vulnerabilities.
	# 
	# Caveat: Uses realpath. So file must exist. Should fix this later?
	# 
	function safe_path($safe_root, $filename) {
		# Get the real path of this file
		$name = realpath($filename);

		# The safe root directory. 
		$safe_root = realpath($safe_root);

		# Check whether realpath() result is still inside the safe root
		if (substr($name, 0, strlen($safe_root)) == $safe_root) {
			return true;
		}
		else {
			return false;
		}
	}
}

// Default class
class file_uploader extends uploader {

	function move() {
		$this->move_result = false;

		# General uploader will move file to destination temporarily
		if (!$move_result = parent::move()) {
			return $move_result; # There was an error. Exit and pass it through
		}

		$this->move_result = array(
			'filename' => $this->dest_filename,
			'source_file' => $move_result
		);

		return $this->move_result;
	}
}


/*
 //Instead of a static class we're doing this
 $file = new uploader('filename');
 OR
 $file = new uploader($_FILES['filename']);
 //OR
 //$file = new uploader(array('filegroup', 'filename'));

 // Setup rules
 $file->dest_path = PATH_FILES . 'temp/';
 $file->dest_file = 'filename.jpg'; // By default this is the filename in $_FILES;
 $file->ext_filter_mode = 'disallow';
 $file->ext_filter = array('asp', 'php', 'py', 'pl', 'plx', 'exe', 'com', 'php3', 'php4', 'php5', 'php6', 'htaccess');

 // Move the file
 $file->move();
*/
class uploader {
	public $orig_filename; // Original filename that we uploaded with..
	public $dest_path; // Make sure it ends with a trailing slash
	public $dest_filename; // Separated from path so we can process it
	public $last_error;
	public $ext_filter_mode; // "allow" or "disallow"
	public $ext_filter;
	public $overwrite_duplicate; // When false, it uses unconflict_filename
	public $move_result; // Result of the last move() call

	protected $ready_for_move = false; // If the $_FILES object was valid, then we're ready to move()...

	public $dest_extension; // This is created after move() is called
	public $full_dest_path; // This is created after move() is called
	public $files_obj;

	function set_defaults() {

		# By default this is false. Only happens with error
		$this->files_obj = false;
		$this->dest_path = PATH_FILES . 'misc/';
		$this->overwrite_duplicate = true;
		$this->last_error = '';
		$this->ext_filter_mode = 'disallow';
		$this->ext_filter = array('asp', 'php', 'py', 'pl', 'plx', 'exe', 'com', 'php3', 'php4', 'php5', 'php6', 'htaccess');
	}

	# Setup our defaults
	public function __construct($name) {
		# Setup defaults
		$this->set_defaults();

		# Try and acquire the file
		if (is_string($name)) {
			if (isset($_FILES[$name])) {
				$this->files_obj = $_FILES[$name];
			}
			else {
				#$this->last_error = '$_FILES[' . $name . '] does not exist.';
				return false;
			}
		}
		elseif (is_null($name)) {
			# No param passed. Don't throw error. Same as $name='' seen above ^^
			return false;
		}
		else {

			# Verify that it is valid.
			if (isset($name['tmp_name']) && isset($name['name'])) {
				# Passed the $_FILES object directly.
				$this->files_obj = $name;
			}
			else {
				$this->last_error = 'Invalid $_FILES[] object';
				return false;
			}
		}

		# Check if there was an error during upload (before PHP is triggered)
		if (isset($this->files_obj['error']) && $this->files_obj['error'] > 0) {
			$this->last_error = 'PHP File Upload Error #' . $this->files_obj['error'] . ' - ' . $this->file_error_message($this->files_obj['error']);
			return false;
		}

		$this->ready_for_move = true;

		# For testing file extensions
		$this->orig_filename = $this->files_obj['name'];
	}
	protected function is_extension_allowed() {
		# 3/4/2017 - Crap. Are we testing the dest_filename or $this->files_obj['name']? 
		# I can't remember which is right! I think it's dest_filename based on the move() function

		# 10/9/2018 - Test $orig_filename AND $dest_filename
		#$extension = end(explode('.', $this->dest_filename));

		$ext_src = strtolower(end(explode('.', $this->orig_filename)));
		$ext_dst = strtolower(end(explode('.', $this->dest_filename)));

		# Used internally only
		$this->dest_extension = $extension;

		#echo "<pre>" . print_r($this); echo "</pre>";

		if ($this->ext_filter_mode == 'allow') {
			if (in_array($ext_src, $this->ext_filter) && in_array($ext_dst, $this->ext_filter)) { # Find out if this *.ext is in our filter array()
				return true;
			}
		}
		elseif ($this->ext_filter_mode == 'disallow') {
			if (!in_array($ext_src, $this->ext_filter) || !in_array($ext_dst, $this->ext_filter)) { # Find out if this *.ext is in our filter array()
				return true;
			}
		}

		return false;
	}

	# Move the file to its final destination
	protected function move() {

		$this->move_result = false; # By default, file didn't move....

		# Nothing to move....
		if ($this->ready_for_move != true) {
			return false;
		}

		# Copy file name if none have been specified
		if ($this->dest_filename == '') {
			$this->dest_filename = $this->files_obj['name'];
		}

		# This should prevent most attempts at filename attacks
		# HEY! You shouldn't be accepting user filenames anyway.
		if (!$this->check_file_uploaded_name($this->dest_filename)) {
			$this->last_error = 'Invalid filename: ' . $this->dest_filename;
			return false;
		}

		# Make sure they aren't uploading exe's or whatever
		if (!$this->is_extension_allowed()) {
			$this->last_error = 'Invalid filename extension: "' . $this->dest_filename . '"';
			return false;
		}

		try {

			# Make sure the destination folder exists
			$this->create_folder($this->dest_path);

			# Make sure name conflicts are resolved by overwriting or renaming
			if (!$this->overwrite_duplicate) {
				$this->dest_filename = $this->unconflict_filename($this->dest_path, $this->dest_filename);
			}

			# Path + Filename for writing
			$this->full_dest_path = $this->dest_path . $this->dest_filename;

			# Move the uploaded file into a temporary folder
			move_uploaded_file($this->files_obj['tmp_name'], $this->full_dest_path);

			# If the file exists then it has been uploaded
			if (file_exists($this->full_dest_path)) {
				# Everything is cool. Return the filename. Remove the root path first
				if (substr($this->full_dest_path, 0, strlen(PATH_ROOT)) == PATH_ROOT) {
					$this->move_result = substr($this->full_dest_path, strlen(PATH_ROOT));
					return $this->move_result;
				}
				return $this->full_dest_path;
			}
			else {
				$this->last_error = 'File was not uploaded.';
				return false;
			}
		}
		catch (Exception $e) {

			$this->last_error = $e;

			# TO DO: Setup last error here
			return false;
		}
	}

	/*
	# 6/15/2018 - This is a shortcut. Used to be like this: 


	$new_file = $pic->move();
	if (is_array($new_file) && isset($new_file['source_file'])) { 
		....
	}

	Now it's like this
	$pic->move();
	if ($pic->file_moved()) { 
		....
	}
	*/
	public function file_moved() {
		if (is_array($this->move_result) && isset($this->move_result['source_file'])) { 
			return true;
		}
		else {
			return false;
		}
	}


	/**
	* Check $_FILES[][name]
	*
	* @param (string) $filename - Uploaded file name.
	* @author Yousef Ismaeil Cliprz
	* See https://secure.php.net/manual/en/function.move-uploaded-file.php
	*/
	protected function check_file_uploaded_name($filename) {

		# Replace spaces so we aren't chekcing aginst those
		$filename = str_replace(' ', '', $filename);

		# Blank filenames are automatically invalid
		if (trim($filename) == '') {
			return false;
		}

		return (bool)((preg_match("`^[-0-9A-Z_\.]+$`i", $filename)) ? true : false);
	}

	# Create folder and all its subfolder parts
	protected function create_folder($path, $attr=0755) {
		if (!is_dir($path)) {
			# Create the folder
			mkdir($path, $attr, true);
		}
	}

	# If two files are named file.jpg then it will rename the new one to file_1.jpg
	# TO DO: Detect the _# and increment similar filenames
	protected function unconflict_filename($path, $file) {
		$filename = $path . $file;
		$index = 0;

		if (file_exists($filename)) {
			$pathinfo = pathinfo($file);
			do {
				$new_file = $pathinfo['filename'] . '_' . ++$index . '.' . $pathinfo['extension'];

				$filename = $path . $new_file;

				# Directory limits are at 1000 so this should never happen
				if ($index > 1050) { break; }
			} while (file_exists($filename));

			return $new_file;
		}
		else {

			# Default file does not exist
			return $file;
		}
	}

	protected function file_error_message($code) {
		switch ($code) { 
            case UPLOAD_ERR_INI_SIZE: 
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini"; 
                break; 
            case UPLOAD_ERR_FORM_SIZE: 
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form"; 
                break; 
            case UPLOAD_ERR_PARTIAL: 
                $message = "The uploaded file was only partially uploaded"; 
                break; 
            case UPLOAD_ERR_NO_FILE: 
                $message = "No file was uploaded"; 
                break; 
            case UPLOAD_ERR_NO_TMP_DIR: 
                $message = "Missing a temporary folder"; 
                break; 
            case UPLOAD_ERR_CANT_WRITE: 
                $message = "Failed to write file to disk"; 
                break; 
            case UPLOAD_ERR_EXTENSION: 
                $message = "File upload stopped by extension"; 
                break; 

            default: 
                $message = "Unknown upload error"; 
                break; 
        } 	
        return $message;
	}
}














