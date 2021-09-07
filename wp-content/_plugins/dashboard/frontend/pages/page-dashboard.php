<?php
/**
 * The template for displaying all pages
 */
get_header(); ?>
    <!-- Sidebar -->
    <?php //get_sidebar( 'left' ); ?>

<?php
global $core_dashboard_class;
$events = $core_dashboard_class->get_events();
?>

    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="tabs-animation">
                <div class="row mb-3">
                    <div class="align-left">
                        <div class="mt-1 pl-0">
                            <h3 class="text-left">Dashboard</h3>
                        </div>
                    </div>

                </div>
                <div class="main-card ">
                    <div class="row">
                        <div class="col-md-3 mb-3 pl-1 align-center">
                            <a href="<?php echo SITE_URL; ?>/posts-be-builder">
                                <div class="bx ">
                                    <img class="icobox" src="<?php echo SITE_URL; ?>/wp-content/uploads/lib-icons/icon-posts.svg" alt="">
                                    <h4>Posts</h4>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3 pl-1 align-center">
                            <a href="<?php echo SITE_URL; ?>/pages-be-builder">
                                <div class="bx ">
                                    <img class="icobox" src="<?php echo SITE_URL; ?>/wp-content/uploads/lib-icons/icon-pages.svg" alt="">
                                    <h4>Pages</h4>
                                </div>
                            </a>
                        </div>
<!--                        <div class="col-md-3 mb-3 pl-1 align-center">-->
<!--                            <a href="#">-->
<!--                                <div class="bx ">-->
<!--                                    <img class="icobox" src="--><?php //echo SITE_URL; ?><!--/wp-content/uploads/lib-icons/icon-frmdata.svg" alt="">-->
<!--                                    <h4>Forms data</h4>-->
<!--                                </div>-->
<!--                            </a>-->
<!--                        </div>-->
<!--                        <div class="col-md-3 mb-3 pl-1 align-center">-->
<!--                            <a href="#">-->
<!--                                <div class="bx ">-->
<!--                                    <img class="icobox" src="--><?php //echo SITE_URL; ?><!--/wp-content/uploads/lib-icons/icon-permission.svg" alt="">-->
<!--                                    <h4>Roles & Permission</h4>-->
<!--                                </div>-->
<!--                            </a>-->
<!--                        </div>-->
                        <div class="col-md-3 mb-3 pl-1 align-center">
                            <a href="<?php echo site_url('/usersmanagement/?view=listing'); ?>">
                                <div class="bx ">
                                    <div class="bx ">
                                        <img class="icobox" src="<?php echo SITE_URL; ?>/wp-content/uploads/lib-icons/icon-users.svg" alt="">
                                        <h4>Users</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3 pl-1 align-center">
                            <a href="<?php echo site_url('/global-settings'); ?>">
                                <div class="bx ">
                                    <div class="bx ">
                                        <img class="icobox" src="<?php echo SITE_URL; ?>/wp-content/uploads/lib-icons/icon-glbsettings.svg" alt="">
                                        <h4>Global setting</h4>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>


            </div>
        </div>
    </div>

<?php
wp_enqueue_script('dashboard-js', DASHBOARD_MODULE_URL . '/frontend/js/dashboard.js', ['jquery', 'bootstrap-modal-js', 'jquery-ui-core', 'jquery-ui-datepicker', 'underscore', 'backbone', 'jquery-form', 'toastr-js'], DASHBOARD_VERSION, true);
?>

<?php get_footer();
