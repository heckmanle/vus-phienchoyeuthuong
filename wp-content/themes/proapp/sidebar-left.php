<?php
global $wp, $currentUser;

?>
<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">

            <ul class="vertical-nav-menu">
                <li class="<?php if($wp->request == 'dashboard' || $wp->request == '') echo 'mm-active';?>">
                    <a href="<?php echo SITE_URL; ?>/dashboard/">
                        <i class="metismenu-icon pe-7s-graph"></i>Dashboard
                    </a>
                </li>
                <?php /*
                <li class="<?php if($wp->request == 'notification') echo 'mm-active';?>">
                    <a href="<?php echo SITE_URL; ?>/notification?view=listing">
                        <i class="metismenu-icon icon text-danger icon-anim-pulse ion-android-notifications"></i>
                        <span class="nonnumber">6</span>
                        Thông báo
                    </a>
                </li> */ ?>

                <?php if(!\SME\Includes\Core\User::has_role($currentUser, ['hh', 'kt secc'])){ ?>
                <li class="<?php if($wp->request == 'events') echo 'mm-active';?>">
                    <a href="<?php echo SITE_URL; ?>/events/?view=listing">
                        <i class="metismenu-icon pe-7s-culture"></i>
                        Sự kiện triển lãm
                    </a>
                </li>
                <?php } ?>

                <li class="<?php if($wp->request == 'bookingservices') echo 'mm-active';?>">
                    <a href="<?php echo SITE_URL; ?>/bookingservices?view=listing">
                        <i class="metismenu-icon pe-7s-note2"></i>
                        Phiếu đăng ký
                    </a>
                </li>

                <?php if(!\SME\Includes\Core\User::has_role($currentUser, ['btc', 'hh', 'kt secc'])){ ?>
                <li class="<?php if($wp->request == 'clients') echo 'mm-active';?>">
                    <a href="<?php echo SITE_URL; ?>/clients/?view=listing">
                        <i class="metismenu-icon pe-7s-id"></i>
                        Khách hàng
                    </a>
                </li>
                <?php } ?>

                <li class="<?php if($wp->request == 'locations') echo 'mm-active';?>">
                    <a href="<?php echo SITE_URL; ?>/locations/?view=listing">
                        <i class="metismenu-icon pe-7s-map"></i>
                        Sơ đồ vị trí
                    </a>
                </li>

                <?php if(!\SME\Includes\Core\User::has_role($currentUser, ['btc'])){ ?>

                <li class=" <?php if($wp->request == 'inventory') echo 'mm-active';?> ">
                    <a href="#">
                        <i class="metismenu-icon pe-7s-server"></i>
                        Kho
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="dnone">
                        <li>
                            <a href="<?php echo SITE_URL; ?>/inventory/?view=listing">
                                <i class="metismenu-icon "></i>
                                Danh sách kho
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SITE_URL; ?>/inventory/?view=product-category">
                                <i class="metismenu-icon"></i>
                                Nhóm sản phẩm
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SITE_URL; ?>/inventory/?view=products">
                                <i class="metismenu-icon"></i>
                                Danh sách sản phẩm
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SITE_URL; ?>/inventory/?view=QRCode">
                                <i class="metismenu-icon"></i>Mã QR
                            </a>
                        </li>

                    </ul>
                </li>
                <?php } ?>

                <li class=" <?php if($wp->request == 'reports') echo 'mm-active';?>">
                    <a href="<?php echo SITE_URL; ?>/reports?view=listing">
                        <i class="metismenu-icon pe-7s-graph2"></i>
                        Báo cáo
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="dnone">
                        <?php if(!\SME\Includes\Core\User::has_role($currentUser, ['hh', 'kt secc'])){ ?>
                        <li>
                            <a href="<?php echo SITE_URL; ?>/reports/?view=events">
                                <i class="metismenu-icon "></i>
                                Báo cáo triển lãm
                            </a>
                        </li>
                        <?php } ?>
                        <?php if(!\SME\Includes\Core\User::has_role($currentUser, ['btc'])){ ?>
                        <li>
                            <a href="<?php echo SITE_URL; ?>/reports/?view=inventory">
                                <i class="metismenu-icon "></i>
                                Báo cáo kho
                            </a>
                        </li>
                        <?php } ?>
                        <?php if(!\SME\Includes\Core\User::has_role($currentUser, ['btc', 'hh', 'kt secc'])){ ?>
                        <li>
                            <a href="<?php echo SITE_URL; ?>/reports/?view=overview">
                                <i class="metismenu-icon "></i>
                                Báo cáo tổng quan
                            </a>
                        </li>
                        <?php } ?>

                    </ul>
                </li>
                <?php if(!\SME\Includes\Core\User::has_role($currentUser, ['btc', 'hh', 'kt secc'])){ ?>
                <li class="<?php if($wp->request == 'usersmanagement') echo 'mm-active';?>">
                    <a href="<?php echo SITE_URL; ?>/usersmanagement/?view=listing">
                        <i class="metismenu-icon pe-7s-users"></i>
                        Quản lý người dùng
                    </a>
                </li>
                <?php } ?>

            </ul>
        </div>
    </div>
</div>