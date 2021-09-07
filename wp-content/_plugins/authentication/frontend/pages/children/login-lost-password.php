<?php
if( !isset($_SESSION['username']) || empty($_SESSION['username']) ){
	wp_redirect(add_query_arg(['view' => 'login-email'], get_the_permalink(get_page_by_path(__('Authentication', AUTHENTICATION_LANG_DOMAIN)))));
	exit;
}

$get_response = \SME\Includes\Core\User::get_phone_by_email($_SESSION['username']);
if( is_wp_error($get_response) ){
    _site_default_wp_die_handler($get_response->get_error_message());
}
$phone_number = isset($get_response['phone']) ? $get_response['phone'] : '';

$email = isset($get_response['email']) ? $get_response['email'] : '';
$_SESSION['phone_number'] = $phone_number;
$_SESSION['email'] = $email;
get_header('login');

$classObj = new Authentication\Includes\Core\Authentication();
$data = $classObj->get_publishGlobalSetting_logo();
$logo = "";
if($data['message'] == 'success') {
    $logo = $data['publishGlobalSetting']->value;
    $GLOBALS['logo'] = $logo;
}

?>
<script>
    var phoneNumber = "<?php echo $phone_number; ?>"
</script>
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
                            <span class="d-block">QUÊN MẬT KHẨU</span>
                        </h2>
                        <div class="">
                            <p class="intro">Bạn chọn nhận mã xác nhận theo nội dung bên dưới
                                để tạo lại mật khẩu mới</p>
                        </div>
                        <div class="">
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="ulicheck">
                                        <li>
											<?php
											$send_code = [
												'action' => 'handle_ajax',
												'func' => 'authentication_forgotpass',
												'type' => 'phone_number'
											];
											?>
                                            <a href="javascript:;" data-send="<?php esc_json_attr_e($send_code); ?>" class="js-action-phone-ajax" data-method="post" data-href="/authentication/?view=login-verifycode">
                                                <i class="ico pe-7s-call"></i>
                                                <p class="pl-5 mb-0">Nhận mã qua điện thoại</p>
                                                <p class="pl-5 mb-0 val"><?php echo str_repeat('*', 3) . ' ' . str_repeat('*', 3) . ' ' . substr($phone_number, -4) ?></p>
                                            </a>
                                            <div id="recaptcha-container"></div>
                                        </li>
                                        <li>
											<?php $send_code['type'] = 'email'; ?>
                                            <a href="javascript:;" data-send="<?php esc_json_attr_e($send_code); ?>" class="js-action-ajax" data-method="post" data-href="/authentication/?view=login-verifycode">
                                                <i class="ico pe-7s-mail-open"></i>
                                                <p class="pl-5 mb-0">Nhận mã qua Email</p>
                                                <p class="pl-5 mb-0 val"><?php
													$email_explode = explode('@', $email);
													$email_1 = $email_explode[0];
													$email_2 = end($email_explode);
													echo str_repeat('*', 3) . ' ' . str_repeat('*', 3) . ' ' . substr($email_1, -3) . '@' . $email_2 ?></p>
                                            </a>
                                        </li>
                                    </ul>
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