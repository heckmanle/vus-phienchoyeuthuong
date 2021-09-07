<?php
/**
 * The template for displaying all pages
 */
global $system_api, $core_events_list, $currentUser;
$eventslist=[];
$datalist = json_decode($core_events_list->get_list(), true);
$eventslist = $datalist['events_list'];
$locations_list = $datalist['locations_list'];

$users_list = $datalist['users_list'];

// list of BTC
$users_role_btc_sale = [];
//dd($users_list);
foreach ($users_list as $uml) {
    if (count($uml["roles"]) > 0) {
        //if (($uml["roles"][0]["role_name"] == "admin") || ($uml["roles"][0]["role_name"] == "sale")) {
        if (($uml["roles"][0]["role_name"] == "btc")) {
            $users_role_btc_sale[] = $uml;
        }
    }
}

// list of manager
$users_role_admin_sale = [];
foreach ($users_list as $uml) {
    if (count($uml["roles"]) > 0) {
        if (($uml["roles"][0]["role_name"] == "admin") || ($uml["roles"][0]["role_name"] == "btc")) {
            $users_role_admin_sale[] = $uml;
        }
    }
}


$clients_list = $datalist['clients_list'];
$client_role_btc = [];

foreach ($clients_list as $citem) {
    // check is BTC
    if( ($citem["client_type"]=="organizers") || ($citem["client_type"]=="contractors")  ) {
        $client_role_btc[]=$citem;
    }
}

?>
<!-- Sidebar -->
<?php get_sidebar( 'left' ); ?>
<!----------------- End of Sidebar -->


