<?php
global $core_bookingservices_class, $core_clients_class, $system_api, $core_inventory, $currentUser;
$booking_list=[];

$data_booking_list = json_decode($core_bookingservices_class->get_list(), true);
$booking_list = $data_booking_list['booking_list'];

//var_dump($booking_list);

if(!\SME\Includes\Core\User::has_role($currentUser, ['btc', 'sale', 'admin', 'hh', 'kt secc'])){
    $booking_list = array_filter($booking_list, function ($it) use ($currentUser){
        return $it['author']['id'] == $currentUser['id'];
    });
}

$get_users = [];
if(\SME\Includes\Core\User::has_role($currentUser, ['sale'])) {
	$users = \SME\Includes\Core\User::users();
	$get_users = array_filter($users, function ($it) {
		return \SME\Includes\Core\User::has_role($it, ['hh', 'kt secc']);
	});
	$get_users = array_values($get_users);
}
$events = \SME\Includes\Core\Event::get_events();
if( is_wp_error($events) ){
	$events = [];
}
$client_type = $core_clients_class->client_type;
$list_client = \SME\Includes\Core\Client::clients();
if( !\SME\Includes\Core\User::has_role($currentUser, ['sale', 'admin']) ) {
	$list_client = array_filter($list_client, function ($item) use ($currentUser) {
		$contact_person_informations = array_column($item['contact_person_informations'], 'id');
		$contact_person_informations = array_values($contact_person_informations);

		return in_array($currentUser['id'], $contact_person_informations);
	});
}
$current_user_id = $system_api->get_id_cookie();
$products = $core_inventory->get_list();
$products = array_filter($products, function ($it){
    return !in_array($it->product_status, ['broken', 'used']);
});
$products = array_values($products);
$author_name = "";
$user = $system_api->query(
    'GET',
    'user',
    [
        'params' => ['id' => $current_user_id],
        'fields' => [
            'name'
        ]
    ], true
);

if(!is_wp_error($user)){
    $author_name = $user->data->user->name;
}
?>
<?php
// init _VAT & _DISCOUNT
?>
    <input type="hidden" value="<?php echo _VAT;?>" name="_vat" id="_vat" >
    <input type="hidden" value="<?php echo _DISCOUNT;?>" name="_discount" id="_discount" >

