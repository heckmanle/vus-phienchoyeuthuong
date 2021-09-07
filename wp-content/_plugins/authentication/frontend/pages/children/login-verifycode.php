<?php
$type = $_SESSION['forgot_type'] ?? '';
$username_type = $_SESSION['username_type'] ?? '';
if( !in_array($type, ['email', 'phone_number']) && !in_array($username_type, ['email', 'phone_number']) ){
	wp_redirect(add_query_arg(['view' => 'login-email'], get_the_permalink(get_page_by_path(__('Authentication', AUTHENTICATION_LANG_DOMAIN)))));
	exit;
}
?>

<?php get_header('login');

$classObj = new Authentication\Includes\Core\Authentication();
$data = $classObj->get_publishGlobalSetting_logo();
$logo = "";
if($data['message'] == 'success') {
    $logo = $data['publishGlobalSetting']->value;
    $GLOBALS['logo'] = $logo;
}
?>
<?php if( 'phone_number' == $type || 'phone_number' == $username_type ): ?>

<script>
    var phoneNumber = "<?php echo preg_replace('/(^0)/', '+84', $_SESSION['phone_number']); ?>"
</script>
<?php endif; ?>
<?php
if( $username_type ){
    $type = $username_type;
	wp_add_inline_script('authentication', '
        jQuery(function ($){
            setTimeout(function(){
            $(".js-re-send-code").trigger("click");}, 1000);
        });
	');
    ?>
<?php } ?>
    <div class="app-container app-theme-white body-tabs-shadow wrapper-layout-authentication">
        <div class="app-container">
            <div class="h-100">
                <div class="h-100 no-gutters row">
                    <div class="d-none d-lg-block col-lg-6">
                        <div class="slider-light">
                            <div class="slick-slider">
                                <div>
                                    <div class="position-relative h-100 d-flex justify-content-center align-items-center bg-plum-plate" tabindex="-1">
                                        <div class="slide-img-bg" style="background-image: url('<?php echo AUTHENTICATION_MODULE_URL;?>/images/sign-bg-1.png');"></div>
                                        <?php
                                        /*<div class="slider-content">
                                            <h3>
                                            </h3>
                                        </div>
                                        */ ?>
                                    </div>
                                </div>

                                <div>
                                    <div class="position-relative h-100 d-flex justify-content-center align-items-center bg-plum-plate" tabindex="-1">
                                        <div class="slide-img-bg" style="background-image: url('<?php echo AUTHENTICATION_MODULE_URL;?>/images/sign-bg-1.png');"></div>
                                        <?php
                                        /*<div class="slider-content">
                                            <h3>
                                            </h3>
                                        </div>
                                        */ ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="h-100 d-flex bg-white justify-content-center align-items-center col-md-12 col-lg-6">
                        <div class="row">

                            <img id="cms_logo" src="<?php echo $GLOBALS['logo'];?>">
                        </div>
                        <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
                            <h2 class="mb-3">
                                <span class="d-block">XÁC THỰC BẢO MẬT</span>
                            </h2>
                            <div class="">
                                <p>Nhập mã xác thực của bạn đã nhận qua

                                    <?php echo isset($_SESSION['email']) ? $_SESSION['email'] : $_SESSION['phone_number']; ?> để tiếp tục</p>

                            </div>
                            <div>
                                <form class="frm-validation frm-validate" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>">
                                    <input type="hidden" name="action" value="handle_ajax">
                                    <?php if( !$username_type ):  ?>
                                    <input type="hidden" name="func" value="authentication_verify_code">
                                    <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('authentication_verify_code'); ?>">
                                    <?php else: ?>
                                        <input type="hidden" name="func" value="authentication_login">
                                        <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('authentication_login'); ?>">
                                    <?php endif; ?>
                                    <div class="form-row mb-3">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group"><label for="exampleCode" class="">6-digit code</label><input name="code" data-rule-required="true" data-msg-required="Trường này không được để trống." data-is-validation="true" id="exampleCode" placeholder="Nhập mã bảo mật" type="text" class="form-control"></div>
                                        </div>

                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6 align-left">
                                            <div class="message-notification"></div>
                                            <div class="ml-auto">
                                                <button class="btn btn-primary btn-lg">XÁC THỰC</button>
                                            </div>
                                        </div>

                                    </div>

                                </form>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <?php
                                        if( $username_type ){
                                            echo  '<div id="recaptcha-container"></div>';
                                        }
                                        ?>
                                        <p class="re-send-code">Bạn chưa nhận được mã? <a href="javascript:;" class="js-re-send-code" data-type="<?php echo $type; ?>" data-func="<?php echo !empty($username_type) ? 'authentication_send_code' : 'authentication_forgotpass'; ?>"><span>Gửi lại</span></a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php get_footer();?>
