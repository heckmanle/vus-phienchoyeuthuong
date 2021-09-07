<?php

namespace SME\Inc;

use \Exception;

class UploadFile{

	protected $destination;
	protected $file;
	protected $uploads;
	private $mimes = [];
	private $allow_mimes = [
		// Image formats.
		'jpg|jpeg|jpe' => 'image/jpeg',
		'gif' => 'image/gif',
		'png' => 'image/png',
		'bmp' => 'image/bmp',
		'tiff|tif' => 'image/tiff',
		'ico' => 'image/x-icon',
		// Text formats.
		'csv' => 'text/csv',
		// custom ppt pptx
		'ppt' => 'application/vnd.ms-powerpoint,',
		'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
		// MS Office formats.
		'doc' => 'application/msword',
		'xla|xls|xlt|xlw' => 'application/vnd.ms-excel',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'docm' => 'application/vnd.ms-word.document.macroEnabled.12',
		'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
		'dotm' => 'application/vnd.ms-word.template.macroEnabled.12',
		'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'xlsm' => 'application/vnd.ms-excel.sheet.macroEnabled.12',
		'xlsb' => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
		'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
		'xltm' => 'application/vnd.ms-excel.template.macroEnabled.12',
		'xlam' => 'application/vnd.ms-excel.addin.macroEnabled.12',
	];

	public function __construct($file, $mimes)
	{
		$this->file = $file;
		$uploads = wp_upload_dir();
		$this->uploads = $uploads;
		$this->destination = $uploads['basedir'];
		$this->mimes = $mimes;
	}

	public function save_file(){
		$filetype     = wp_check_filetype_and_ext( $this->file['tmp_name'], $this->file['name'], $this->mimes );
		$ext             = empty( $filetype['ext'] ) ? '' : $filetype['ext'];
		$type            = empty( $filetype['type'] ) ? '' : $filetype['type'];
		$proper_filename = empty( $filetype['proper_filename'] ) ? '' : $filetype['proper_filename'];
		if ( $proper_filename ) {
			$file['name'] = $proper_filename;
		}
		if ( ( ! $type || ! $ext ) ) {
			return new \WP_Error(401, __( 'Tập tin không đúng định dạng' ) );
		}
		$filename = wp_unique_filename( $this->uploads['path'], $this->file['name'], null );
		$new_file = $this->uploads['path'] . "/$filename";
		$move_new_file = @move_uploaded_file( $this->file['tmp_name'], $new_file );
		if ( false === $move_new_file ) {
			return new \WP_Error(401, __('Tải tập tin lên không thành công'));
		}
		return $this->uploads['url'] . "/$filename";
	}

}
