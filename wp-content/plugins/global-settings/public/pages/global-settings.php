<?php

$type = $_GET['type'] ?? 'material-icons';
$settings = Global_Settings::get_define_settings();
$get_setting = $settings[$type] ?? [];
if( empty($get_setting) ){
	global $wp_query;
	$wp_query->set_404();
	status_header( 404 );
	get_template_part( 404 );
	return;
}


add_filter('pre_get_document_title', function ($title) use($get_setting){
	$title .= ' ' . $get_setting['title'];
	return $title;
}, 100);
get_header();
wp_enqueue_style('global-settings');
wp_enqueue_script('global-settings');
$html = '';
if( file_exists($get_setting['path']) ){
	ob_start();
	include_once $get_setting['path'];
	$html = ob_get_clean();
}
?>
    <script>
        const GS_IMAGE_LOADING = {
            message: '<div class="ball-clip-rotate-multiple"> <div></div><div></div></div>',
        };
        const GS_AJAX_URL = "<?php echo GLOBAL_SETTINGS_AJAX_URL; ?>";
    </script>
<div class="container-fluid gs-container-fluid mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="gs-navigation">
                <h3 class="font-weight-bold"><?php _e('Global settings') ?></h3>
                <ul>
					<?php
					foreach ($settings as $item){
						$link = site_url('/' . GLOBAL_SETTINGS_PAGE_URL);
						$link = add_query_arg(['type' => $item['name']], $link);
						echo sprintf('<li>
                        <a href="%s" title="%s">%s</a>
                    </li>', $link, $item['title'], $item['title']);
					}
					?>
                </ul>
            </div>
        </div>
        <div class="col-md-8">
            <?php
			echo $html;
            ?>
        </div>
    </div>
</div>
<?php
get_footer();
