<?php get_header('login');
$classObj = new Authentication\Includes\Core\Authentication();
$data = $classObj->get_publishGlobalSetting_logo();
$two_fa = \DIVI\Includes\Core\GlobalSettings::publishGlobalSetting('2FA');
if( !is_wp_error($two_fa) ){
    $two_fa = $two_fa['value'] ?? 'off';
}else{
	$two_fa = 'off';
}
$logo = "";
if( !is_wp_error($data) && $data['message'] == 'success') {
    $logo = $data['publishGlobalSetting']->value;
    $GLOBALS['logo'] = $logo;
}
?>
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

                            <img id="cms_logo" src="<?php echo $GLOBALS['logo'];?> ">
                        </div>
                        <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
                            <h2 class="mb-3">
                                <span class="d-block">ĐĂNG NHẬP</span>
                            </h2>
                            <div class="">
                                <p class="intro">Nhập mật khẩu của bạn để xác thực vào hệ thống</p>
                            </div>
                            <div class="">
                                <form class="user frm-validation frm-validate" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>">
                                    <input type="hidden" name="action" value="handle_ajax">
                                    <?php if( $two_fa == 'on' ): ?>
                                    <input type="hidden" name="func" value="authentication_check_password">
                                    <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('authentication_check_password'); ?>">
                                    <?php else: ?>
                                        <input type="hidden" name="func" value="authentication_login">
                                        <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('authentication_login'); ?>">
                                    <?php endif; ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Mật khẩu</label>
                                                <input class="form-control" type="password" data-val="true" data-val-required="Vui lòng nhập mật khẩu" data-rule-required="true" data-msg-required="Trường này không được để trống." data-is-validation="true" id="Password" name="password">
                                                <i class="fas fa-eye action-show-password"></i>
                                                <span class="text-danger field-validation-valid" data-valmsg-for="Password" data-valmsg-replace="true"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12" style="max-width:360px;">
                                            <div class="message-notification"></div>
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                Đăng nhập
                                            </button>
                                        </div>
                                    </div>

                                </form>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <p class=""><a href="/authentication/?view=login-lost-password">Quên mật khẩu?</a></p>
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



