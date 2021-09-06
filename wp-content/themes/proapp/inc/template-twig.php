<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 9/24/19
 * Time: 10:31 AM
 */

namespace SME\Inc;

require_once THEME_DIR . '/inc/app-extension.php';
class TemplateTWIG{
    public static function compileTemplateTwig( $template, $data ) {
        $env = new \Twig\Environment(new \Twig\Loader\ArrayLoader(), [
        	'debug' => true,
        ]);
	    $env->addExtension(new AppExtension());
	    $env->addExtension(new \Twig\Extension\DebugExtension());
        $template = $env->createTemplate($template);
        $message = $env->render($template, $data);
        return $message;
    }
}