<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="tabs-animation">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">
                        <a href="#" class="btn btn-primary btn-circle btn-sm ttip">
                            <i class="fas fa-info-circle"></i>
                        </a>
                        DANH SÁCH CHƯƠNG TRÌNH SỰ KIỆN</h1>
                </div>

            </div>
            <!-- DataTales Example -->
            <div class="resetpos card shadow mb-4">

                <div class="card-body pt-5">

                    <div class="row mb-2">
                        <div class="col-md-5 align-left">
                            <div class="btngroup">

                                <?php if(!\SME\Includes\Core\User::has_role($currentUser, ['btc', 'hh', 'kt secc'])){ ?>

                                <a href="#" class="btn btn-primary btn-icon-split btn-sm" data-toggle="modal" data-target="#exampleModalLongDetail">
                                        <span class="icon text-white-50">
                                          +
                                        </span>
                                    <span class="text">Thêm mới</span>
                                </a>

                                <a href="javascript:;" class="btn btn-danger btn-icon-split btn-sm btn-delete-events" id="remfrm">
                                        <span class="icon text-white-50">
                                          <i class="fas fa-trash"></i>
                                        </span>
                                    <span class="text">Xoá</span>
                                </a>

                                <?php } ?>

                                <a href="/events/?view=listing" class="btn btn-sm btn-light">
                                    <i class="fas fa-sync-alt"> </i>
                                </a>


                            </div>
                        </div>


                    </div>
                    <!------->


                    <div class="table-responsive">
                        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="example" class="mb-0 table table-hover table-striped table-bordered dataTable dtr-inline">
                                    <thead>
                                            <tr role="row">
                                                <th class="cb" ><input type="checkbox"></th>
                                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 330px;" aria-label="Position: activate to sort column ascending">Tên sự kiện</th>

                                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 149px;" aria-label="Start date: activate to sort column ascending">BTC</th>
                                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 88px;" aria-label="Start date: activate to sort column ascending">Quản lý</th>

                                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 98px;" aria-label="Salary: activate to sort column ascending">Bắt đầu</th>
                                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 130px;" aria-label="Salary: activate to sort column ascending">Kết thúc</th>
                                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 80px;" aria-label="Salary: activate to sort column ascending">Trạng thái</th>
                                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 80px;" aria-label="Salary: activate to sort column ascending">Liên kết</th>
                                            </tr>
                                            </thead>


                                            <tbody>
                                            <?php
                                            if(@count($eventslist) > 0 ) {

                                            foreach ($eventslist as $event) {

                                                $author = 'Không xác định';
                                                if(!empty($event['author']['name'])){
                                                $author = $event["author"]["name"];
                                                }
                                                $data_send = [
                                                    'func' => 'get_list',
                                                    'action' => "handle_ajax",
                                                    'id' => $event["id"],
                                                ];
                                                $data_detail = [
                                                        'id' => $event['id'],
                                                        'event_name' => $event['title'],
                                                        'event_desc' => $event['description'],
                                                        'event_customer_role' => $event['client']['id'],
                                                        'event_manage_user' => $event['author']['id'],
                                                        'event_start' => $event['start_date'],
                                                        'event_end' => $event['end_date'],
                                                        'event_location' => $event['location_diagram']['id'],
                                                        'event_status' => $event['status'],
                                                ];
                                                $is_booking_main = $core_events_list->get_booking_main_of_event($event["id"]);
                                                $handle = 'add_new_booking_main';
                                                if($is_booking_main){
                                                    $handle = 'preview_booking_main';
                                                }
                                                ?>
                                                <tr class="event-<?=$event["id"];?>">
                                                    <td class="cb"><input class="check-item" type="checkbox" name="events[]" value="<?=$event["id"];?>"></td>

                                                    <td class="sorting_1 edit-event">
                                                        <a href="#" class="edit-event" data-toggle="modal" data-target="#exampleModalLongDetail" data-send="<?=esc_json_attr($data_detail)?>">
                                                            <?php echo $event['title'];?></a></td>
                                                    <td><?php echo $event['client']['name'];?></td>
                                                    <td><?php echo $event['manager']['name'];?></td>
                                                    <td><?php echo $event['start_date'];?></td>
                                                    <td><?php echo $event['end_date'];?></td>
                                                    <td>
                                                        <div class="progress ">
                                                            <?php
                                                            $status="";
                                                            $percenStatus='0%';
                                                            if($event['status'] == "dàn dựng") {
                                                                $status="bg-warning";
                                                                $percenStatus='68%';
                                                            }
                                                            if($event['status'] == "hoàn tất") {
                                                                $status="";
                                                                $percenStatus='100%';
                                                            }
                                                            if($event['status'] == "kết thúc") {
                                                                $status="bg-success bg-done";
                                                                $percenStatus='100%';
                                                            }
                                                            ?>

                                                            <div class="progress-bar <?php echo $status;?>" role="progressbar" style="width: <?php echo $percenStatus;?>; padding-left:6px;" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">
                                                                <?php echo $event['status'];?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="linkgrp">

                                                        <a href="/locations/?view=detail&id=<?=$event['location_diagram']['id'];?>" title="Sơ đồ vị trí" class="sublinkico" data-toggle="tooltip" title="" data-placement="bottom" class="btn-shadow mr-3 btn btn-dark" data-original-title="Sơ đồ vị trí">
                                                            <i class="fa fa-map" aria-hidden="true"></i>
                                                        </a>
                                                    <?php
                                                    $data_send = [
                                                      'id' => $event['id']
                                                    ];
                                                    ?>
                                                        <a href="#" class="btn-icon-split btn-handle-booking btn-sm <?=$handle?>" data-toggle="modal" data-target="#exampleModalLong" title="Danh sách thiết bị" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Danh sách thiết bị" data-send="<?=esc_json_attr($data_send)?>">
                                                            <i class="fas fa-tasks"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            }
                                            ?>

                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
    </div>
    <!-- /.container -->
</div>

<div class="modal fade show" id="exampleModalLongDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-modal="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">THÔNG TIN CHƯƠNG TRÌNH SỰ KIỆN</h5>
                <button type="button" class="close event" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body p-5">
            </div>
        </div>
    </div>
</div>



<?php // ########  devices of events ######### ?>
<div class="modal fade show" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-modal="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Danh sách thiết bị đăng ký</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<!--<div class="modal fade" id="yesNoModal" tabindex="999" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h3 class="modal-title text-center">XÁC NHẬN</h3>
                <p>Bạn chắc chắn hoàn tất nội dung cập nhật?</p>
            </div>
            <div class="modal-footer text-center">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Huỷ</button>
                <a href="javascript:;" class="btn-confirm-add-booking btn btn-primary btn-icon-split">
                            <span class="icon text-white-50">
                              <i class="fas fa-check"></i>
                            </span>
                    <span class="text">Xác nhận</span>
                </a>

            </div>
        </div>
    </div>
</div>-->

<script type="template/html" id="tpl_add_row_import_booking">
    <form id="frm-add-booking" action="">
        <input name="event_id" type="hidden" value="{{event_id}}">
        <input name="action" type="hidden" value="handle_ajax">
        <input name="func" type="hidden" value="handle_send_mail">
        <div>
            <div class="row table-preview-booking d-flex justify-content-center">
                <div class="datalist">
                    <div class="text-left">
                        <label class="btn btn-default uplfile">
                            <i class="fas fa-paperclip"></i> Chọn và tải file đăng ký .. <input name="file_import" type="file" hidden>

                        </label>
                        <span>(Định dạng file .xls, tải file mẫu ở <a href="<?=EVENTS_MODULE_URL?>templates/Mau-file-dang-ky-thiet-bi.xls">đây</a>)</span>

                    </div>

                </div>
            </div>

            <?php if(!\SME\Includes\Core\User::has_role($currentUser, ['btc', 'hh', 'kt secc'])){ ?>
            <div class="datalist">
                <div class="row brd">
                    <p class="h4 text-gray-900 ">Nội dung thông báo</p>
                    <input name="content" type="text" class="form-control" required="">
                </div>

                <div class="row brd">
                    <p class="h4 text-gray-900 ">Thông báo gửi đến</p>
                    <label class="radio-simulator-button">
                        <input class="d-none" type="checkbox" name="object[]" value="btc">
                        <img class="iconGrp" src="<?php echo THEME_URL.'/img/icon-btc.png';?>">
                    </label>
                    <label class="radio-simulator-button">
                        <input class="d-none" type="checkbox" name="object[]" value="sale">
                        <img class="iconGrp" src="<?php echo THEME_URL.'/img/icon-sale.png';?>">
                    </label>
                    <label class="radio-simulator-button">
                        <input class="d-none" type="checkbox" name="object[]" value="hh">
                        <img class="iconGrp" src="<?php echo THEME_URL.'/img/icon-hh.png';?>"
                    </label>
                </div>

                <div class="row ">
                    <a href="/events/?view=listing" class="btn btn-secondary btn-icon-split">
                                                <span class="icon text-white-50">
                                                  <i class="fas fa-arrow-right"></i>
                                                </span>
                        <span class="text">Huỷ</span>
                    </a>
                    <!-- Button trigger modal -->
                    <button type="submit" class="btn btn-primary">
                                <span class="icon text-white-50">
                                  <i class="fas fa-check"></i>
                                </span>
                        <span class="text">Gửi</span>
                    </button>

                </div>

            </div>
            <?php } ?>
        </div>

    </form>
</script>
<script type="template/html" id="tpl_form_event">
    <form id="frmAddEvent" class="popf" method="post" action="<?= admin_url('admin-ajax.php'); ?>">
        <input type="hidden" name="action" value="handle_ajax">
        <input type="hidden" name="func" value="handle_event">
        <input type="hidden" name="id" value="{{id}}">
        <div class=" form-group">
            <label>Tên sự kiện</label>
            <input type="text" class="form-control form-control-user" name="event_name" id="event_name" aria-describedby="emailHelp" placeholder="" value="{{event_name}}">
        </div>
        <div class="form-group">
            <label>Mô tả</label>
            <textarea class="form-control form-control-user" name="event_desc" id="event_desc" aria-describedby="emailHelp" placeholder="">{{event_desc}}</textarea>
        </div>
        <div class="form-group">
            <label>Ban tổ chức</label><br/>

            <select class="select2-event" name="event_customer_role" id="event_customer_role" style="width:100%">
                    <?php
                    if( count($client_role_btc) > 0 ) {
                        ?>
                        <option value="0"><?= "--Chọn BTC / Nhà thầu--"; ?></option>
                        <?php
                        foreach ($client_role_btc as $cl) {
                            ?>
                            <#
                            var selected = '';
                            if(event_customer_role == '<?=$cl['id']?>'){
                            selected = 'selected';
                            }
                            #>
                            <option {{selected}} value="<?= $cl["id"]; ?>"><?= $cl["name"]; ?></option>
                            <?php
                        }
                    } else {
                        ?>
                        <option value="0"><?= "--Danh sách trống--"; ?></option>
                        <?php

                    }
                    ?>

            </select>

        </div>
        <div class="form-group">
            <label>Quản lý</label>
            <select class="select2-event" name="event_manage_user" id="event_manage_user" style="width:100%">

                    <?php
                    if( count($users_role_admin_sale) > 0 ) {
                        ?>
                        <option value="0"><?= "--Chọn người quản lý--"; ?></option>
                        <?php
                        foreach ($users_role_admin_sale as $urbs) {
                            ?>
                            <#
                            var selected = '';
                            if(event_manage_user == '<?=$urbs['id']?>'){
                            selected = 'selected';
                            }
                            #>
                            <option {{selected}} value="<?= $urbs["id"]; ?>"><?= $urbs["name"]; ?></option>
                            <?php
                        }
                    } else {
                        ?>
                        <option value="0"><?= "--Danh sách trống--"; ?></option>
                        <?php

                    }
                    ?>

            </select>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <label>Ngày bắt đầu</label>
                <input name="event_start" id="event_start" placeholder="dd/mm/yyyy" type="text" class="form-control form-control-user" aria-describedby="emailHelp" style="width:98%" value="{{event_start}}">
            </div>
            <div class="col-md-6">
                <label>Ngày kết thúc</label>
                <input type="text" class="form-control form-control-user" name="event_end" id="event_end" aria-describedby="emailHelp" placeholder="dd/mm/yyyy" value="{{event_end}}">
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-10 p-0">
                <label>Chọn sơ đồ vị trí</label></br>
                <select class="select2-event" name="event_location" id="event_location" style="width:60%">
                    <option selected>-- Chọn sơ đồ vị trí -- </option>
                    <?php
                    foreach ($locations_list as $ll) {
                        ?>
                        <#
                        var selected = '';
                        if(event_location == '<?=$ll['id']?>'){
                        selected = 'selected';
                        }
                        #>
                        <option {{selected}} value="<?= $ll["id"]; ?>"><?= $ll["name"]; ?></option>
                        <?php
                    }
                    ?>
                </select>


                <div class="height30"></div>
                <a href="/locations/?view=detail&eventid=3" class="btn btn-primary btn-circle btn-sm ttip">
                    +
                </a>
                <a href="/locations/?view=detail&eventid=3"><label>Tạo sơ đồ mới</label></a>
            </div>
        </div>


        <div class="form-group">
            <div class="col-md-10 p-0">
                <select class="browser-default select2-event" name="event_status" id="event_status" style="width:60%">

                    <#
                    var selected_dd = '';
                    if(event_status == 'dàn dựng'){
                    selected_dd = 'selected';
                    }
                    #>
                    <option {{selected_dd}} value="dàn dựng" selected>DÀN DỰNG</option>
                    <#
                    var selected_ht = '';
                    if(event_status == 'hoàn tất'){
                    selected_ht = 'selected';
                    }
                    #>
                    <option {{selected_ht}} value="hoàn tất">HOÀN TẤT</option>
                    <#
                    var selected_kt = '';
                    if(event_status == 'kết thúc'){
                    selected_kt = 'selected';
                    }
                    #>
                    <option {{selected_kt}} value="kết thúc">KẾT THÚC</option>
                </select>
            </div>

        </div>


        <div class="">
            <a href="/events/?view=listing" class="btn btn-secondary btn-icon-split">
                                    <span class="icon text-white-50">
                                      <i class="fas fa-arrow-right"></i>
                                    </span>
                <span class="text">Huỷ</span>
            </a>
            <button type="submit" class="btn-wide mr-2 btn-square btn btn-primary">
                        <span class="icon text-white-50">
                                      <i class="fas fa-check"></i>
                                    </span>
                Cập nhật</button>

        </div>


    </form>
</script>
<?php
wp_enqueue_script('events-js', EVENTS_MODULE_URL . '/frontend/js/events-public.js', [], EVENTS_VERSION, true);
wp_localize_script('events-list-js', 'EVENTS', [
    'AJAX_URL' => admin_url("admin-ajax.php"),
]);

wp_enqueue_script('events-import-booking-js', EVENTS_MODULE_URL . '/frontend/js/events-import-booking.js', ['jquery', 'bootstrap-modal-js', 'jquery-ui-core', 'jquery-ui-datepicker', 'underscore', 'backbone', 'jquery-form', 'toastr-js'], EVENTS_VERSION, true);
?>
<?php // ########  detail of events ######### ?>
