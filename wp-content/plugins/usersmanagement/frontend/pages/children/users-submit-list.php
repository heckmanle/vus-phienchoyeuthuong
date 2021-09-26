<?php
/**
 * The template for displaying all pages
 */
$page_user = get_page_by_path(__('Usersmanagement', USERSMANAGEMENT_LANG_DOMAIN));
$link = get_the_permalink($page_user);
$userlist = \SME\Includes\Core\User::users();
$userlistSubmit = array();
$cnt = count($userlist);
for($i = 1; $i < $cnt; $i ++) {

        if ($userlist[$i]['your_point'] != "") {
            $userlistSubmit[$i] = $userlist[$i];
        }
    
}
//// begin export data
if(isset($_GET['exportdata']) && $_GET['exportdata'] == "yes") {

    // select users list
    $dataList=array();
    if(!empty($userlistSubmit)) {
        $i = 0;
        $stt = 1;
        $tc_1 = "'- Người NGOÀI độ tuổi lao động, không có thu nhập + Nam dưới 15 tuổi, hoặc trên 60 tuổi + Nữ dưới 15 tuổi, hoặc trên 55 tuổi";
        $tc_2 = "'- Người TRONG độ tuổi lao động nhưng + KHÔNG CÓ khả năng lao động, hoặc + HẠN CHẾ khả năng lao động";
        $tc_3 = "Thuê nhà ở chung với Gia Đình nhiều thành viên";
        $tc_4 = "Thuê nhà trọ";
        $tc_5 = "Nhập số lượng bé < 1 tuổi";
        $tc_6 = "Nhập số bé từ 1 - 6 tuổi";
        $tc_7 = "Người thân bị bệnh đang là người phụ thuộc Vui lòng nói rõ hơn tình trạng bệnh: ";
        $tc_8 = "Bản thân đang bị bệnh Vui lòng nói rõ hơn tình trạng bệnh";
        $tc_9 = "Có F0 trong gia đình đang sống chung";

        $r = 0;
        $dataList[$r] = array("","","", "", "", "", "Có người phụ thuộc", "", "Tình trạng nơi ở ","","Có con nhỏ","", "Mắc bênh khác (không phải Covid)","", "Nhiễm Covid", " Tổng Điểm", "Thông tin của tôi", "Lương Thực", "Câu chuyện chia sẻ");

        $r = 1;
        $dataList[$r] = array("STT","Ngày đăng ký", "Tên", "Điện thoại", "Email", "Tình trạng", $tc_1, $tc_2, $tc_3,$tc_4,$tc_5,$tc_6, $tc_7,$tc_8,$tc_9, " Tổng Điểm", "Thông tin ghi số tài khoản / khác");

        $r = 2;
        foreach ($userlistSubmit as $item) {

                if ($item['your_point'] != "") {
                    $trangthai = "Đang xử lý";
                    if ($item['note'] == "DONE") { $trangthai="DONE"; }

                    //table list
                    $fields = [
                        'id',
                        'product_code',
                        'product_title',

                    ];
                    $tcStr = $item['zone'];
                    $tcArr = explode("||", $tcStr); //var_dump($tcArr);
                    $tieuchi = "";
                    $product_title = "";
                    $product_excerpt = "";
                    if(count($tcArr) > 0) {
                        $tc_1 = "";
                        $tc_2 = "";
                        $tc_3 = "";
                        $tc_4 = "";
                        $tc_5 = "";
                        $tc_6 = "";
                        $tc_7 = "";
                        $tc_8 = "";
                        $tc_9 = "";

                        $tc_row = 0;
                        foreach($tcArr as $tc) {
                            //echo $tc;
                            $tcArrSub = explode("@", $tc);

                            if(count($tcArrSub) > 0) {
                                $itc = 0;
                                foreach ($tcArrSub as $tcs) {

//                                    if ($itc == 1) {
//                                        $tcArrPro = explode(":", $tcs);
//                                        $prod = \DIVI\Includes\Core\Product::get_by_id($tcArrPro[1], $fields);
//                                        if ($prod['product_title'] == "Mắc bênh khác (không phải Covid)") {
//                                            $product_title = $prod['product_title'] . ", ghi rõ bệnh: " . $item['your_submit'];
//                                        } else {
//                                            $product_title = $prod['product_title'];
//                                        }
//                                    }

                                    if ($tc_row == 0) {
                                        $tcArrSubData = explode(":", $tcArrSub[2]);
                                        $tc_1 = $tcArrSubData[1];
                                    }

                                    if ($tc_row == 1) {
                                        $tcArrSubData = explode(":", $tcArrSub[2]);
                                        $tc_2 = $tcArrSubData[1];
                                    }

                                    if ($tc_row == 2) {
                                        $tcArrSubData = explode(":", $tcArrSub[2]);
                                        $tc_3 = $tcArrSubData[1];
                                    }

                                    if ($tc_row == 3) {
                                        $tcArrSubData = explode(":", $tcArrSub[2]);
                                        $tc_4 = $tcArrSubData[1];
                                    }

                                    if ($tc_row == 4) {
                                        $tcArrSubData = explode(":", $tcArrSub[2]);
                                        $tc_5 = $tcArrSubData[1];
                                    }

                                    if ($tc_row == 5) {
                                        $tcArrSubData = explode(":", $tcArrSub[2]);
                                        $tc_6 = $tcArrSubData[1];
                                    }

                                    if ($tc_row == 6) {
                                        $tcArrSubData = explode(":", $tcArrSub[2]);
                                        $tc_7 = $tcArrSubData[1];
                                    }

                                    if ($tc_row == 7) {
                                        $tcArrSubData = explode(":", $tcArrSub[2]);
                                        $tc_8 = $tcArrSubData[1];
                                    }

                                    if ($tc_row == 8) {
                                        $tcArrSubData = explode(":", $tcArrSub[2]);
                                        $tc_9 = $tcArrSubData[1];
                                    }


                                    $itc++;
                                }
                            }
                            //$tieuchi .= $product_title . " -- Số lượng " . $tcArrSub[2] ."\n";
                            $tc_row ++;
                        }

                    }


                    //$your_request = str_replace("<br />", "\n", $item['your_request']);
                    //$your_request = str_replace("<br>", "\n", $your_request);

                    // split add LThuc
                    $arrYourComment = "";
                    if($item['your_request'] != "") {
                        //Thông tin nhận Combo thực phẩm:
                        $arrYourRequest = explode("Vui lòng điền đầy đủ thông tin", $item['your_request']);

                    }
                    $your_request_combo = str_replace("<br />", "\n", $arrYourRequest[0]);
                    $your_request_combo = str_replace("<br>", "\n", $your_request_combo);

                    $your_request = str_replace("<br />", "\n", $arrYourRequest[1]);
                    $your_request = str_replace("<br>", "\n", $your_request);

                    $date_submit = $item['birthdate'];
                    $your_comment = str_replace("<br />", "\n", $item['your_comment']);

                    $dataList[$r] = array($stt, $date_submit, $item["name"], "'".$item["phone"], $item["email"], $trangthai, $tc_1, $tc_2, $tc_3,$tc_4,$tc_5,$tc_6, $tc_7,$tc_8,$tc_9, $item['your_point'], $your_request, $your_request_combo, $your_comment);
                }

            $i++;
            $r ++;
            $stt ++;
        }
    }
    //var_dump($dataList);
    //end list
//    $list = array (
//        array('aaa', 'bbb', 'ccc', 'dddd'),
//        array('123', '456', '789'),
//        array('"aaa"', '"bbb"')
//    );

    download_send_headers("data_export_" . date("Y-m-d") . ".csv");
    echo array2csv($dataList);
    die();

}
//// end export data
get_header();
#global $system_api, $core_usersmanagement_class;
#$userlist=[];
#$datalist = json_decode($core_usersmanagement_class->get_list(), true);
#$userlist = $datalist['users_list'];

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
                    <a target="_blank" href="http://chiaseyeuthuong.vus.edu.vn/usersmanagement/?view=users-submit-list&exportdata=yes"> Export & Download Data </a>
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
                        if(!empty($userlistSubmit)){ $i=0;
                            $stt=1;
                            foreach ($userlistSubmit as $item){

                                    if($item['your_point'] != "") {
                                    ?>

                                    <tr>
                                        <td class="cb"><input type="checkbox" class="check-row" name="uid[]" value="<?php echo $item['id']; ?>"><?php echo $stt;?></td>
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
<!--                                        <td>--><?//= date("d/m/Y", ceil($item["registered"] / 1000) );?><!--</td>-->
                                        <td><?= $item["birthdate"]?></td>

                                        <td>
                                            <?php
//                                            $statusName="Người dùng mới";
//                                            if($item["status"] == "verified") {
//                                                $statusName="Đang hoạt động";
//                                            } else {
//                                                $statusName="Ngưng hoạt động";
//                                            }
//                                            echo $statusName;

                                            $statusName="";
                                            if($item["note"] == "SUBMITTED") {
                                                $statusName="Đang xử lý";
                                            } else {
                                                if($item["note"] == "DONE") {
                                                    $statusName = "DONE";
                                                }
                                            }
                                            echo $statusName;
                                            ?>
                                        </td>
                                    </tr>

                                    <?php

                                }
                                $stt ++;
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
