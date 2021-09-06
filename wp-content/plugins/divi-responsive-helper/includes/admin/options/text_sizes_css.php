<?php
add_action('wp_footer', function () {
    $type_attr = current_theme_supports('html5', 'style') ? '' : ' type="text/css"';
    // H1
    $h1_desktop = esc_html(et_get_option('pac_drh_h1_desktop'));
    $h1_tablet = esc_html(et_get_option('pac_drh_h1_tablet'));
    $h1_phone = esc_html(et_get_option('pac_drh_h1_phone'));
    // H2
    $h2_desktop = esc_html(et_get_option('pac_drh_h2_desktop'));
    $h2_tablet = esc_html(et_get_option('pac_drh_h2_tablet'));
    $h2_phone = esc_html(et_get_option('pac_drh_h2_phone'));
    // H3
    $h3_desktop = esc_html(et_get_option('pac_drh_h3_desktop'));
    $h3_tablet = esc_html(et_get_option('pac_drh_h3_tablet'));
    $h3_phone = esc_html(et_get_option('pac_drh_h3_phone'));
    // H4
    $h4_desktop = esc_html(et_get_option('pac_drh_h4_desktop'));
    $h4_tablet = esc_html(et_get_option('pac_drh_h4_tablet'));
    $h4_phone = esc_html(et_get_option('pac_drh_h4_phone'));
    // H5
    $h5_desktop = esc_html(et_get_option('pac_drh_h5_desktop'));
    $h5_tablet = esc_html(et_get_option('pac_drh_h5_tablet'));
    $h5_phone = esc_html(et_get_option('pac_drh_h5_phone'));
    // H6
    $h6_desktop = esc_html(et_get_option('pac_drh_h6_desktop'));
    $h6_tablet = esc_html(et_get_option('pac_drh_h6_tablet'));
    $h6_phone = esc_html(et_get_option('pac_drh_h6_phone'));
    // Paragraph
    $p_desktop = esc_html(et_get_option('pac_drh_p_desktop'));
    $p_tablet = esc_html(et_get_option('pac_drh_p_tablet'));
    $p_phone = esc_html(et_get_option('pac_drh_p_phone'));
    echo "
    <style>
    @media(min-width:981px){
        body, .et_pb_column .et_quote_content blockquote cite, .et_pb_column .et_link_content a.et_link_main_url, .et_pb_column .et_quote_content blockquote cite, 
        .et_pb_column .et_quote_content blockquote cite, .et_pb_column .et_quote_content blockquote cite, .et_pb_blog_grid .et_quote_content blockquote cite, 
        .et_pb_column .et_link_content a.et_link_main_url, .et_pb_column .et_link_content a.et_link_main_url, .et_pb_column .et_link_content a.et_link_main_url, 
        .et_pb_blog_grid .et_link_content a.et_link_main_url, .et_pb_post .post-content,.et_pb_counter_title,
        .et_pb_counter_amount,.et_pb_slide_content,.et-menu li,.et_pb_filterable_portfolio .et_pb_portfolio_filters li,.woocommerce ul.products li.product .price del span.amount,
        .woocommerce ul.products li.product .price ins span.amount, .et_pb_post .post-meta,.woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price,
        .woocommerce ul.products li.product .price del, .woocommerce-page ul.products li.product .price del,.nav-single a{font-size:".esc_attr($p_desktop)."}
        h1,.et_pb_pagebuilder_layout .page .post-content h1,.et_pb_title_container h1{font-size:".esc_attr($h1_desktop)."}
        h2,.et_pb_blog_grid h2,.et_pb_slide_description .et_pb_slide_title,.woocommerce ul.products li.product .woocommerce-loop-product__title,.et_pb_portfolio_grid .et_pb_portfolio_item h2,
        .et_pb_portfolio_grid .et_pb_portfolio_item h2, .et_pb_filterable_portfolio_grid .et_pb_portfolio_item h2{font-size:".esc_attr($h2_desktop)."}
        h3,.et_pb_circle_counter h3, .et_pb_number_counter h3{font-size:".esc_attr($h3_desktop)."}
        h4 {font-size:".esc_attr($h4_desktop)."}
        h5 {font-size:".esc_attr($h5_desktop)."}
        h6 {font-size:".esc_attr($h6_desktop)."}
    }
    @media(min-width:481px) and (max-width:980px){
        body, .et_pb_column .et_quote_content blockquote cite, .et_pb_column .et_link_content a.et_link_main_url, .et_pb_column .et_quote_content blockquote cite, 
        .et_pb_column .et_quote_content blockquote cite, .et_pb_column .et_quote_content blockquote cite, .et_pb_blog_grid .et_quote_content blockquote cite, 
        .et_pb_column .et_link_content a.et_link_main_url, .et_pb_column .et_link_content a.et_link_main_url, .et_pb_column .et_link_content a.et_link_main_url, 
        .et_pb_blog_grid .et_link_content a.et_link_main_url, .et_pb_post .post-content,.et_pb_counter_title,
        .et_pb_counter_amount,.et_pb_slide_content,.et-menu li,.et_pb_filterable_portfolio .et_pb_portfolio_filters li,.woocommerce ul.products li.product .price del span.amount,
        .woocommerce ul.products li.product .price ins span.amount,.et_pb_post .post-meta,.woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price,
        .woocommerce ul.products li.product .price del, .woocommerce-page ul.products li.product .price del,.nav-single a{font-size:".esc_attr($p_tablet)."}
        h1,.et_pb_pagebuilder_layout .page .post-content h1,.et_pb_title_container h1{font-size:".esc_attr($h1_tablet)."}
        h2,.et_pb_blog_grid h2,.et_pb_slide_description .et_pb_slide_title,.woocommerce ul.products li.product .woocommerce-loop-product__title, .et_pb_portfolio_grid .et_pb_portfolio_item h2,
        .et_pb_portfolio_grid .et_pb_portfolio_item h2, .et_pb_filterable_portfolio_grid .et_pb_portfolio_item h2{font-size:".esc_attr($h2_tablet)."}
        h3 {font-size:".esc_attr($h3_tablet)."}
        h4 {font-size:".esc_attr($h4_tablet)."}
        h5 {font-size:".esc_attr($h5_tablet)."}
        h6 {font-size:".esc_attr($h6_tablet)."}
    }
    @media(max-width:480px){
        body, .et_pb_column .et_quote_content blockquote cite, .et_pb_column .et_link_content a.et_link_main_url, .et_pb_column .et_quote_content blockquote cite, 
        .et_pb_column .et_quote_content blockquote cite, .et_pb_column .et_quote_content blockquote cite, .et_pb_blog_grid .et_quote_content blockquote cite, 
        .et_pb_column .et_link_content a.et_link_main_url, .et_pb_column .et_link_content a.et_link_main_url, .et_pb_column .et_link_content a.et_link_main_url, 
        .et_pb_blog_grid .et_link_content a.et_link_main_url, .et_pb_post .post-content,.et_pb_counter_title,
        .et_pb_counter_amount,.et_pb_slide_content,.et-menu li,.et_pb_filterable_portfolio .et_pb_portfolio_filters li,.woocommerce ul.products li.product .price del span.amount,
        .woocommerce ul.products li.product .price ins span.amount,.et_pb_post .post-meta,.woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price,
        .woocommerce ul.products li.product .price del, .woocommerce-page ul.products li.product .price del,.nav-single a{font-size:".esc_attr($p_phone)."}
        h1,.et_pb_pagebuilder_layout .page .post-content h1,.et_pb_title_container h1{font-size:".esc_attr($h1_phone)."}
        h2,.et_pb_blog_grid h2,.et_pb_slide_description .et_pb_slide_title,.woocommerce ul.products li.product .woocommerce-loop-product__title, .et_pb_portfolio_grid .et_pb_portfolio_item h2,
        .et_pb_portfolio_grid .et_pb_portfolio_item h2, .et_pb_filterable_portfolio_grid .et_pb_portfolio_item h2{font-size:".esc_attr($h2_phone)."}
        h3,.et_pb_circle_counter h3, .et_pb_number_counter h3{font-size:".esc_attr($h3_phone)."}
        h4 {font-size:".esc_attr($h4_phone)."}
        h5 {font-size:".esc_attr($h5_phone)."}
        h6 {font-size:".esc_attr($h6_phone)."}
    }
    </style>
    ";
});
