<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 13/07/2020
 * Time: 17:14
 */

namespace SME\Inc;

//class AppExtension extends \Twig\Extension\AbstractExtension{
class AppExtension {
	public static $instance = null;

	public static function instance(){
		is_null(self::$instance) && self::$instance = new self();
		return self::$instance;
	}

	public function getFilters(){
		$result = parent::getFilters();
		$result[] = new \Twig\TwigFilter('numberToFormat', [$this, 'formatPrice']);
		$result[] = new \Twig\TwigFilter('numberToWord', [$this, 'numberToWord']);
		return $result;
	}

	public function formatPrice($number, $decimals = 0){
		return core_convert_number_to_format($number, $decimals);
	}

	public function numberToWord($number){
		return core_convert_number_to_word($number);
	}


}
