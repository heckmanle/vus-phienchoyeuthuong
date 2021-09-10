<?php
/**
 * The template for displaying all pages
 */

$users = \SME\Includes\Core\User::users();
$get_roles = \SME\Includes\Core\User::get_roles();
$user = [];
$id = $name = $email = $birthdate = $phone = $address = $roles = $department = $direct_management = $note = $status = '';
$phone_code = '+84';
if( isset($_GET['uid']) && !empty($_GET['uid']) ){
    $user = \SME\Includes\Core\User::get_user_by_id($_GET['uid']);
    if( is_wp_error($user) ){
	    _site_default_wp_die_handler(__('Người dùng không tồn tại'));
    }
    extract($user);
    if( !empty($phone) ){
        if( preg_match('/^(\+84)/i', $phone) ){
            $phone_code = '+84';
            $phone = preg_replace('/^(\+84)/i', '', $phone);
        }
    }
    if( !empty($roles) ){
	    $roles = array_shift($roles);
    }
}

get_header();
wp_enqueue_style('plugin-user-management');
?>
    <!-- Sidebar -->
<?php //get_sidebar( 'left' ); ?>
    <!----------------- End of Sidebar -->

    <div class="app-main__outer prof">
        <div class="app-main__inner p-0">
            <div class="app-inner-layout">

                <div class="wrapper-layout-user-management">

                    <form class="form-validation" enctype="multipart/form-data" action="<?php echo admin_url('admin-ajax.php'); ?>" method="POST">
                        <input type="hidden" name="action" value="handle_ajax">
                        <input type="hidden" name="func" value="handle_user">
                        <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('handle_user'); ?>">
                        <?php
                        if( !empty($user) ){
                            echo '<input name="uid" type="hidden" id="uid" value="' . $user['id'] . '">';
                        }
                        ?>
                        <div class="app-inner-layout__wrapper">
                            <div class="app-inner-layout__sidebar card">
                                <img id="user-avatar" class="rounded-circle shadow mt-3" src="<?php echo !empty($user) && !empty($user['avatar']) ? $user['avatar'] : USERSMANAGEMENT_MODULE_URL . 'images/default.jpg';?>" alt="Avatar" style="width: 123px;height: 123px;margin:0 auto;border: 8px solid #fff;" data-toggle="modal" data-target="#modal-user-picture" >
                                <ul class="mt-3">
                                    <li class="nav-item-header nav-item">
                                        <span>LIÊN QUAN TÀI KHOẢN</span>
                                    </li>
                                    <li class="nav-item">
                                        <input type="file" class="d-none" name="file_upload" id="file-user-avatar">
                                        <button data-toggle="modal" data-target="#modal-user-picture" type="button" tabindex="0" class="d-flex align-items-center dropdown-item">
                                            <div class="badge ml-0 mr-3 badge-dot badge-dot-xl badge-success">Dark</div>
                                            Cập nhật hình đại diện
                                        </button>
                                    </li>
                                </ul>
                                <hr>
                                <?php if( isset($last_login) && !empty($last_login) ): ?>
                                <ul class="mt-3">
                                    <li class="nav-item-header nav-item">
                                        <span>LỊCH SỬ HOẠT ĐỘNG</span>
                                    </li>
                                </ul>

                                        <div class="notifications-box mr-3 p-2">
                                            <div class="vertical-time-simple vertical-without-time vertical-timeline vertical-timeline--one-column user-login-history" data-simplebar="">
                                                <?php
                                                    foreach ($last_login as $item){
	                                                    $item = ceil($item / 1000);
                                                        $last_login_date = date('d/m/Y', $item);
                                                        $last_login_time = date('h:i A', $item);
                                                        echo "
                                                        <div class=\"vertical-timeline-item dot-warning vertical-timeline-element\">
                                                            <div><span class=\"vertical-timeline-element-icon bounce-in\"></span>
                                                                <div class=\"vertical-timeline-element-content bounce-in\">
                                                                    <p>Đăng nhập vào ngày {$last_login_date}, lúc
                                                                        <span class=\"text-success\">{$last_login_time}</span>
                                                                    </p>
                                                                    <span class=\"vertical-timeline-element-date\"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        ";

                                                    }
                                                ?>

                                            </div>
                                        </div>
                                <?php endif; ?>
                            </div>
                            <div class="app-inner-layout__content card">
                                <div class="app-inner-layout__header bg-heavy-rain">
                                    <div class="app-page-title">
                                        <div class="page-title-wrapper">
                                            <div class="page-title-heading">
                                                <div class="mr-1">
                                                    <div class="page-title-icon">
                                                        <i class="pe-7s-power icon-gradient bg-mixed-hopes"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    THÔNG TIN TÀI KHOẢN
                                                    <div class="page-title-subheading">Các thông tin chi tiết của tài khoản, bạn có thể tuỳ chỉnh cập nhật nội dung cho tài khoản này.</div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="form-wizard-content frmmodal  p-4">

                                    <div class="form-row">
                                            <div class="col-md-3">
                                                <div class="position-relative form-group"><label for="exampleCity" class="">Tên đầy đủ <span class="txtred">(*)</span></label><input name="name" data-is-validation="true" data-rule-required="true" data-msg-required="Tên không được để trống" id="exampleCity" type="text" class="form-control" value="<?php echo $name; ?>"></div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="position-relative form-group"><label for="exampleZip" class="">Email <span class="txtred">(*)</span></label><input name="email" data-is-validation="true" data-rule-required="true" data-msg-required="Email không được để trống" id="exampleZip" type="email" class="form-control" value="<?php echo $email; ?>"></div>
                                            </div>
                                            <div class="col-md-3 birthdate">
                                                <label for="exampleAddress2" class="">Ngày sinh</label>
                                                <div class="input-group">
                                                    <input type="text" name="birthdate" class="form-control" data-toggle="datepicker-icon" placeholder="dd/mm/yyyy"  value="<?php echo $birthdate; ?>">
                                                    <div class="input-group-prepend datepicker-trigger ">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-calendar-alt"></i>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <div class="position-relative form-group user">
                                                <label for="password" class="">Mật khẩu</label>
                                                <input name="password" id="password" type="password" class="form-control">
                                                <i class="fas fa-eye action-show-password cursor-pointer" data-show="#password"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="position-relative form-group user"><label for="confirm-password" class="">Xác nhận lại mật khẩu</label><input name="confirm_password" data-rule-equalTo="#password" data-is-validation="true" id="confirm-password" data-msg-equalTo="Nhập lại mật khẩu phải trùng với mật khẩu" type="password" class="form-control"><i class="fas fa-eye action-show-password cursor-pointer" data-show="#confirm-password"></i></div>
                                        </div>
                                        <div class="col-md-4">

                                        </div>
                                    </div>

                                    <div class="form-row">

                                        <div class="col-md-4">
                                            <div class="position-relative form-group">
                                                <label for="exampleState" class="">Điện thoại <span class="txtred">(*)</span></label>
                                                <div class="row">
                                                    <div class="col-md-3 p0 col-sm-3 phone_code" style="padding-left:0px !important;">
                                                        <input name="phone_code" id="userphonecode" type="text" class="form-control" placeholder="+84" value="<?php echo $phone_code; ?>">
                                                    </div>
                                                    <div class="col-md-9 col-sm-9 phone_number">
                                                        <input name="phone" id="userphonenumber" type="text" class="form-control" data-is-validation="true" data-rule-required="true" value="<?php echo $phone; ?>" placeholder="090 000 0000">
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="position-relative form-group">
                                                <label for="exampleAddress" class="">Địa chỉ liên lạc</label>
                                                <input name="address" id="exampleAddress" placeholder="10 Đường số 8 .." type="text" class="form-control" value="<?php echo $address; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4">

                                        </div>
                                    </div>

                                    <div class="form-row mb-2">

                                        <div class="col-md-4 mana_user">
                                            <label for="exampleAddress" class="">Quản lý trực tiếp</label>
                                            <div class="position-relative form-group">
                                                <select name="manager" class="multiselect-dropdown form-control custom-select">
                                                    <?php
                                                    if( !empty($users) ){
                                                        foreach ($users as $u){
                                                            $selected = !empty($direct_management) && is_array($direct_management) && $direct_management['id'] == $u['id'] ? 'selected' : '';
                                                            echo sprintf('<option value="%s" %s>%s</option>', $u['id'], $selected, $u['name']);
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="exampleAddress2" class="">Phòng ban</label>
                                            <?php  $strDepartment="";
                                                    if( !empty($department) ){
                                                        for ($p=0; $p < count($department); $p++){
                                                            $strDepartment .= $department[$p];
                                                            if(count($department) != $p && count($department) > 1 ) {
                                                                $strDepartment .= " , ";
                                                            }
                                                        }
                                                    } ?>
                                            <input name="department" id="department" type="text" class="form-control" value="<?php echo $strDepartment; ?>" placeholder="">


                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-8">
                                            <label for="exampleAddress2" class="">Ghi chú</label>
                                            <input name="note" id="exampleAddress2" placeholder=".." type="text" class="form-control" value="<?php echo $note; ?>">
                                        </div>
                                    </div>

                                    <div class="form-row pt-3">
                                        <div class="col-md-4">
                                            <label for="exampleAddress2" class="">Vai trò</label>
                                            <select name="role" class="multiselect-dropdown form-control custom-select">
                                                <?php
                                                if( !empty($get_roles) ){
                                                    foreach ($get_roles as $role){
                                                        $selected = !empty($roles) && is_array($direct_management) && $roles['id'] == $role['id'] ? 'selected' : '';
                                                        echo sprintf('<option value="%s" %s>%s</option>', $role['id'], $selected, mb_strtoupper($role['role_name']));
                                                    }
                                                }
                                                ?>
                                            </select>


                                        </div>
                                    </div>

                                    <div class="form-row mt-3 mb-2 boradius_circl">
                                        <div class="col-md-12">
                                            <label for="exampleAddress2" class="">Trạng thái hoạt động</label>
                                            <input id="chkToggle1" <?php echo $status == 'verified' ? 'checked' : ''; ?> name="status" type="checkbox" data-toggle="toggle">
                                        </div>
                                    </div>

                                    <div class="message-notification"></div>

                                    <button type="submit" class="mt-2 btn-shadow btn-wide btn-pill btn-hover-shine btn btn-primary">Cập nhật</button>
                                </div>

                                <?php if($user['roles'][0]['role_name'] == "admin") { ?>
                                <div class="u_more">
                                    <div class="form-wizard-content frmmodal  p-4">
                                        <div class="row page-title-heading">
                                            <h3>THÔNG TIN HỖ TRỢ</h3>
                                        </div>
                                        <div class="row page-title-heading">
                                            <h4>Câu chuyện chia sẻ</h4>
                                        </div>
                                        <div class="row u_comm">
                                            <?php echo $user['your_comment'];?>
                                        </div>
                                        <div class="row page-title-heading pt-4">
                                            <h4>Thông tin của tôi:</h4>
                                        </div>
                                        <div class="row u_comm">
                                            <?php echo $user['your_request'];?>
                                        </div>
                                        <div class="row page-title-heading pt-4">
                                            <h4>Tiêu chí:</h4>
                                        </div>
                                        <div class="row u_comm">
                                            <?php echo $user['your_point'] . "</br>" .  $user['your_submit'];?>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>

                        </div>

                    </form>


                </div>
            </div>
        </div>
    </div>




            <!-- END CONTENT -->
        </div>
    </div>
<div id="modal-user-picture" class="modal fade" role="dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                <h4 class="modal-title"><?php _e('Ảnh đại diện'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="picture-content">
                    <div class="cropper-image">
                        <img id="image" src="">
                    </div>
                    <div class="cropper-actions d-none">
                        <input type="text" id="range">
                    </div>
                    <div class="form-item form-type-file form-item-files-picture-upload mt-3">
                        <div class="text-center">
                            <label class="btn btn-primary" for="picture-upload"><i class="icon-plus" aria-hidden="true"></i> <?php _e('Upload photo'); ?> </label>
                            <input id="picture-upload" class="inputfile form-file d-none" type="file" name="picture_upload" size="48">
                        </div>
                        <div class="description mt-3 mb-3 alert alert-warning" role="alert">
                            <?php echo sprintf("<p>%s</p>", __('Khuôn mặt hoặc hình ảnh ảo của bạn. Hình ảnh lớn hơn 1024x1024 pixel sẽ được thu nhỏ lại.'));?>
                        </div>
                    </div>
                    <div class="message-notification">

                    </div>
                    <div class="modal-footer">
                        <div class="form-actions form-wrapper" id="edit-actions--2">
                            <button class="btn btn-primary form-submit ajax-processed" type="submit" id="btn-save-picture" data-dismiss="modal" value="Update"><i class="icon-save"></i> <?php _e('Lưu lại') ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- Begin form container -->

    <!-- End form container -->
<?php

get_footer();
?>
