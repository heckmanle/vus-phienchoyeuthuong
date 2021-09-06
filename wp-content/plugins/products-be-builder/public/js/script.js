jQuery(function ($){
    _.templateSettings = {
        evaluate: /<#([\s\S]+?)#>/g,
        interpolate: /{{{([\s\S]+?)}}}/g,
        escape: /{{([^}]+?)}}(?!})/g
    };
    let view_mode = 1, pagination_run = false, products = REP_LIST.products.concat([]), page_number = 1;
    let tpl_item_1 = _.template($('script#tpl-pro-item-1').html());
    let tpl_item_0 = _.template($('script#tpl-pro-item-0').html());
    const calcHeightItem = async () => {
        let $thumbnail = $('.page-pro-list .pro-thumbnail a');
        if( $thumbnail.length ){
            $('.page-pro-list').block(REP_IMAGE_LOADING);
            for (let index = 0; index < $thumbnail.length; index++) {
                let $this = $($thumbnail[index]);
                let $parent = $this.closest('.pro-item');
                if( $parent.hasClass('pro-item-mode-1') ){
                    let width = $this.width();
                    let res = await $this.css('height', width);
                }else{
                    let h = $parent.height();
                    let res = await $this.css({
                        width: h,
                        height: h,
                    });
                }

            }
        }
        $('.page-pro-list').unblock(REP_IMAGE_LOADING);
    }
    //calcHeightItem();
    // $(window).resize(function (){
    //     calcHeightItem();
    // });
    const renderItem = async (data) => {
        let html = 0;
        if( view_mode === 1 ){
            for (let i = 0; i < data.length; i++){
                html += await tpl_item_1(data[i]);
            }
        }else{
            for (let i = 0; i < data.length; i++){
                html += await tpl_item_0(data[i]);
            }
        }
        return html;
    }
    $('.pro-search .view-mode').click(function (ev){
        ev.preventDefault();
        let $this = $(this), {mode} = $this.data();
        view_mode = mode;
        $this.addClass('active');
        $('.pro-search .view-mode').not($this).removeClass('active');
        $('.page-pro-list').pagination(page_number);
    });
    const repEscapeRegex = function( value ) {
        return value.replace( /[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&" );
    }
    const repFilter = function( array, term ) {
        let matcher = new RegExp( repEscapeRegex( term ), "i" );
        return $.grep( array, function( value ) {
            return matcher.test( value );
        } );
    }
    $('.pro-search-keyword').keypress(function (ev){
        if( ev.which === 13 )
            $('#pro-search-action').trigger('click');
    });
    $('#pro-search-action').click(function (ev){
        ev.preventDefault();
        let $address = $('.ress-address input.checkbox-status:checked'),
            $cates = $('.ress-cate input.checkbox-status:checked'),
            $text = $('.pro-search-keyword');
        let address = [], cates = [], text = repEscapeRegex($text.val().trim());
        $address.each(function(){
            address.push($(this).val());
        });
        $cates.each(function(){
            cates.push($(this).val());
        });
        products = REP_LIST.products.concat([]);
        products = products.filter(item => {
            let condition = true;
            if( text.length ) {
                let matcher = new RegExp(text, "i");
                condition = matcher.test(item.product_title);
            }
            if( cates.length ){
                let cate_filter = item.product_category.filter(it => {
                    return cates.includes(it.id);
                });
                condition = condition && cate_filter.length;
            }
            if( address.length ){
                condition = condition && address.includes(item.address);
            }
            return condition;
        });
        Pagination();
    });
    const Pagination = () => {
        $('.page-pro-list').pagination({
            dataSource: products,
            pageSize: REP_LIST.limit,
            autoHidePrevious: true,
            autoHideNext: true,
            showNext: false,
            showPrevious: false,
            callback: function(data, pagination) {
                if( pagination_run === true ){
                    let html = '';
                    if( view_mode === 1 ){
                        data.forEach(function (item){
                            html += tpl_item_1(item);
                        });
                    }else{
                        data.forEach(function (item){
                            html += tpl_item_0(item);
                        });
                    }
                    $('.page-pro-list-wrapper').html(html);

                }
                page_number = pagination.pageNumber;
                calcHeightItem();
                if( products.length <= REP_LIST.limit ){
                    $('.paginationjs').addClass('d-none');
                }else{
                    $('.paginationjs').removeClass('d-none');
                }
                pagination_run = true;
            }
        });
    }
    Pagination();
    if( REP_LIST.keyword != '' || REP_LIST.address != '' || REP_LIST.cate != '' ){
        $('#pro-search-action').trigger('click');
    }
});