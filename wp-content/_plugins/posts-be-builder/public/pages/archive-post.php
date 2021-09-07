<?php
add_filter('pre_get_document_title', function ($title){
	$title .= ' ' . __('Posts be builder', PAGES_BE_BUILDER_LANG_DOMAIN);
	return $title;
}, 100);
$page = get_query_var('page', 1);

$post_categories = \DIVI\Includes\Core\PostCategory::postCategories();
if( is_wp_error($post_categories) || empty($post_categories) ){
	$post_categories = [
        ['id' => 0, 'cate_title' => 'Choose Post Category']
    ];
}

get_header();

?>
<noscript>
    <style>
        [data-simplebar] {
            overflow: auto;
        }
    </style>
</noscript>
<script>
    const POSTSBB_IMAGE_LOADING = {
        message: '<div class="ball-clip-rotate-multiple"> <div></div><div></div></div>',
    };
    const POSTSBB_AJAX_URL = "<?php echo POSTS_BE_BUILDER_AJAX_URL; ?>";
</script>
<div class="layout-postsbb-create w-100">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-3">

                <div class="d-md-flex justify-content-md-between align-items-center">
                    <h3 class="mb-3 pb-0 d-flex align-items-center"><img width="20" class="mr-2 icon-my-rep" src="<?php echo POSTS_BE_BUILDER_PUBLIC_URL . 'images/icons/icon-my-rep.svg' ?>"><span>Posts</span></h3>
                    <a href="javascript:;" class="btn-pro-create mb-3">
                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.6757 1.09375L12.1789 0.590496C12.0454 0.457025 11.8644 0.382041 11.6757 0.382041C11.4869 0.382041 11.3059 0.457025 11.1724 0.590496L11.6757 1.09375ZM13.6887 3.10678L14.1919 3.61003C14.4699 3.33209 14.4699 2.88146 14.1919 2.60352L13.6887 3.10678ZM6.6431 10.1524V10.8641C6.83186 10.8641 7.01289 10.7891 7.14636 10.6556L6.6431 10.1524ZM4.63008 8.13933L4.12682 7.63608C3.99335 7.76955 3.91837 7.95057 3.91837 8.13933H4.63008ZM4.63008 10.1524H3.91837C3.91837 10.5454 4.23701 10.8641 4.63008 10.8641V10.1524ZM13.8108 13.9045V14.6163C14.2039 14.6163 14.5225 14.2976 14.5225 13.9045H13.8108ZM1 13.9045H0.288289C0.288289 14.2976 0.606933 14.6163 1 14.6163L1 13.9045ZM1 1.09375V0.382039C0.606933 0.382039 0.288289 0.700683 0.288289 1.09375L1 1.09375ZM11.1724 1.59701L13.1854 3.61003L14.1919 2.60352L12.1789 0.590496L11.1724 1.59701ZM13.1854 2.60352L6.13985 9.6491L7.14636 10.6556L14.1919 3.61003L13.1854 2.60352ZM5.13334 8.64259L12.1789 1.59701L11.1724 0.590496L4.12682 7.63608L5.13334 8.64259ZM6.6431 9.44064H4.63008V10.8641H6.6431V9.44064ZM5.34179 10.1524V8.13933H3.91837V10.1524H5.34179ZM13.8108 13.1928H1V14.6163H13.8108V13.1928ZM1.71171 13.9045V1.09375H0.288289V13.9045H1.71171ZM1 1.80546H7.4054V0.382039H1V1.80546ZM13.0991 7.49915V13.9045H14.5225V7.49915H13.0991Z" fill="#000"/>
                        </svg>
                        <span class="pl-2">Add</span>
                    </a>
                </div>
                <div class="postsbb-product-list">
                    <div class="position-relative" id="postsbb-product-tabs-content">
                        <div class="" id="postsbb-myposts">
                            <div class="postsbb-myposts-content" data-simplebar data-simplebar-auto-hide="false">
                                <ul class="" >

                                </ul>
                            </div>
                            <div class="postsbb-section-pagination"></div>
                        </div>
                        <div class="tab-pane fade" id="postsbb-myposts-favorite" role="tabpanel" aria-labelledby="profile-tab">... ....</div>
                    </div>
                </div>
            </div>
            <div class="col-md-9" id="postsbb-pro-detail-content" data-simplebar data-simplebar-auto-hide="false">
                <form class="postsbb-form-product position-relative" action="<?php echo REAL_ESTATE_PRODUCTS_AJAX_URL; ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="postsbb_handle_ajax">
                    <input type="hidden" name="func" value="postsbb_post_handle">
                    <div style="padding-right: 15px;">
                        <div class="postsbb-pro-detail pt-3">
                            <p class="alert_aligncenter">Please add new post or choose post for update ..</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script id="tpl-postsbb-detail" type="text/html">
    <div class="row">
        <div class="col-md-9">
            <label>Title</label>
            <div class="postsbb-pro-title-content form-group d-flex align-items-center justify-content-between">
                <div class="form-group position-relative w-100 pr-3 overflow-hidden">
                    <input type="hidden" name="id" value="{{ id }}">
                    <label class="lbl-pro-title mb-0<# if(!id){ #> d-none<# } #>" for="pro-title">{{ post_title }}</label>
                    <input type="text" class="form-control input-pro-title material-form-control px-0<# if(id){ #> d-none<# } #>" value="{{ post_title }}" id="post_title" name="post_title" data-is-validation="true" required>
                    <span class="focus-border"></span>
                </div>
                <a href="javascript:;" class="js-edit-pro-title postsbb-edit-pro-title">
                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g filter="url(#filter0_d)">
                            <path d="M16.1981 2.67627L16.7014 2.17302C16.5679 2.03954 16.3869 1.96456 16.1981 1.96456C16.0094 1.96456 15.8283 2.03954 15.6949 2.17302L16.1981 2.67627ZM18.2111 4.68929L18.7144 5.19255C18.9923 4.91461 18.9923 4.46398 18.7144 4.18604L18.2111 4.68929ZM11.1656 11.7349V12.4466C11.3543 12.4466 11.5353 12.3716 11.6688 12.2381L11.1656 11.7349ZM9.15254 9.72185L8.64929 9.2186C8.51581 9.35207 8.44083 9.53309 8.44083 9.72185H9.15254ZM9.15254 11.7349H8.44083C8.44083 12.1279 8.75947 12.4466 9.15254 12.4466V11.7349ZM18.3333 15.4871V16.1988C18.7263 16.1988 19.045 15.8801 19.045 15.4871H18.3333ZM5.52246 15.4871H4.81075C4.81075 15.8801 5.12939 16.1988 5.52246 16.1988V15.4871ZM5.52246 2.67627V1.96456C5.12939 1.96456 4.81075 2.2832 4.81075 2.67627H5.52246ZM15.6949 3.17953L17.7079 5.19255L18.7144 4.18604L16.7014 2.17302L15.6949 3.17953ZM17.7079 4.18604L10.6623 11.2316L11.6688 12.2381L18.7144 5.19255L17.7079 4.18604ZM9.6558 10.2251L16.7014 3.17953L15.6949 2.17302L8.64929 9.2186L9.6558 10.2251ZM11.1656 11.0232H9.15254V12.4466H11.1656V11.0232ZM9.86425 11.7349V9.72185H8.44083V11.7349H9.86425ZM18.3333 14.7754H5.52246V16.1988H18.3333V14.7754ZM6.23417 15.4871V2.67627H4.81075V15.4871H6.23417ZM5.52246 3.38798H11.9279V1.96456H5.52246V3.38798ZM17.6215 9.08167V15.4871H19.045V9.08167H17.6215Z" fill="#0078CE"/>
                        </g>
                        <defs>
                            <filter id="filter0_d" x="-0.612793" y="0.540527" width="25.0811" height="25.0811" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"/>
                                <feOffset dy="4"/>
                                <feGaussianBlur stdDeviation="2"/>
                                <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"/>
                                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"/>
                                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"/>
                            </filter>
                        </defs>
                    </svg>
                    <span class="pl-1">Edit</span>
                </a>
            </div>
            <div class="postsbb-builder-layout pt-3<# if(!id){ #> d-none<# } #>">
                <h3 class="font-weight-bold">Editor</h3>
                <p>You can add more content by default layout or drag/drop below</p>
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ edit_post_url }}" target="_blank" class="postsbb-drag-drop-layout d-block">
                            <div class="d-flex align-items-center">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="18" height="18" transform="matrix(0 1 1 0 3 3)" stroke="#0078CE" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M21 8H3" stroke="#0078CE" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M15 8V21" stroke="#0078CE" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="pl-2">Drag/Drop Layout</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="postsbb-builder-layout<# if(!id){ #> d-none<# } #>">
                <hr/>
                <h3 class="font-weight-bold pt-3">SEO Content</h3>
                <div class="form-group">
                    <label for="">URL Slug</label>
                    <input class="form-control" name="post_slug" value="{{ post_slug }}">
                </div>
                <div class="form-group">
                    <label for="">Keywords</label>
                    <input class="form-control" name="post_seo_keywords" value="{{ post_seo_keywords }}">
                </div>
                <div class="form-group">
                    <label for="">Description</label>
                    <textarea class="form-control" name="post_seo_description">{{ post_seo_description }}</textarea>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div id="submitdiv" class="postbox">
                <div class="postbox-header">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo POSTS_BE_BUILDER_PUBLIC_URL ?>images/icons/icon-date.png" class="mr-2">
                        <span>{{ moment(updated).format('MMM Do YYYY') }}</span>
                    </div>
                </div>
                <div class="postbox-container px-2">
                    <div class="row">
                        <div class="col-md-6 py-2">
                            <div class="d-flex align-items-center postsbb-100">
                                <div class="form-group w-100 mb-0 form-group-postsbb-status">
                                    <select name="post_status" class="postsbb-status" data-selected-default="{{ post_status }}">
                                        <option value="draft">Draft</option>
                                        <option value="publish">Publish</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 py-2">
                            <div class="row">
                                <div class="col-md-8 p-0">
                                    <div class="postsbb-pro-action-save d-flex justify-content-end">
                                        <button class="btn btn-submit" id="postsbb-btn-pro-action-save"><img src="<?php echo POSTS_BE_BUILDER_PUBLIC_URL ?>images/icons/icon-update.png" width="15" class="mr-1"> Update</button>
                                    </div>
                                </div>
                                <# if(id){ #>
                                <div class="col-md-4 p-0">
                                    <div class="postsbb-pro-action-delete d-flex justify-content-end">
                                        <button class="btn btn-submit" id="postsbb-btn-pro-action-delete" data-id="{{id}}">Delete</button>
                                    </div>
                                </div>
                                <# } #>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="categorydiv" class="postbox">
                <div class="postbox-header">
                    <h5 class="handle"><?php _e('Categories'); ?></h5>
                    <div class="handle-actions">
                        <a href="javascript:;" class="handlediv" aria-expanded="true">
                            <svg class="icon-chevron-down" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 10L12 16L18 10" stroke="black" stroke-linecap="round"/>
                            </svg>
                            <svg class="icon-chevron-up" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 14L12 8L6 14" stroke="black" stroke-linecap="round"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="postbox-container">
                    <div class="inside">
						<div class="form-group w-100">
                            <select class="postsbb-select-pro-cates" id="post_category" name="post_category[]" multiple data-is-validation="true" required data-selected-default="<# if(post_category){ #>{{JSON.stringify(post_category)}}<# } #>"></select>
                        </div>
                    </div>
                </div>
            </div>
            <div id="productimagesdiv" class="postbox postbox-images">
                <div class="postbox-header">
                    <h5 class="handle"><?php _e('Featured image'); ?></h5>
                    <div class="handle-actions">
                        <a href="javascript:;" class="handlediv" aria-expanded="true">
                            <svg class="icon-chevron-down" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 10L12 16L18 10" stroke="black" stroke-linecap="round"/>
                            </svg>
                            <svg class="icon-chevron-up" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 14L12 8L6 14" stroke="black" stroke-linecap="round"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="postbox-container">
                    <div class="inside">
                        <div class="postsbb-pro-gallery mb-3 px-2">
                            <div class="row postsbb-pgi-content">
                                <#
                                let _tpl_gallery_item = _.template(document.getElementById('tpl-gallery-item').innerHTML);
                                let gli = '';
                                _.each(post_gallery, function(item, idx){
                                gli += _tpl_gallery_item({image_url: item, idx: idx, file: ''});
                                })
                                #>
                                {{{ gli }}}
                                <div class="col-md-6 px-1 postsbb-pgi postsbb-pgi-add">
                                    <label class="postsbb-pro-gallery-item postsbb-js-pro-gallery-add">
                                        <input type="file" multiple class="d-none" accept="image/*">
                                        <span class="px-2">
                                            + Add Photos
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="postbox">
                <div class="postbox-header">
                    <h5 class="handle"><?php _e('Excerpt'); ?></h5>
                    <div class="handle-actions">
                        <a href="javascript:;" class="handlediv" aria-expanded="true">
                            <svg class="icon-chevron-down" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 10L12 16L18 10" stroke="black" stroke-linecap="round"/>
                            </svg>
                            <svg class="icon-chevron-up" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 14L12 8L6 14" stroke="black" stroke-linecap="round"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="postbox-container">
                    <div class="inside">
                        <textarea name="post_excerpt" class="form-control">{{ post_excerpt }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>
<script id="tpl-gallery-item" type="text/html">
    <div class="col-md-6 px-1 postsbb-pgi mb-2" data-idx="{{ idx }}">
        <div class="postsbb-pro-gallery-item">
            <#
            let img_src = '';
            if( file ){
                img_src = URL.createObjectURL(file);
            }else{
                img_src = image_url;
            #>
            <input type="hidden" name="pro_gallery_img_url[]" value="{{ img_src }}">
            <# } #>
            <img src="{{ img_src }}" class="w-100">
            <a href="javascript:;" class="js-pro-gallery-item-remove">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.6074 4.96472L5.31911 15.6404H13.1479L13.8596 4.96472M4.6074 4.96472H2.82812M4.6074 4.96472H7.09839M13.8596 4.96472H15.6389M13.8596 4.96472H11.3687M7.09839 4.96472V2.82959H11.3687V4.96472M7.09839 4.96472H11.3687M7.8101 7.09986V13.5053M10.6569 7.09986V13.5053" stroke="#324552" stroke-width="1.42342" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>
    </div>
</script>
<script id="tpl-postsbb-item" type="text/html">
    <li class="postsbb-product-item" data-id="{{ id }}">
        <div class="d-flex">
            <div class="postsbb-pro-left mr-3">
                <div class="postsbb-pro-image">
                    <img src="{{ thumbnail }}">
                </div>
            </div>
            <div class="postsbb-pro-right">
                <h5 class="postsbb-pro-title">{{ post_title }}</h5>
                <div class="postsbb-pro-property">
                    <span class="postsbb-pro-status mr-3 postsbb-pro-status-{{ post_status }}">
                        <# if( post_status === 'publish' ){ #>
                        Public
                        <# }else{ #>
                        Draft
                        <# } #>
                    </span>
                    <span class="postsbb-pro-date">{{ livetimestamp }} | {{ updated }}</span>
                </div>
            </div>
        </div>
    </li>
</script>
<?php
wp_enqueue_style('posts-be-builder');
wp_enqueue_script('posts-be-builder');
wp_localize_script('posts-be-builder', 'POSTSBB_GLOBAL', [
    'post_categories' => $post_categories,
    'page' => $page,
    'limit_file' => Real_Estate_Products_Public::LIMIT_FILE,
]);
get_footer();