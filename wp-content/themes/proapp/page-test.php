<?php
/**
 * The template for displaying all pages
 */

get_header(); ?>
    <!-- Sidebar -->
<?php get_sidebar( 'left' ); ?>
    <!----------------- End of Sidebar -->

    <!-- Main Content -->
    <div class="app-main__outer">
        <div class="app-main__inner">


            <?php
            $categories=[
                ["cate_name"=> "NGUỒN","prod_list" => ["Nguồn 1","Nguồn 2","Nguồn 3","Nguồn 4","Nguồn 5"]],
                ["cate_name"=> "Ổ CẮM","prod_list" => ["Ổ cắm 1","Ổ cắm dài 30 ..","Ổ cắm 3","Ổ cắm 4","Ổ cắm 5", "Ổ cắm 6", "Ổ cắm 7", "Ổ cắm 8"]]

            ];
            ?>

            <table class="cate_pro_list bc-table">
                <thead>
                    <tr>
                        <th class='' >STT</th>
                        <th class='' >Vị trí</th>
                        <?php
                        // get categories list
                        $cntProds=0;
                        foreach ($categories as $cateitem) {
                            $cntProds=count($cateitem['prod_list']);
                            $widthCate=$cntProds * 30;
                            ?>
                            <th class='catehead'>
                                <table>
                                    <tr>
                                        <td width="<?php echo $widthCate."px";?>" class="cate_title" colspan="<?php echo $cntProds;?>"><?php echo $cateitem['cate_name'];?></td>
                                    </tr>
                                    <tr class="bc-browsers">
                                        <?php
                                        // get categories list
                                        if($cntProds > 0) {
                                            $text_height=0;
                                            foreach ($cateitem['prod_list'] as $proditem) {
                                                $text_height= strlen($proditem) * 5;
                                                ?>
                                                <th class="bc-browser-chrome">
                                                    <span class="bc-head-txt-label bc-head-icon-chrome"><?php echo $proditem;?></span>
                                                </th>
                                                <?php
                                            } // end for cate
                                        }
                                        ?>
                                    </tr>
                                </table>
                            </th>
                            <?php
                        }  // end for prod
                        ?>

                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td class='' >1</td>
                    <td class='' >A001-A005</td>
                    <?php
                    // get categories list
                    $cntProds=0;
                    foreach ($categories as $cateitem) {
                        $cntProds=count($cateitem['prod_list']);
                        $widthCate=$cntProds * 30;
                        ?>
                        <td class='catehead'>
                            <table>
                                <tr class="bc-browsers">
                                    <?php
                                    // get categories list
                                    if($cntProds > 0) {
                                        $text_height=0;
                                        foreach ($cateitem['prod_list'] as $proditem) {
                                            $text_height= strlen($proditem) * 5;
                                            ?>
                                            <td class="bc-browser-chrome">
                                                <span class="bc-head-txt-label bc-head-icon-chrome"><?php echo "3";?></span>
                                            </td>
                                            <?php
                                        } // end for cate
                                    }
                                    ?>
                                </tr>
                            </table>
                        </td>
                        <?php
                    }  // end for prod
                    ?>
                </tr>



                    <tr>
                        <td class='' >2</td>
                        <td class='' >A0011-A0015</td>
                        <?php
                        // get categories list
                        $cntProds=0;
                        foreach ($categories as $cateitem) {
                            $cntProds=count($cateitem['prod_list']);
                            $widthCate=$cntProds * 30;
                            ?>
                            <td class='catehead'>
                                <table>
                                    <tr class="bc-browsers">
                                        <?php
                                        // get categories list
                                        if($cntProds > 0) {
                                            $text_height=0;
                                            foreach ($cateitem['prod_list'] as $proditem) {
                                                $text_height= strlen($proditem) * 5;
                                                ?>
                                                <td class="bc-browser-chrome">
                                                    <span class="bc-head-txt-label bc-head-icon-chrome"><?php echo "3";?></span>
                                                </td>
                                                <?php
                                            } // end for cate
                                        }
                                        ?>
                                    </tr>
                                </table>
                            </td>
                            <?php
                        }  // end for prod
                        ?>
                    </tr>
                </tbody>
            </table>




        </div>
    </div>

<?php get_footer();
