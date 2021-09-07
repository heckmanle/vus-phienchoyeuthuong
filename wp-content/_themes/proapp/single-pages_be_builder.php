<?php
get_header('guest');
global $wp, $apiPageID, $uEdit;
$uEdit = "";
$response="";
if( isset($_GET['et_fb']) ) {
    $uEdit = $_GET['et_fb'];
}
?>
    <div id="main-content">
        <div class="entry-content">
            <?php while ( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <?php
                        if($uEdit != "") {
                            the_content();
                        } else {
                            $fields = [
                                'id',
                                'product_code',
                                'product_title',
                                'product_description',
                                'address',
                                'product_number',
                                'product_pay',
                                'product_status',
                                'product_gallery',
                                'product_slug',
                                'product_seo_keywords',
                                'product_seo_description',
                                'updated',
                                'created',
                            ];
                            if($apiPageID != "") {
                                $response = \DIVI\Includes\Core\Pages::get_by_id($apiPageID, $fields);
                            }

                            if(isset($response['product_description']) && $response['product_description'] != "") {
                                echo $response['product_description'];
                            }
                        }
                        ?>

                </article> <!-- .et_pb_post -->
            <?php endwhile; ?>
        </div>
    </div> <!-- #main-content -->
<?php
get_footer();
