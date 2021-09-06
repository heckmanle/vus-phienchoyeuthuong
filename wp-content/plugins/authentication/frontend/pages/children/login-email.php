<?php get_header('login');
$classObj = new Authentication\Includes\Core\Authentication();
$data = $classObj->get_publishGlobalSetting_logo();
$logo = "";
if($data['message'] == 'success') {
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
                                <p>Nhập thông tin tài khoản của bạn để đăng nhập vào hệ thống</p>
                            </div>
                            <div>
                                <form class="frm-validation frm-validate" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>">
                                    <input type="hidden" name="action" value="handle_ajax">
                                    <input type="hidden" name="func" value="authentication_login_email">
                                    <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('authentication_login_email'); ?>">
                                    <div class="form-row mb-3">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group"><label for="exampleEmail" class="">Điện thoại / Email (*)</label>
                                                <input name="user_name" data-rule-required="true" data-msg-required="Trường này không được để trống." data-is-validation="true" id="exampleEmail" placeholder="Nhập " type="text" class="form-control">
                                                <img class="ico" src="<?php echo SITE_URL; ?>/wp-content/uploads/lib-icons/ico-mail.svg" alt="">

                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6 align-left">
                                            <div class="message-notification"></div>
                                            <div class="ml-auto">
                                                <button class="btn btn-primary btn-lg">ĐĂNG NHẬP</button>
                                            </div>
                                        </div>

                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
get_footer();
?>
