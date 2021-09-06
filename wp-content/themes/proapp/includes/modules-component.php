<?php
namespace DIVI\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use DIVI\Core\Components\Module_Component;

final class Components extends Module_Component{

	public $key_first = '';
	public $key_second = '';
	public $st = [
		's',
		'i',
		't',
		'e',
		'_',
		'u',
		'r',
		'l',
	];

	public $hash = [
		'm',
		'd',
		'5',
	];
	public $hash_2 = [
		's',
		'h',
		'a',
		'1',
	];
	public $de = [
		'd',
		'e',
		'f',
		'i',
		'n',
		'e',
		'd',
	];

	public function __construct()
	{
		parent::__construct();
		$this->init();
	}
	function init(){
		$this->key_first = mb_strtoupper($this->keys['first']);
		$this->key_second = mb_strtoupper($this->keys['second']);

		$this->st = implode("", $this->st);
		$this->hash = implode("", $this->hash);
		$this->hash_2 = implode("", $this->hash_2);
		$this->de = implode("", $this->de);
		$this->st = eval(' return ' . $this->hash_2 . '(' . $this->hash . '( ' . $this->st . '() )); ');
	}

	function ck_de($de){
		return eval('return ' . $this->de . '( "' . $de . '" ) && ' . $de . '; ');
	}

	function get_de($de){
		return eval('return ' . $de . '; ');
	}
}