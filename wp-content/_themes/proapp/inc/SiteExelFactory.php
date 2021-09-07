<?php
/**
 * Created by PhpStorm.
 * User: BCM_dev
 * Date: 11/22/19
 * Time: 11:34 AM
 */

namespace SME\Inc;

class SiteExelFactory {
    private $file_template = THEME_DIR . '/template/exports/template.xlsx';
    private static $EXPORT_DIRECTORY = THEME_DIR . '/assets/exports/';
    private $identify;
    protected $config = [
        'logo' => true,
        'write_title' => true,
	    'style_from_file_template' => false,
    ];
    public $objPHPExcel;
    private $filename;
    private $file_format = '';
    private $file;
    public $sheet;
    private $max_row = 7;
    private $max_column = 2;
    private $errors = [];
    private $title;

    protected $style_header = [];

    /**
     * SiteExelFactory constructor.
     * @param $title
     * @param string $file_template
     * @param array $config
     * @param array $settings (output_directory, max_row, max_column, style_header)
     */
    public function __construct(
        $title,
        $file_template = '',
        $config = [],
        $settings = []
    ) {
        if( !empty($config) ){
            $this->set_config($config);
        }

        $this->set_settings($settings);

        $files = glob(self::$EXPORT_DIRECTORY . '/*');
        foreach($files as $file){
            if( is_file($file) ){
                unlink($file);
            }
        }

        if( !empty($file_template) ) {
	        $this->file_template = $file_template;
        }
        $this->errors = new \WP_Error();
	    $file_template_explode = explode('.', $this->file_template);
	    $this->file_format = end($file_template_explode);
        $this->title = $title;
        $this->set_file_name($title);
        $fileCopy = self::$EXPORT_DIRECTORY . $this->filename .  ".{$this->file_format}";
        @copy($this->file_template, $fileCopy);
        @chmod($fileCopy, 0777);
        if ( is_file($fileCopy) ) {
            $this->file = $fileCopy;

            try {
                $this->identify = \PHPExcel_IOFactory::identify($fileCopy);
                $this->objPHPExcel = \PHPExcel_IOFactory::load($fileCopy);
                $this->sheet = $this->objPHPExcel->getActiveSheet();
                if(!$this->config['style_from_file_template']) {
	                $this->set_default_style([
		                'font' => [
			                'size' => 12
		                ],
		                'alignment' => [
			                'horizontal' => "center",
			                'vertical' => "center",
		                ],
	                ]);
                }
            }
            catch (\Exception $e) {
	            $this->errors->add(401, $e->getMessage());
            }
        }
    }

    function set_export_directory($directory = ''){
        if( !empty($directory) )
            $directory .= '/';
        $upload = wp_upload_dir();
        $output_folder = "{$upload['basedir']}/exports/" . $directory;
        if (!is_dir($output_folder)) {
            mkdir($output_folder, 0777, true);
        }

        self::$EXPORT_DIRECTORY = $output_folder;
    }

    function set_settings($settings){
        $output_directory = isset($settings['output_directory']) ? trim($settings['output_directory']) : '';
        $max_row = isset($settings['max_row']) ? (int)$settings['max_row'] : $this->max_row;
        $max_column = isset($settings['max_column']) ? (int)$settings['max_column'] : $this->max_column;
        $style_header = isset($settings['style_header']) ? $settings['style_header'] : [];
        $this->set_export_directory($output_directory);
        $this->set_max_row($max_row);
        $this->set_max_column($max_column);
	    if(!$this->config['style_from_file_template']) {
		    $this->set_style_header($style_header);
	    }
    }

