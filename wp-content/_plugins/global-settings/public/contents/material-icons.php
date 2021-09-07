<?php
 wp_enqueue_script('global-settings-material-icons');
?>

<div class="material-icons-wrapper">
	<h3><?php _e('Material icons', GLOBAL_SETTINGS_LANG_DOMAIN); ?></h3>
    <form id="material-icons-form" action="<?php echo GLOBAL_SETTINGS_AJAX_URL; ?>">
        <input type="hidden" name="action" value="gs_handle_ajax">
        <input type="hidden" name="func" value="gs_upload_file">
        <label class="material-label-upload">
            <span class="text"><?php _e('Upload icons', GLOBAL_SETTINGS_LANG_DOMAIN); ?></span>
            <input type="file" name="material_icons[]" multiple>
            <span class="icon"><?php _e('Upload', GLOBAL_SETTINGS_LANG_DOMAIN); ?></span>
        </label>
    </form>
	<div class="material-icons-list">
		<div class="material-icons-items">

		</div>
	</div>
    <script type="text/html" id="tpl-render-items">
        <ul class="">
            <# _.each(result, function(item){ #>
                <li class="d-inline-block">
                    <span style="background-image: url({{ item }})">
                    </span>
                    <a href="javascript:;" data-url="{{ item }}" class="material-icon-remove"><i class="ion-android-delete"></i></a>
                </li>
            <# }) #>
        </ul>
    </script>
    <script type="text/html" id="tpl-preview-items">
        <h3 class="mt-2"><?php _e('Upload progress', GLOBAL_SETTINGS_LANG_DOMAIN); ?></h3>
        <ul class="">
            <# _.each(files, function(file, idx){ #>
            <li class="d-inline-block image-item-{{ idx }}">
                <img src="{{ URL.createObjectURL(file) }}" width="150">
                <div class="progress mt-2">
                    <div class="progress-bar" style="width:0%"></div>
                </div>
            </li>
            <# }) #>
        </ul>
    </script>
</div>
