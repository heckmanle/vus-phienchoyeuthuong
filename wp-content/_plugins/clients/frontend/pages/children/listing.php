<?php
/**
 * The template for displaying all pages
 */
global $core_clients_class, $system_api, $currentUser;
$list = $core_clients_class->get_list();
$list_branch = $core_clients_class->list_branch;
$client_type = $core_clients_class->client_type;

$more_author = $system_api->parseFields(['name']);
$more_contact_person_information =$system_api->parseFields(['name', 'gender', 'birthdate', 'phone', 'email', 'title']);
$result = [];
$query = $system_api->query(
    'GET',
    'users',
    [
        'fields' => [
            'id',
            'name',
            'phone',
            'email ',
            'role_title',
            'roles { role_name }',
            'birthdate',
        ]
    ], true
);
$listAllusers=$query->data->users;
if (!\SME\Includes\Core\User::has_role($currentUser, ['btc', 'hh', 'kt secc'])){
?>
    <!----------------- End of Sidebar -->
<div class="app-main__outer layout-clients">
        <div class="app-main__inner">

            <div class="row">
                <div class="">
                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">
                        <a href="#" class="btn btn-primary btn-circle btn-sm ttip">
                            <i class="fas fa-info-circle"></i>
                        </a>
                        Danh sách khách hàng</h1>
                </div>

            </div>

            <div class="main-card mb-2 card">
                <div class="resetpos card-body pt-5">

                    <div class="row mb-0">
                        <div class="col-md-5 align-left">
                            <div class="btngroup">

                                <a href="#" class="btn btn-primary btn-icon-split btn-sm opendetail" data-toggle="modal" data-target="#exampleModalLong">
                                        <span class="icon text-white-50">
                                          +
                                        </span>
                                    <span class="text">Thêm mới</span>
                                </a>

<!--                                <a href="#" data-toggle="modal" data-target="#modal-import-clients" class="btn btn-success btn-icon-split btn-sm">-->
<!--                                                            <span class="icon text-white-50">-->
<!--                                                              <i class="fas fa-file-import"></i>-->
<!--                                                            </span>-->
<!--                                    <span class="text">Nhập từ Excel</span>-->
<!--                                </a>-->

                                <a href="javascript:;" class="btn btn-danger btn-icon-split btn-sm btn-delete-clients" id="remfrm">
                                        <span class="icon text-white-50">
                                          <i class="fas fa-trash"></i>
                                        </span>
                                    <span class="text">Xoá</span>
                                </a>

                                <a href="/clients/?view=listing" class="btn btn-sm btn-light">
                                    <i class="fas fa-sync-alt"> </i>
                                </a>


                            </div>
                        </div>


                    </div>
                    <div class="dataTables_wrapper dt-bootstrap4">

                        <table id="example" class="mb-0 table table-hover table-striped table-bordered dataTable dtr-inline">
                            <thead>
                            <tr>
                                <th class=""><input class="checkall" type="checkbox"></th>
                                <th>Mã KH</th>
                                <th class="sorting">Tên đầy đủ</th>
                                <th>Điện thoại</th>
                                <th>Địa chỉ</th>
                                <th>Mã số thuế</th>
                                <th>Loại khách hàng</th>
                                <th class="sorting_desc">Người tạo</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                            if(!empty($list)){
                                foreach ($list as $item){
                                    $author = 'Không xác định';
                                    if(!empty($item->author)){
                                        $author = $item->author->name;
                                    }
                                    $data_send = [
                                        'func' => 'get_client',
                                        'action' => "handle_ajax",
                                        'id' => $item->id,
                                    ];
                                    ?>
                                    <tr class="client-<?=$item->id?>">
                                        <td class="cb"><input class="check-item" type="checkbox" name="clients[]" value="<?=$item->id?>"></td>
                                        <td>
                                            <a href="#" class="edit-client" data-toggle="modal" data-target="#exampleModalLong" data-send="<?=esc_json_attr($data_send)?>"><?=$item->client_id?></a>
                                        </td>
                                        <td>
                                            <a href="#" class="edit-client" data-toggle="modal" data-target="#exampleModalLong" data-send="<?=esc_json_attr($data_send)?>"><?=$item->name?></a>
                                        </td>
                                        <td><?=$item->phone?></td>
                                        <td><?=$item->address?></td>
                                        <td><?=$item->tax_code?></td>
                                        <td><?=$client_type[$item->client_type]?></td>
                                        <td><a href="#"><?=$author?></a></td>
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
            <!-- END TABLE -->

            <!-- END CONTENT -->
        </div>
    </div>
<?php
} // end set roles
?>
<div class="modal fade show" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-modal="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Thông tin khách hàng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="main-card mb-2">

                    <div class="">
                        <div class="tab-content">
                                <!-- TAB 1 -->
                                <form id="frm-handle-clients" class="frmmodal" action="#" method="POST">
                                </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modal-import-clients" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form  id="frm-import-clients">
                <input type="hidden" name="action" value="handle_ajax">
                <input type="hidden" name="func" value="import_clients">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Nhập từ Excel</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div>
                        <span>Xử lý dữ liệu (Tải về File mẫu: <a href="<?=CLIENTS_MODULE_URL?>templates/Mau-file-nhap-khach-hang.xls">Excel</a>) </span>
                    </div>
                    <div>
                        <label for="import_file_product">Chọn tập tin</label>
                        <input type="file" name="import" id="import_file_product">
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary import-clients">Nhập vào</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="template/html" id="tpl_add_row_clients_form">
        <input type="hidden" name="action" value="handle_ajax">
        <input type="hidden" name="func" value="handle_clients">
        <input type="hidden" name="id" value="{{id}}" autocomplete="off">
        <div class=" row text-center " id="">
            <div class="col-md-12 position-relative custome_type">
                <div class="position-relative form-group text-left">
                    <#
                    let checked_organizers = '';
                    if(client_type == 'organizers'){
                        checked_organizers = 'checked';
                    }
                    #>
                    <input name="client_type" id="Check_organizers" type="radio" class="" value="organizers" {{checked_organizers}} required="">
                    <label for="Check_organizers" class="form-check-label">Ban tổ chức</label>
                </div>
                <div class="position-relative form-group text-left">
                    <#
                    let checked_contractors = '';
                    if(client_type == 'contractors'){
                    checked_contractors = 'checked';
                    }
                    #>
                    <input name="client_type" id="Check_contractors" type="radio" class="" value="contractors" {{checked_contractors}} required="">
                    <label for="Check_contractors" class="">Nhà thầu</label>
                </div>
                <div class="position-relative form-group text-left">
                    <#
                    let checked_client = '';
                    if(client_type == 'client'){
                    checked_client = 'checked';
                    }
                    #>

                    <input name="client_type" id="Check_client" type="radio" class="" value="client" {{checked_client}} required="">
                    <label for="Check_client" class="form-check-label">Khách hàng</label>

                </div>

            </div>
        </div>

        <div class="form-row">
            <div class="col-md-4">
                <div class="position-relative form-group"><label for="exampleCity" class="">Mã KH <span class="txtred">(*)</span></label>
                    <input name="client_id" id="exampleCity" type="text" class="form-control" value="{{client_id}}" required="">
                </div>
            </div>
            <div class="col-md-8">
                <div class="position-relative form-group"><label for="exampleCity" class="">Tên đầy đủ <span class="txtred">(*)</span></label>
                    <input name="name" id="exampleCity" type="text" class="form-control" value="{{name}}" required>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-4">
                <div class="position-relative form-group">
                    <label for="exampleCity" class="">Phân nghành</label>
                    <select class="form-control" name="branch" required>
                        <?php
                        foreach ($list_branch as $key => $item){
                            ?>
                            <#
                            var selected = '';
                            if( branch == '<?=$key?>' ){
                            selected = 'selected';
                            }
                            #>
                            <option value="<?=$key?>" {{selected}}><?=$item?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="position-relative form-group">
                    <label for="exampleState" class="">Điện thoại <span class="txtred">(*)</span></label>
                    <input name="phone" id="exampleState" type="text" class="form-control" value="{{phone}}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="position-relative form-group">
                    <label for="exampleZip" class="">Email <span class="txtred">(*)</span></label>
                    <input name="email" id="exampleZip" type="email" class="form-control" value="{{email}}">
                </div>
            </div>

        </div>
        <div class="form-row">
            <div class="col-md-12">
                <div class="position-relative form-group">
                    <label for="exampleAddress" class="">Địa chỉ liên lạc</label>
                    <input name="address" id="exampleAddress" placeholder="10 Đường số 8 .." type="text" class="form-control" value="{{address}}">
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12">
                <div class="position-relative form-group">
                    <label for="exampleAddress2" class="">Địa chỉ xuất hoá đơn</label>
                    <input name="billing_address" id="exampleAddress2" placeholder="123 Đường số 8 .." type="text" class="form-control" value="{{billing_address}}">
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-3">
                <div class="position-relative form-group">
                    <label for="exampleAddress2" class="">Mã số thuế</label>
                    <input name="tax_code" id="exampleAddress2" placeholder="" type="text" class="form-control" value="{{tax_code}}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="position-relative form-group">
                    <label for="exampleAddress2" class="">Website</label>
                    <input name="website" id="exampleAddress2" placeholder="www." type="text" class="form-control" value="{{website}}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="position-relative form-group">
                    <label for="exampleAddress2" class="" style="padding-top: 27px;">Logo khách hàng</label>
                    <input type="file" id="fileUpload" class="d-none" name="avatar_file">
                    <label for="fileUpload" class="dropzone1 d-flex justify-content-center align-items-center flex-wrap">
                        <#
                        if(avatar){
                        #>
                        <img id="img" class="img-upload" src="{{avatar}}">
                        <input type="hidden" name="avatar" value="{{avatar}}">
                        <#
                        }else{
                        #>
                        <div class="dz-default dz-message">
                            <i class="s-50 fas fa-camera"></i>
                            <div>Ảnh đại diện</div>
                            <img class="img-upload" id="img" src="">
                        </div>
                        <#
                        }
                        #>
                    </label>
                </div>
            </div>

        </div>

        <div class="form-row">
            <div class="col-md-12">
                <label for="exampleAddress2" class="">Ghi chú</label>
                <input name="note" id="exampleAddress2" placeholder=".." type="text" class="form-control" value="{{note}}">
            </div>
        </div>

        <p class="mt-3">THÔNG TIN NGƯỜI LIÊN HỆ</p>
        <#
        if(contact_person_information){
        #>
        <div class="gbr">
            <div class="form-row">
                <div class="col-md-4">
                    <div class="position-relative form-group"><label for="exampleCity" class="">Tên đầy đủ <span class="txtred">(*)</span></label><input name="contact_person_information[name]" id="exampleCity" type="text" class="form-control" value="{{contact_person_information.name}}"></div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group"><label for="exampleState" class="">Giới tính </label><input name="contact_person_information[gender]" id="exampleState" type="text" class="form-control" value="{{contact_person_information.gender}}"></div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="exampleZip" class="">Ngày sinh</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="datepicker-client" data-toggle="datepicker-icon" placeholder="dd/mm/yyyy" name="contact_person_information[birthdate]" value="{{contact_person_information.birthdate}}">
                            <div class="input-group-prepend datepicker-trigger ">
                                <div class="input-group-text">
                                    <i class="fa fa-calendar-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="form-row">

                <div class="col-md-4">
                    <div class="position-relative form-group"><label for="exampleState" class="">Điện thoại <span class="txtred">(*)</span></label><input name="contact_person_information[phone]" id="exampleState" type="text" class="form-control" value="{{contact_person_information.phone}}"></div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group"><label for="exampleZip" class="">Email</label><input name="contact_person_information[email]" id="exampleZip" type="email" class="form-control" value="{{contact_person_information.email}}"></div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group"><label for="exampleCity" class="">Chức danh </label><input name="contact_person_information[title]" id="exampleCity" type="text" class="form-control" value="{{contact_person_information.title}}"></div>
                </div>

            </div>

        </div>
        <#
        } else {
        #>
        <div class="">
            <div>
                <div>
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="exampleCity" class="">Tên đầy đủ <span class="txtred">(*)</span></label>
                                <input name="contact_person_information[name]" id="exampleCity" type="text" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="exampleState" class="">Giới tính </label>
                                <select class="form-control" name="contact_person_information[gender]">
                                    <option value="Nam">Nam</option>
                                    <option value="Nữ">Nữ</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="exampleZip" class="">Ngày sinh</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="datepicker-client" placeholder="dd/mm/yyyy" name="contact_person_information[birthdate]" value="">
                                    <div class="input-group-prepend datepicker-trigger ">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="exampleState" class="">Điện thoại <span class="txtred">(*)</span></label>
                                <input name="contact_person_information[phone]" id="exampleState" type="text" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="exampleZip" class="">Email</label>
                                <input name="contact_person_information[email]" id="exampleZip" type="email" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="exampleCity" class="">Chức danh </label>
                                <input name="contact_person_information[title]" id="exampleCity" type="text" class="form-control" value="">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <#
        }
        #>
        <div class="users_of_client account-client-repeater">
            <p class="mt-3">TÀI KHOẢN KHÁCH HÀNG</p>
            <div data-repeater-list="account-client">
        <#
        if(contact_person_informations && contact_person_informations.length > 0){
        _.each(contact_person_informations, function(item){
        #>

                <div data-repeater-item class="personal_group">
                    <div class="d-flex justify-content-end">
                        <a data-repeater-delete href="javascript:;">
                            X
                            <!--                            <i class="fa fa-trash" aria-hidden="true"></i>-->
                        </a>
                    </div>
                    <div class="mb-2 p-2 shadow">
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="exampleCity" class="">Tên tài khoản <span class="txtred">(*)</span></label>
                                    <select class="form-control select2-client select-user select-user-by-name" name="user">
                                        <option value="">Tài khoản</option>
                                        <?php
                                        if(!empty($listAllusers)){
                                            foreach ($listAllusers as $user){
                                                ?>
                                                <#
                                                var selected = '';
                                                if( item.id == '<?=$user->id?>' ){
                                                selected = 'selected';
                                                }
                                                #>
                                                <option value="<?=$user->id?>" {{selected}}><?=$user->name?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="exampleState" class="">Số điện thoại</label>
                                    <select class="form-control select2-client select-user select-user-by-phone" name="phone">
                                        <option value="">Số điện thoại</option>
                                        <?php
                                        if(!empty($listAllusers)){
                                            foreach ($listAllusers as $user){
                                                ?>
                                                <#
                                                var selected = '';
                                                if( item.id == '<?=$user->id?>' ){
                                                selected = 'selected';
                                                }
                                                #>
                                                <option value="<?=$user->id?>" {{selected}}><?=$user->phone?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="exampleZip" class="">Email</label>
                                    <input name="" id="exampleZip" type="text" class="form-control email-review" value="{{item.email}}" disabled >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <#
        });
        }else{
        #>
            <div data-repeater-list="account-client">
                <div data-repeater-item class="personal_group">
                    <div class="d-flex justify-content-end">
                        <a data-repeater-delete href="javascript:;">
                            X
<!--                            <i class="fa fa-trash" aria-hidden="true"></i>-->
                        </a>
                    </div>
                <div class="mb-2 p-2 shadow">
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="exampleCity" class="">Tên tài khoản <span class="txtred">(*)</span></label>
                                <select class="form-control select2-client select-user select-user-by-name" name="user">
                                    <option value="">Tài khoản</option>
                                    <?php
                                    if(!empty($listAllusers)){
                                        foreach ($listAllusers as $user){
                                            ?>
                                            <option value="<?=$user->id?>"><?=$user->name?></option>
                                        <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="exampleState" class="">Số điện thoại</label>
                                <select class="form-control select2-client select-user select-user-by-phone" name="phone" >
                                    <option value="">Số điện thoại</option>
                                    <?php
                                    if(!empty($listAllusers)){
                                        foreach ($listAllusers as $user){
                                            ?>
                                            <option value="<?=$user->id?>"><?=$user->phone?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="exampleZip" class="">Email</label>
                                <input name="" id="exampleZip" type="text" class="form-control email-review" disabled >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <#
        }
        #>
            </div>
        </div>
        <div class="form-row">
            <a href="javascript:;" class="btn_add_more_users">+ Thêm tài khoản</a>
        </div>
        <button class="mt-3 btn-shadow btn-wide btn-pill btn-hover-shine btn btn-primary">Cập nhật</button>

</script>
<?php
wp_enqueue_script( 'underscore' );
wp_enqueue_script('clients-js', CLIENTS_MODULE_URL . 'frontend/js/clients.js', ['jquery', 'jquery-repeater', 'bootstrap-modal-js', 'jquery-ui-core', 'jquery-ui-datepicker', 'underscore', 'backbone', 'jquery-form', 'toastr-js'], CLIENTS_VERSION, true);
wp_localize_script('clients-js', 'CLIENTS', [
    'AJAX_URL' => admin_url("admin-ajax.php"),
]);
?>
