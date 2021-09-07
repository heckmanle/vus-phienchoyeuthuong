<?php

if( !isset($_SESSION['phone_number']) || empty($_SESSION['phone_number']) || !isset($_SESSION['forgot_type']) || empty($_SESSION['forgot_type']) ){
//	wp_redirect(add_query_arg(['view' => 'login-email'], get_the_permalink(get_page_by_path(__('Authentication', AUTHENTICATION_LANG_DOMAIN)))));
//	exit;
}

$classObj = new Authentication\Includes\Core\Authentication();
$data = $classObj->get_publishGlobalSetting_logo();
$logo = "";
if($data['message'] == 'success') {
    $logo = $data['publishGlobalSetting']->value;
    $GLOBALS['logo'] = $logo;
}

?>
<?php get_header('login');?>
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
                            <span class="d-block">ĐẶT MẬT KHẨU</span>
                        </h2>
                        <div class="">
                            <p class="intro"> <p>Mật khẩu của bạn sẽ được thiết lập theo nội dung bên dưới</p></p>
                        </div>
                        <div class="">
                            <form class="frm-validation frm-validate user" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>">
                                <input type="hidden" name="action" value="handle_ajax">
                                <input type="hidden" name="func" value="authentication_reset_password">
                                <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('authentication_reset_password'); ?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mật khẩu</label>
                                            <input class="form-control" type="password" data-val="true" data-val-required="Vui lòng nhập mật khẩu" data-rule-minlength="8" data-msg-minlength="Mật khẩu ít nhất 8 ký tự bao gồm chữ và số" id="Password1" data-rule-required="true" data-msg-required="Trường này không được để trống." data-is-validation="true" name="password">
                                            <i class="fas fa-eye action-show-password" data-show="#Password1"></i>
                                            <span class="text-danger field-validation-valid" data-valmsg-for="Password1" data-valmsg-replace="true"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nhập lại mật khẩu</label>
                                            <input class="form-control" type="password" data-val="true" data-val-required="Vui lòng nhập mật khẩu" data-rule-required="true" data-msg-required="Trường này không được để trống." data-is-validation="true" data-msg-equalTo="Nhập lại mật khẩu phải trùng với mật khẩu" data-rule-equalTo="#Password1" id="Password2" name="re_password">
                                            <i class="fas fa-eye action-show-password" data-show="#Password2"></i>
                                            <span class="text-danger field-validation-valid" data-valmsg-for="Password2" data-valmsg-replace="true"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="message-notification"></div>
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            Xác nhận
                                        </button>
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
<?php get_footer();?>

