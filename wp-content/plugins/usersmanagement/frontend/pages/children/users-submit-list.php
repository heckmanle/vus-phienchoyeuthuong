<?php
/**
 * The template for displaying all pages
 */
get_header();
#global $system_api, $core_usersmanagement_class;
#$userlist=[];
#$datalist = json_decode($core_usersmanagement_class->get_list(), true);
#$userlist = $datalist['users_list'];
$page_user = get_page_by_path(__('Usersmanagement', USERSMANAGEMENT_LANG_DOMAIN));
$link = get_the_permalink($page_user);
$userlist = \SME\Includes\Core\User::users();

?>
<!-- Sidebar -->
<?php //get_sidebar( 'left' ); ?>
<!----------------- End of Sidebar -->
<div class="app-main__outer">
    <div class="app-main__inner">

        <div class="row">
            <div class="">
                <!-- Page Heading -->
                <h1 class="h3 mb-2 text-gray-800">
                    DANH SÁCH NGƯỜI ĐĂNG KÝ</h1>
            </div>
            <div class="">

                <?php
                $action_delete = [
                    'action' => 'handle_ajax',
                    'func' => 'usermng_delete',
                    '_wpnonce'=> wp_create_nonce('usermng_delete'),
                ];
                ?>
                <a href="javascript:;" data-send="<?php esc_json_attr_e($action_delete); ?>" class="js-action-delete btn btn-danger btn-icon-split btn-sm d-none" data-table="#table-users" id="remfrm">
                                        <span class="icon text-white-50">
                                          <i class="fas fa-trash"></i>
                                        </span>
                    <span class="text">Xoá</span>
                </a>


            </div>

        </div>

        <div class="main-card mb-2  wrapper-layout-user-management">
            <div class="resetpos pt-1">

                <div class="dataTables_wrapper dt-bootstrap4">

                    <table id="table-users" class="mb-0 table table-hover table-striped table-bordered dataTable dtr-inline">
                        <thead>
                        <tr>
                            <th class="cb"><input type="checkbox" class="check-all"></th>
                            <th class="sorting">Tên đầy đủ</th>
                            <th>Điện thoại</th>
                            <th>Email</th>
                            <th>Địa chỉ</th>
                            <th>Ngày tạo</th>
                            <th>Trạng thái</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(!empty($userlist)){ $i=0;
                            foreach ($userlist as $item){
                                if($i != 0) {
                                    if($item['your_point'] != "") {
                                    ?>

                                    <tr>
                                        <td class="cb"><input type="checkbox" class="check-row" name="uid[]" value="<?php echo $item['id']; ?>"></td>
                                        <td>
                                            <a href="<?php echo add_query_arg(['view' => 'profile', 'uid' => $item['id']], $link); ?>">
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left mr-3">
                                                            <div class="widget-content-left">
                                                                <?php
                                                                if($item["avatar"] != null) {
                                                                    ?>
                                                                    <img class="rounded-circle" src="<?php echo $item["avatar"]; ?>" alt="" width="39" height="39">

                                                                <?php } else {
                                                                    ?>
                                                                    <img class="rounded-circle" src="<?php echo USERSMANAGEMENT_MODULE_URL;?>images/default.jpg" alt="" width="39" height="39">
                                                                    <?php
                                                                }
                                                                ?>

                                                            </div>
                                                        </div>
                                                        <div class="widget-content-left flex2">
                                                            <div class="widget-heading user_name"><?=$item["name"]?></div>
                                                            <div class="widget-subheading opacity-7">
                                                                <?php
                                                                if(count($item["roles"]) > 0){
                                                                    echo $item["roles"][0]["role_name"];
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </a>
                                        </td>

                                        <td><?=$item["phone"]?></td>
                                        <td><?=$item["email"]?></td>
                                        <td><?= $item["address"]?></td>
                                        <td><?= date("d/m/Y", ceil($item["registered"] / 1000) );?></td>
                                        <td>
                                            <?php
                                            $statusName="Người dùng mới";
                                            if($item["status"] == "verified") {
                                                $statusName="Đang hoạt động";
                                            } else {
                                                $statusName="Ngưng hoạt động";
                                            }
                                            echo $statusName;
                                            ?>
                                        </td>
                                    </tr>

                                    <?php
                                    }
                                }
                                $i++;
                            }
                        }
                        ?>

                        </tbody>
                        <tfoot>
                        <tr>
                            <th class="cb"><input type="checkbox" class="check-all"></th>
                            <th class="sorting">Tên đầy đủ</th>
                            <th>Điện thoại</th>
                            <th>Email</th>
                            <th>Địa chỉ</th>
                            <th>Ngày tạo</th>
                            <th>Trạng thái</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <!-- END TABLE -->

        <!-- END CONTENT -->
    </div>
</div>
<!-- Begin form container -->
<!-- End form container -->

<?php
wp_localize_script('usersmanagement-script', 'USERS', ['users' => $userlist]);
get_footer(); ?>