    function set_style_header($style){
        if( !empty($style) ){
            $this->style_header = $style;
        }else{
            $this->style_header = [
                'fill' => [
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'fffff'],
                ],
                "borders" => [
                    "allborders" => [
                        "style" => \PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array(
                            'rgb' => '000000'
                        )
                    ],
                ],
                'font' =>[
                   /* 'bold' => true,*/
                    'size' => 14,
                    'name' => 'Arial',
                ],
                'alignment' => [
                    'horizontal' => "center",
                    'vertical' => "center",
                ],
            ];
        }
    }

    function set_max_column($number){
        if( empty($number) )
            $number = 1;
        $this->max_column = (int)$number;
    }

    private function set_file_name($title) {
        $this->filename = $title . " " . current_time('d') . '-' . current_time('m') . '-' . current_time('Y');
    }

    function set_config($args = []){
        $config = $this->config;
        $args = array_filter($args, function($item) use ($config){ return in_array($item, $config); }, ARRAY_FILTER_USE_KEY);
        $this->config = wp_parse_args($args, $this->config);
    }

    function set_max_row($row) {
        $this->max_row = $row;
    }

    function set_list_data_validation($coordinate, $data, $title, $data_link = false) {
        $objValidation2 = $this->sheet->getCell($coordinate)->getDataValidation();
        $objValidation2->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
        $objValidation2->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
        $objValidation2->setAllowBlank(false);
        $objValidation2->setShowInputMessage(true);
        $objValidation2->setShowDropDown(true);
        $objValidation2->setPromptTitle($title);
        $objValidation2->setPrompt('Chọn một giá trị trong danh sách bên dưới');
        $objValidation2->setErrorTitle('Lỗi nhập');
        $objValidation2->setError('Giá trị không nằm trong danh sách');
        if( !$data_link ) {
	        $objValidation2->setFormula1('"' . $data . '"');
        }else {
	        $objValidation2->setFormula1($data);
        }
    }

    function get_max_row() {
        return $this->max_row;
    }

    function get_max_column() {
        return $this->max_column;
    }

    public function write_heading($text, $style = [], $height = 32) {
        try {
	        if(!$this->config['style_from_file_template']) {
	        	$style_default = [
			        'font' => [
				        'bold' => true,
				        'size' => 14,
				        'name' => 'Arial',
			        ],
			        'alignment' => [
				        'horizontal' => "left",
				        'vertical' => "center",
			        ]
		        ];
	        	$style = wp_parse_args($style, $style_default);
	        }

	        if( !empty($style) ){
		        $this->sheet->getStyleByColumnAndRow(0, $this->max_row)->applyFromArray($style);
	        }

            $this->sheet->mergeCellsByColumnAndRow(0, $this->max_row ,$this->max_column, $this->max_row );
            $this->sheet->getRowDimension($this->max_row)->setRowHeight( $height );
            $this->sheet->setCellValueByColumnAndRow(0, $this->max_row, $text);
            $this->max_row ++;
        }
        catch (\Exception $e) {
	        $this->errors->add(401, $e->getMessage());
        }
    }

    public function increse_max_row( $increase ) {
        $this->max_row += $increase;
    }

    public function write_title($title = '', $style = [], $column = 0, $row = 5) {
        try {
        	if( empty($this->title) ){
        		$title = $this->title;
	        }
            $this->sheet->mergeCellsByColumnAndRow($column, $row, $this->max_column, $row);
	        if(!$this->config['style_from_file_template']) {
	        	if( empty($style) ){
	        		$style = [
				        'font' => [
					        'bold' => true,
					        'size' => 14,
					        'color' => array(
						        'rgb' => 'FF0000'
					        ),
					        'name' => 'Arial',
				        ],
				        'alignment' => [
					        'horizontal' => "left",
					        'vertical' => "center",
				        ],

			        ];
		        }
		        $this->sheet->getStyleByColumnAndRow($column, $row)->applyFromArray($style);
		        $this->sheet->getRowDimension($row)->setRowHeight(25);
	        }
            $this->sheet->setCellValueByColumnAndRow($column, $row, $title);
        }
        catch (\Exception $e) {
	        $this->errors->add(401, $e->getMessage());
        }
    }

    public function write_table($header, $body, $heading = "", $style_body = []) {
        if ( !empty( $heading ) ) {
            $this->write_heading( $heading );
        }

        $this->write_header( $header, 0, $this->max_row);
        $this->max_row ++;
        $current_row = $this->max_row;
        $max_column = 0;

        foreach ( $body['records'] as $key => $record ) {
            if ( !empty($body["preprocess_item_function"]) ) {
                if ( !empty($body["addition_data"] ) ) {
                    $record = call_user_func( $body["preprocess_item_function"], $record, $key, $body["addition_data"] );
                }
                else {
                    $record = call_user_func( $body["preprocess_item_function"], $record, $key );
                }
            }

            $this->write_record($record, $header, $max_column);
            $this->max_row ++;
        }
	    if(!$this->config['style_from_file_template']) {
		    if (empty($style_body)) {
			    $style_body = [
				    "borders" => [
					    "allborders" => [
						    "style" => \PHPExcel_Style_Border::BORDER_THIN,
						    'color' => array(
							    'rgb' => '000000'
						    )
					    ],
				    ],
				    'font' => [
					    'size' => 12,
					    'name' => 'Arial',
				    ],
				    'alignment' => [
					    'horizontal' => "center",
					    'vertical' => "center",
				    ],
			    ];
		    }
	    }
	    if( !empty($style_body) ){
		    $this->set_style_for_cell_range(0, $current_row, $max_column - 1, $this->max_row - 1, $style_body);
	    }
    }

    private function write_record( $record, $header,  &$max_column,  &$current_column = 0 ) {

    	if(empty($header) ) {
		    $header = $record;
    	}

	    foreach ($header as $key => $item) {
		    if (isset($item['children']) && !empty($item["children"])) {
			    $this->write_record($record[$key], $item["children"], $max_column, $current_column);
		    } else {
			    $pDataType = isset($item['data-type']) ? $item['data-type'] : \PHPExcel_Cell_DataType::TYPE_STRING;
			    $this->sheet->setCellValueExplicitByColumnAndRow($current_column, $this->max_row, $record[$key], $pDataType);
			    $current_column++;

			    if ($max_column < $current_column) {
				    $max_column = $current_column;
			    }
		    }
	    }
    }

    public function write_header( $header, $start_column = 0, $start_row = 7 ) {
        $current_row = $start_row;
        $current_column = $start_column;

        foreach ( $header as $item) {
            $check = false;
            $end_row = $current_row;
            $end_column = $current_column;

            if (  !empty( $item["width"] ) ) {
                if( $item['width'] == 'auto' ){
                    $this->sheet->getColumnDimensionByColumn($current_column)->setAutoSize(true);
                }else{
                    $this->sheet->getColumnDimensionByColumn($current_column)->setWidth( $item["width"] );
                }

            }

            if (  !empty( $item["rowspan"] ) ) {
                $end_row = $current_row + $item["rowspan"] - 1;
                $check = true;
            }

            if (  !empty( $item["colspan"] ) ) {
                $end_column = $current_column + $item["colspan"] - 1;
                $check = true;
            }

            if ($check) {
                $this->merge_cells_by_column_and_row($current_column, $current_row, $end_column, $end_row);
            }

            $this->sheet->setCellValueByColumnAndRow($current_column, $current_row, $item["value"]);

            if ( !empty( $item["children"] ) ) {
                $this->write_header($item["children"], $current_column, $end_row + 1);
            }

            $current_column = $end_column + 1;

            if ($this->max_row < $end_row + 1) {
                $this->max_row  = $end_row;
            }
        }

        $this->max_column = $current_column - 1;
        $this->set_style_for_cell_range($start_column, $current_row, $current_column - 1, $this->max_row, $this->style_header);

    }

    private function merge_cells_by_column_and_row($start_column, $start_row, $end_column, $end_row) {
        try {
            $this->sheet->mergeCellsByColumnAndRow($start_column, $start_row, $end_column, $end_row);
        }
        catch (\Exception $e) {
	        $this->errors->add(401, $e->getMessage());
        }
    }

    public function set_style_for_cell_range($start_column, $start_row, $end_column, $end_row, $styles ) {
        try {
            $this->sheet->getStyleByColumnAndRow($start_column, $start_row, $end_column, $end_row)->applyFromArray($styles);
        }
        catch (\Exception $e) {
	        $this->errors->add(401, $e->getMessage());
        }
    }

    private function set_default_style( $style ) {
       try {
           $this->sheet->getDefaultStyle()->applyFromArray($style);
       }
       catch (\Exception $e) {
	       $this->errors->add(401, $e->getMessage());
       }
    }

    public function draw_logo($coordinates = 'A1', $style = []) {
       try {
	       preg_match('/^([\S+]?\D+|[\S+?\D+])(\d+)$/i', $coordinates, $match);
	       if( count($match) !== 3 ){
	       	    return;
	       }
           $settings = site_get_logo_title_website();

           if ( !empty( $settings['logo_url'] ) ) {
               $upload = wp_upload_dir();
               $path_logo = str_replace($upload['baseurl'], $upload['basedir'], $settings['logo_url'] );
	           $style_defaults = [
	           	   'height' => 50,
		           'offset_x' => 10,
		           'offset_y' => 5,
		           'column_width' => 33,
		           'column_height' => 80,
	           ];
	           $style = wp_parse_args($style, $style_defaults);
	           if( $style['column_height'] ){
	           	    $this->sheet->getRowDimension($match[2])->setRowHeight($style['column_height']);
	           }
	           if( $style['column_width'] ){
	           	    $this->sheet->getColumnDimension($match[1])->setWidth($style['column_width']);
	           }
               $drawing = new \PHPExcel_Worksheet_Drawing();
               $drawing->setName('Logo');
               $drawing->setDescription('Logo');
               $drawing->setWorksheet($this->sheet);
               $drawing->setPath($path_logo);

               $drawing->setHeight($style['height']);
               $drawing->setCoordinates($coordinates)
                   ->setOffsetX($style['offset_x'])
                   ->setOffsetY($style['offset_y']);
           }
       }
       catch (\Exception $e) {
	       $this->errors->add(401, $e->getMessage());
       }
    }

    public function get_file_url() {
        $url = str_replace(ABSPATH, site_url('/'), self::$EXPORT_DIRECTORY);
        return $url . $this->filename . "." . $this->file_format;
    }

    function write($coordinates = 'A1', $style_logo = []) {
        if( $this->config['logo'] ) {
            $this->draw_logo($coordinates, $style_logo);
        }
        if( $this->config['write_title'] ) {
            $this->write_title();
        }
        $this->save();
    }


    function save() {
        try {
            $objWriter = \PHPExcel_IOFactory::createWriter($this->objPHPExcel, $this->identify);
            $objWriter->save($this->file);
        } catch (\Exception $e) {
	        $this->errors->add(401, $e->getMessage());
        }
    }

    public function get_error(){
    	return $this->errors;
    }
}