<div class="app-main__outer wrapper-booking-services">
    <div class="app-main__inner">
        <div class="tabs-animation">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">
                        <a href="#" class="btn btn-primary btn-circle btn-sm ttip">
                            <i class="fas fa-info-circle"></i>
                        </a>
                        Danh sách phiếu đăng ký</h1>
                </div>

            </div>

            <!-- DataTales Example -->
            <div class="resetpos card shadow mb-4">

                <div class="card-body pt-5">

                    <div class="row mb-0">
                        <div class="col-md-5 align-left">
                            <div class="btngroup">

                                <a href="#" class="btn btn-primary btn-icon-split btn-sm opendetail" data-toggle="modal" data-target="#modal-booking-service">
                                        <span class="icon text-white-50">
                                          +
                                        </span>
                                    <span class="text">Thêm mới</span>
                                </a>


	                            <?php if( \SME\Includes\Core\User::has_role($currentUser, ['admin']) ): ?>
		                            <?php
		                            $action_delete = [
			                            'action' => 'handle_ajax',
			                            'func' => 'delete_bookingservices',
		                            ];
		                            ?>
                                <a href="javascript:;" data-send="<?php esc_json_attr_e($action_delete); ?>" class="btn btn-danger btn-icon-split btn-sm btn-delete-bookingservices js-action-delete d-none"  data-table="#table-booking">
                                        <span class="icon text-white-50">
                                          <i class="fas fa-trash"></i>
                                        </span>
                                    <span class="text">Xoá</span>
                                </a>
                                <?php endif; ?>
                                <a href="/bookingservices?view=listing" class="btn btn-sm btn-light">
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
                                    <table id="table-booking" class="mb-0 table table-hover table-striped table-bordered dataTable dtr-inline">

                                    <thead>
                                            <tr role="row">
	                                            <?php if( \SME\Includes\Core\User::has_role($currentUser, ['admin']) ): ?>
                                                <th class="cb" ><input class="check-all" type="checkbox"></th>
                                                <?php endif; ?>
                                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 80px;" aria-label="Position: activate to sort column ascending">Mã phiếu</th>
                                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 110px;" aria-label="Start date: activate to sort column ascending">Người đăng ký</th>
                                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 110px;" aria-label="Salary: activate to sort column ascending">Khách hàng</th>
                                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 200px;" aria-label="Salary: activate to sort column ascending">Sự kiện triển lãm</th>
                                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 98px;" aria-label="Salary: activate to sort column ascending">Ngày đăng ký</th>
                                                <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1" style="width: 80px;" aria-label="Salary: activate to sort column ascending">Trạng thái</th>


                                            </tr>
                                            </thead>


                                            <tbody>
                                            <?php
                                            if(!empty($booking_list)){
                                                foreach ($booking_list as $item){
                                                    $data_send = [
                                                        'func' => 'get_once_bookingservices',
                                                        'action' => "handle_ajax",
                                                        'id' => $item['id'],
                                                    ];
//                                                    'fields' => [
//                                                        'id',
//                                                        'into_money',
//                                                        'author { name } ',
//                                                        'client { id, name } ',
//                                                        'booth',
//                                                        'events { id, title }',
//                                                        'status',
//                                                        'date'
//                                                    ]
                                                    if($item['flag'] != "main") {

                                                    ?>
                                                        <tr id="bookingservices-<?=$item['id']?>" role="row" class="odd">
                                                                <?php if( \SME\Includes\Core\User::has_role($currentUser, ['admin']) ): ?>
                                                            <td class="cb"><input type="checkbox" class="check-row"  name="bookingservices[]" value="<?=$item['id']?>"></td>
                                                                <?php endif; ?>
                                                            <td class="sorting_1">
                                                                <a href="#modal-booking-service" rel="modal:open" data-send="<?=esc_json_attr($data_send)?>" class="btn-icon-split btn-sm opendetail get-once" data-toggle="modal" data-target="#modal-booking-service">
                                                                    <?=$item["votes"]?>
                                                                </a>
                                                            </td>

                                                            <td style="width: 88px;"><?=$item["author"]["name"]?></td>
                                                            <td style="width: 88px;"><?=$item["client"]["name"]?></td>



                                                            <td style="width: 188px;"><?=isset($item["events"]["title"]) ? $item["events"]["title"]: "Không tìm thấy"?></td>
                                                            <td><?=$item["date"]?></td>
                                                            <td>
                                                                <?php
                                                                $ctatus="";
                                                                $percenStatus='0%';
                                                                $status_name = "";
                                                                if($item['status'] == "draft") {
                                                                    $status_name = "Chờ xác nhận";
                                                                    $ctatus="bg-warning";
                                                                    $percenStatus='68%';
                                                                }
                                                                if($item['status'] == "pending-draft") {
                                                                    $status_name = "Chờ triển khai";
                                                                    $ctatus="";
                                                                    $percenStatus='90%';
                                                                }
                                                                if($item['status'] == "approve") {
                                                                    $status_name = "Đã xác nhận";
                                                                    $ctatus="bg-success";
                                                                    $percenStatus='88%';
                                                                }
                                                                if($item['status']== "done") {
                                                                    $ctatus="bg-success bg-done";
                                                                    $status_name = "Hoàn tất";
                                                                    $percenStatus='100%';
                                                                }
                                                                ?>
                                                                <div class="progress ">
                                                                    <div class="progress-bar <?php echo $ctatus;?>" role="progressbar" style="width: <?php echo $percenStatus;?>; padding-left:6px;" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">
                                                                        <?php echo $status_name;?>
                                                                    </div>
                                                                </div>
                                                            </td>


                                                        </tr>

                                                    <?php
                                                    }
                                                }
                                            }
                                            ?>
                                            </tbody>
                                    </table>

                                    <?php /*
                                    <div class="card-body">
                                        <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">

                                        </table>
                                    </div>
 */ ?>
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

<div class="modal fade show" id="modal-booking-service" tabindex="-1" role="dialog" aria-labelledby="modal-booking-serviceTitle" aria-modal="true" data-backdrop="static">
    <form class="modal-dialog modal-lg frm-validation" method="POST" action="<?php echo admin_url('admin-ajax.php'); ?>">
        <input type="hidden" name="action" value="handle_ajax">
        <input type="hidden" name="func" value="register_bookingservices">
        <div class="modal-content booking form-content">

        </div>
    </form>
</div>

<script type="template/html" id="tpl-form-content">
    <div class="modal-header">PHIẾU ĐĂNG KÝ</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body booking_form_detail">

        <div class="main-card mb-2">

            <div class="">
                <div class="tab-content">
                    <!-- TAB 1 -->
                    <div id="step-1" class="tab-pane fade show active">
                        <div class="card-body">
                            <#
                            let _template = _.template(document.getElementById('tpl-repeater-product-item').innerHTML), html = '', idx = 0, total_money = 0;
                            _.each(list_of_equipment, function(item){
                            item.parent_id = id;
                            idx++;
                            item.idx = idx;
                            item.disabled = disabled;
                            total_money += item.quantity * item.product.product_pay
                            html += _template(item);
                            });
                            total_money = total_money + (total_money * vat / 100) - (total_money * discount / 100);
                            total_money = total_money + (total_money * per_charge / 100);
                            #>
                            <# if(id){ #>
                            <input type="hidden" id="booking-id" name="id" value="{{id}}">
                            <# } #>
                            <input type="hidden" name="vote" value="{{id}}">
                            <input type="hidden" name="user_id" value="{{ author.id }}">

                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group w-100">
                                        <label for="exampleEmail55">Tên triển lãm</label>
                                        <select class="form-control select2-basic select-events" name="events" data-is-validation="true" data-rule-required="true" data-msg-required="Vui lòng chọn tên triển lãm" {{ disabled }}>
                                            <option value="">- Chọn show -</option>
                                            <# if( events.hasOwnProperty('id') ){ #>
                                            <option value="{{ events.id }}" selected>{{ events.title }}</option>
                                            <# } #>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="form-client-type">Loại khách hàng</label>
                                        <#
                                        var client_type = "Không xác định";
                                        if(client.client_type){
                                            if(client.client_type == 'organizers'){
                                                client_type = "Ban tổ chức";
                                            }else if(client.client_type == 'contractors'){
                                                client_type = "Khách hàng";
                                            }else if(client.client_type == 'client'){
                                                client_type = "Nhà thầu";
                                            }
                                        }
                                        #>
                                        <input type="text" id="form-client-type" class="form-control" disabled value="{{ client_type }}">
                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="exampleEmail55">Ngày tạo</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend datepicker-trigger">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar-alt"></i>
                                                </div>
                                            </div>
                                            <input id="datepicker-bookingservices" type="text" class="form-control" data-toggle="datepicker-icon" placeholder="dd/mm/yyyy" name="date" required="" value="{{date}}" {{ disabled }}>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-3">
                                    <div class="position-relative form-group w-100">
                                        <label for="">Mã KH <span class="txtred">(*)</span></label>

                                        <select class="form-control select-client select-client-id" name="client" data-is-validation="true" data-rule-required="true" data-msg-required="Vui lòng chọn khách hàng" {{ disabled }}>>
                                            <option value="">- Chọn mã KH -</option>
                                            <# if( client.hasOwnProperty('id') ){ #>
                                            <option value="{{ client.id }}" selected>{{ client.client_id }}</option>
                                            <# } #>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group w-100">
                                        <div class="position-relative form-group">
                                            <label for="user-create">Số phiếu</label>
                                            <input id="user-votes" placeholder="" type="text" class="form-control" disabled value="{{ votes }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="position-relative form-group w-100">
                                        <label for="form-select-location-event">Gian hàng <span class="txtred">(*)</span></label>
                                        <select class="form-control select2-basic select-location-event" id="form-select-location-event" name="booth" {{ disabled }} data-is-validation="true" data-rule-required="true" data-msg-required="Chọn gian hàng">
                                            <option value="">- Chọn gian hàng -</option>
                                            <# if(booth){ #>
                                            <option value="{{ booth }}" selected>{{ booth }}</option>
                                            <# } #>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="position-relative form-group">
                                        <label for="form-vat">VAT(%)</label>
                                        <input name="vat" id="form-vat" placeholder="" type="text" class="form-control" value="{{ vat }}" {{ disabled }}>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="position-relative form-group">
                                        <label for="form-discount">Chiết khấu(%)</label>
                                        <input name="discount" id="form-discount" placeholder="" type="text" class="form-control" value="{{ discount }}" {{ disabled }}>
                                    </div>

                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-3">
                                    <div class="position-relative form-group w-100">
                                        <label for="form-customer-name">Tên KH <span class="txtred">(*)</span></label>
                                        <select class="form-control select-client select-client-name" data-is-validation="true" data-rule-required="true" data-msg-required="Vui lòng chọn khách hàng" {{ disabled }}>
                                            <option value="">- Chọn tên KH - </option>
                                            <# if( client.hasOwnProperty('id') ){ #>
                                            <option value="{{ client.id }}" selected>{{ client.name }}</option>
                                            <# } #>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-1 p-0" style="">
                                    <div class="position-relative form-group">
                                        <a href="/clients/?view=listing#" class="edit-client btn btn-primary" style="margin-top: 20px;padding: 4px 10px;">+</a>

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="position-relative form-group">
                                        <label for="user-create">Người lập phiếu</label>
                                        <input id="user-create" placeholder="" type="text" class="form-control" disabled value="{{ author.name }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="form-payment-type">Thanh toán</label>
                                        <input name="payment_type" id="form-payment-type" placeholder="Chuyển khoản" value="{{ payment_type }}" type="text" class="form-control" {{ disabled }}>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="form-total-money">Thành tiền</label>
                                        <input id="form-total-money" placeholder="" type="text" class="form-control" value="{{ Applications.helpers.convertStringToMoney(total_money) }}" {{ disabled }} disabled>
                                    </div>
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label>Địa chỉ liên lạc</label>
                                        <input id="author-address" disabled placeholder="" type="text" class="form-control address-client" value="{{ client.address }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label>Người liên hệ</label>
                                        <input  id="author-name" placeholder="" type="text" class="form-control name-client" disabled value="{{ client.contact_person_information.name }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label>Điện thoại</label>
                                        <input  id="author-phone" placeholder="" type="text" class="form-control phone-client" disabled value="{{ client.contact_person_information.phone }}">
                                    </div>
                                </div>


                            </div>
                            <div class="form-row">







                            </div>
                            <hr/>
                            <div class="">
                                <h3 class="card-title text-left">DANH MỤC THIẾT BỊ</h3>
                                <table id="table-product-bookingservice" class="mb-0 table table-borderless  table-product-repeater">
                                    <thead>
                                    <tr>
                                        <th class="text-left">MãSP</th>
                                        <th class="text-left">Tên sản phẩm</th>
                                        <th class="text-left">Đơn vị tính</th>
                                        <th class="text-center" style="width: 39px">SL</th>
                                        <th class="text-right" style="">Giá bán</th>
                                        <th class="text-right" style="">Thành tiền</th>
                                        <# if( disabled != 'disabled'){ #>
                                        <th></th>
                                        <# } #>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {{{ html }}}
                                    </tbody>
                                </table>
                                <# if(disabled != 'disabled'){ #>
                                <div class="pt-2">
                                    <a class="add-more-product" href="#">+ Chọn thêm thiết bị đăng ký</a>
                                </div>
                                <# } #>
                            </div>
		                    <?php if( \SME\Includes\Core\User::has_role($currentUser, ['admin', 'sale', 'hh', 'kt secc']) ): ?>
                                <#
                                if(id){ #>
                                <#
                                if(status == 'done' || status == 'approve'){
                                #>
                                <div class="row sec-publish mt-3">
                                    <hr>
                                    <h3 class="card-title text-left">HÌNH ẢNH XÁC NHẬN</h3>
                                    <div class="form-group w-100 form-group-image-upload">
                                        <div class="view-image-content position-relative <# if(image_confirm == ''){ #>d-none<# } #>">
                                            <a href="javascript:;" class="remove-image"><span aria-hidden="true">×</span></a>
                                            <#
                                            console.log(image_confirm);
                                            for( var im=0; im < image_confirm.length; im ++) {
                                            #>
                                            <div class="confrm_book_img" style="background: url({{image_confirm[im]}}) no-repeat 0 0;"></div>
                                            <#
                                            }
                                            #>
<!--
    <img src="{{image_confirm}}" class="view-image" style="width:300px; height: auto; border: 1px solid #ddd;padding: 3px;">-->
                                            <# if(image_confirm){ #>
                                            <input type="hidden" name="image_confirm" value="{{image_confirm}}">
                                            <# } #>
                                        </div>
                                        <label for="bk-image" class="lbl-bk-image cursor-pointer<# if(image_confirm != ''){ #> d-none<# } #>">
                                            <input id="bk-image" class="bk-image d-none" accept="image/*" type="file" name="image_upload">
                                        </label>
                                    </div>
                                </div>
                                <# } #>
                                <# } #>
		                    <?php endif; ?>
		                    <?php if( \SME\Includes\Core\User::has_role($currentUser, ['admin', 'sale']) ):
                                ?>
                                <#
                                if(id){ #>
                                <# if(status == 'pending-draft' || status == 'draft' ){
                                #>
                                <div class="sec-publish mt-3">
                                    <h3 class="card-title text-left">TRIỂN KHAI</h3>
                                    <div class="form-group w-100">
                                        <label>Kỹ thuật viên <span class="txtred">(*)</span></label>
                                        <select data-is-validation="true" data-rule-required="true" data-msg-required="Vui lòng chọn kỹ thuật viên" class="select-mailto" name="mailto"></select>
                                    </div>
                                    <div class="form-group w-100">
                                        <label>Ghi chú</label>
                                        <textarea name="note" class="form-control"></textarea>
                                    </div>
                                </div>
                                <# } #>
                                <# } #>
		                    <?php endif; ?>
                            <div class="divider"></div>
                            <div class="message-notification"></div>
                            <div class="clearfix btngroupstep">
                                <#
                                if(disabled != 'disabled'){
                                #>
                                <button type="submit" id="" class="btn-shadow btn-wide float-left btn-pill btn-hover-shine btn btn-primary">Gửi</button>
                                <#
                                }
                                #>
                                <# if(id){ #>
			                    <?php if( \SME\Includes\Core\User::has_role($currentUser, ['admin', 'hh', 'kt secc']) ): ?>
                                    <# if(status === 'approve'){ #>
                                    <button type="submit" id="" class="btn-shadow btn-wide float-left btn-pill btn-hover-shine btn btn-success" name="completed">Xác nhận hoàn tất</button>
                                    <# } #>
			                    <?php elseif( \SME\Includes\Core\User::has_role($currentUser, ['sale']) ): ?>
                                    <# if(status === 'pending-draft' || status == 'draft'){ #>
                                    <button type="submit" id="" class="btn-shadow btn-wide float-left btn-pill btn-hover-shine btn btn-primary mr-2" name="rollback">Xác nhận đơn hàng</button>
                                    <# } #>
                                <?php endif; ?>
                                <# } #>
                            </div>
                        </div>
                    </div>
                    <div id="step-2" class="tab-pane fade">
                        <# if( id ){ #>
                        <div class="">
                            <iframe id="print-preview" class="w-100 border-0" src="<?php echo get_the_permalink(get_page_by_path(__('Bookingservices', BOOKINGSERVICES_LANG_DOMAIN))) ?>?view=print&id={{id}}&theme_dir=<?=THEME_URL;?>"></iframe>
                            <a href="<?php echo get_the_permalink(get_page_by_path(__('Bookingservices', BOOKINGSERVICES_LANG_DOMAIN))) ?>?view=print&print&id={{id}}" target="_blank" type="button" class="btn-shadow btn-wide btn-pill btn-hover-shine btn btn-primary js-print-booking" style="color:#fff !important;">IN PHIẾU</a>
                        </div>
                        <# }else{ #>
                            <h3 class="my-4 text-center">Phiếu chưa được khởi tạo</h3>
                        <# } #>
                    </div>
                    <div id="step-3" class="tab-pane fade">
                        <# if(status === 'done'){ #>
                        <div class="no-results">
                            <div class="swal2-icon swal2-success swal2-animate-success-icon">
                                <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
                                <span class="swal2-success-line-tip"></span>
                                <span class="swal2-success-line-long"></span>
                                <div class="swal2-success-ring"></div>
                                <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
                                <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
                            </div>
                            <div class="results-title">Đã hoàn tất đăng ký.</div>
                        </div>
                        <# }else{ #>
                            <h3 class="my-4 text-center">Đang xử lý</h3>
                        <# } #>
                    </div>
                </div>
                <div id="smartwizard">
                    <ul class="forms-wizard nav nav-tabs">
                        <li class="active">
                            <a class="active" data-toggle="tab" href="#step-1">
                                <em>1</em><span>Đăng ký thông tin</span>
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#step-2">
                                <em>2</em><span>In hoá đơn & Xác nhận</span>
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#step-3">
                                <em>3</em><span>Hoàn thành</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</script>
<script type="text/html" id="tpl-repeater-product-item">
    <#
    let _idx = new Date().getTime();
    if( idx === 0 ){
        idx = _idx;
    }
    #>
    <tr>
        <td class="td-product-code">{{ product.product_code }}</td>
        <td class="td-select-product">
            <div class="w-100">
                <select {{ disabled }} class="form-control select2-product-table" name="products[{{idx}}][id]">
                    <option value="">--Chọn sản phẩm--</option>
                    <#
                    if( product.hasOwnProperty('product_title') ){ #>
                    <option selected value="{{product.id}}">{{ product.product_title }}</option>
                    <# }
                    #>
                </select>
            </div>
        </td>
        <td class="td-product-unit">
            <# if( product.product_unit ){ #>
                {{ product.product_unit }}
            <# }else{ #>
            --
            <# } #>
        </td>
        <td>
            <div class="" style="width: 80px">
                <input {{ disabled }} class="form-control input-product-quantity" type="text" data-value="{{ quantity }}" value="{{ Applications.helpers.convertStringToMoney(quantity) }}" name="products[{{idx}}][quantity]" data-type="currency" data-is-validation="true" data-rule-required="true" data-msg-required="Trường này không được để trống.">
            </div>
        </td>
        <td class="text-right td-product-price" data-value="{{ product.product_pay }}">
            {{ Applications.helpers.convertStringToMoney(product.product_pay) }}
        </td>
        <td class="text-right td-total">
            {{ Applications.helpers.convertStringToMoney(quantity * into_money) }}
        </td>
        <# if( disabled != 'disabled'){ #>
        <td> <a href="javascript:;" class="product-row-remove"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
        <# } #>
    </tr>
</script>
<script type="text/html" id="tpl-print-booking">
    <div class="modal-header">PHIẾU ĐĂNG KÝ</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <iframe id="print-preview" class="w-100 border-0" src="<?php echo get_the_permalink(get_page_by_path(__('Bookingservices', BOOKINGSERVICES_LANG_DOMAIN))) ?>?view=print&id={{id}}"></iframe>
        <a href="<?php echo get_the_permalink(get_page_by_path(__('Bookingservices', BOOKINGSERVICES_LANG_DOMAIN))) ?>?view=print&print&id={{id}}" target="_blank" type="button" class="btn-shadow btn-wide btn-pill btn-hover-shine btn btn-primary js-print-booking">IN PHIẾU</a>
    </div>
</script>
<?php
wp_enqueue_script('bookingservices-js', BOOKINGSERVICES_MODULE_URL . 'frontend/js/bookingservices.js', ['jquery', 'bootstrap-modal-js', 'jquery-ui-core', 'jquery-ui-datepicker', 'moment-front', 'underscore', 'backbone', 'jquery-form', 'toastr-js'], BOOKINGSERVICES_VERSION, true);
wp_localize_script('bookingservices-js', 'BOOKING', [
    'clients' => $list_client,
    'products' => $products,
    'users' => $get_users,
    'events' => $events,
    'author_name' => $author_name,
    'user' => $currentUser,
    'client_type' => $client_type,
]);
