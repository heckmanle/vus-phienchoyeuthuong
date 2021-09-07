<?php


namespace REP\Components\Widgets;

use REP\Includes\Product;

class ProductTitle extends \WP_Widget
{
	public function __construct()
	{
		$id_base = 'rep-product-title';
		$name = __('Product Title', REAL_ESTATE_PRODUCTS_LANG_DOMAIN);
		$widget_options = array();
		$control_options = array();
		parent::__construct($id_base, $name, $widget_options, $control_options);
	}

	public function widget( $args, $instance ){
		$html = '';
		if( is_admin() ){
			$current_page = $_POST['current_page'] ?? [];
			$post_type = $_POST['post_type'] ?? '';
			$id = $current_page['id'] ?? 0;
			if( $post_type == Product::PRODUCT_TYPE ){
				$pro_id = get_post_meta($id, Product::PRODUCT_META_SYNC, true);
			}
		}else{
			global $post;
			if( $post->post_type == Product::PRODUCT_TYPE ){
				$pro_id = get_post_meta($post->ID, Product::PRODUCT_META_SYNC, true);
			}
		}
		if( !empty($pro_id) ){
			$fields = [
				'id',
				'product_code',
				'product_title',
				'product_unit',
				'product_price',
				'product_category' => [
					'id',
					'cate_title'
				],
				'product_description',
				'address',
				'product_number',
				'product_pay',
				'product_status',
				'product_gallery',
				'updated',
				'created',
				'user_id' => [
					'id',
					'avatar',
					'name',
					'email',
					'phone',
					'registered',
				],
			];
			$response = \DIVI\Includes\Core\Product::get_by_id($pro_id, $fields);
			if( !is_wp_error($response) ){
				$product_title = $response['product_title'];
				$address = $response['address'];
				$product_price = $response['product_price'];
				if( $product_price ){
					$product_price = core_convert_number_to_format($product_price) . ' đ/tháng';
				}
				$html = '
				<div class="row">
					<div class="col-12">
						<div class="rep-breadcrumb">
							<nav aria-label="breadcrumb">
								<ul class="breadcrumb bg-transparent px-0">
									<li class="breadcrumb-item"><a href="#">Home</a></li>
									<li class="breadcrumb-item"><a href="#">Apartments</a></li>
									<li class="breadcrumb-item active breadcrumb-pro-title" aria-current="page">' . $product_title . '</li>
								</ul>
							</nav>
						</div>
					</div>
					<div class="col-md-7">
					   <h3 class="prod-title">' . $product_title . '</h3>
					   <div class="d-flex flex-wrap">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C8.13 2 5 5.13 5 9C5 14.25 12 22 12 22C12 22 19 14.25 19 9C19 5.13 15.87 2 12 2ZM12 11.5C10.62 11.5 9.5 10.38 9.5 9C9.5 7.62 10.62 6.5 12 6.5C13.38 6.5 14.5 7.62 14.5 9C14.5 10.38 13.38 11.5 12 11.5Z" fill="#828282"/>
	</svg>
							<div class="prod-address">
								<span>' . $address . '</span>
							</div>
					   </div>
					   <div class="d-md-flex align-items-center mt-3">
							<div class="prod-reviews mr-4">
								<div class="d-flex align-items-center">
									<svg width="30" height="29" viewBox="0 0 40 39" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path fill-rule="evenodd" clip-rule="evenodd" d="M19.6721 27.9619L29.8035 34.0012L27.1149 22.6189L36.0659 14.9606L24.2788 13.9729L19.6721 3.23828L15.0655 13.9729L3.27832 14.9606L12.2293 22.6189L9.54075 34.0012L19.6721 27.9619Z" fill="#F2C94C"/>
				</svg>
									<span class="pro-reviews-value">4.6 (23 reviews)</span>         	
								</div>		
							</div>
							<div class="prod-save">
								<div class="d-flex align-items-center">
								<svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M7 4.86475C6.73478 4.86475 6.48043 4.9701 6.29289 5.15764C6.10536 5.34518 6 5.59953 6 5.86475V19.9216L11.4188 16.051C11.7665 15.8027 12.2335 15.8027 12.5812 16.051L18 19.9216V5.86475C18 5.59953 17.8946 5.34518 17.7071 5.15764C17.5196 4.9701 17.2652 4.86475 17 4.86475H7ZM4.87868 3.74343C5.44129 3.18082 6.20435 2.86475 7 2.86475H17C17.7956 2.86475 18.5587 3.18082 19.1213 3.74343C19.6839 4.30603 20 5.0691 20 5.86475V21.8647C20 22.2393 19.7907 22.5825 19.4576 22.7539C19.1245 22.9253 18.7236 22.8962 18.4188 22.6785L12 18.0936L5.58124 22.6785C5.27642 22.8962 4.87549 22.9253 4.54242 22.7539C4.20935 22.5825 4 22.2393 4 21.8647V5.86475C4 5.0691 4.31607 4.30603 4.87868 3.74343Z" fill="#FFBB16"/>
</svg>
								<span class="prod-save-title">' . __('Save', REAL_ESTATE_PRODUCTS_LANG_DOMAIN) . '</span>
								</div>
							</div>
					   </div>
					</div>
					<div class="col-md-5">
						<div class="d-flex justify-content-end">
							<div class="">
								<div class="d-md-flex align-items-center">
									<div class="wdt-pro-industrial-park d-flex align-items-center">
										<svg width="21" height="19" viewBox="0 0 21 19" fill="none" xmlns="http://www.w3.org/2000/svg">
<mask id="path-1-inside-1" fill="white">
<path d="M19.25 18.3667V16.727C19.9221 16.4705 20.3428 15.8588 20.3 15.2C20.3 14.327 19.6721 13.6167 18.9 13.6167C18.1279 13.6167 17.5 14.327 17.5 15.2C17.4572 15.8588 17.8779 16.4705 18.55 16.727V18.3667H16.8V8.55C16.8 8.42998 16.7251 8.32022 16.6064 8.26658L14 7.08763V5.38333C14.0001 5.2419 13.8965 5.11757 13.7463 5.0787L9.1 3.87758V0.316667C9.1 0.141787 8.94329 0 8.75 0H1.75C1.55671 0 1.4 0.141787 1.4 0.316667V18.3667H0V19H21V18.3667H19.25ZM8.4 18.3667H2.1V17.7333H5.25V17.1H2.1V15.8333H5.25V15.2H2.1V13.9333H5.25V13.3H2.1V12.0333H5.25V11.4H2.1V10.1333H5.25V9.5H2.1V8.23333H5.25V7.6H2.1V6.33333H5.25V5.7H2.1V4.43333H5.25V3.8H2.1V2.53333H5.25V1.9H2.1V0.633333H8.4V18.3667ZM13.3 18.3667H9.1V17.7333H11.55V17.1H9.1V15.8333H11.55V15.2H9.1V13.9333H11.55V13.3H9.1V12.0333H11.55V11.4H9.1V10.1333H11.55V9.5H9.1V8.23333H11.55V7.6H9.1V6.33333H11.55V5.7H9.1V4.53657L13.3 5.6221V18.3667ZM16.1 18.3667H14V17.7333H15.05V17.1H14V15.8333H15.05V15.2H14V13.9333H15.05V13.3H14V12.0333H15.05V11.4H14V10.1333H15.05V9.5H14V7.7957L16.1 8.7457V18.3667ZM18.55 16.0138C18.3149 15.7974 18.1884 15.5032 18.2 15.2C18.2 14.6851 18.5206 14.25 18.9 14.25C19.2794 14.25 19.6 14.6851 19.6 15.2C19.6113 15.5032 19.4848 15.7973 19.25 16.0138V15.5167H18.55V16.0138Z"/>
</mask>
<path d="M19.25 18.3667V16.727C19.9221 16.4705 20.3428 15.8588 20.3 15.2C20.3 14.327 19.6721 13.6167 18.9 13.6167C18.1279 13.6167 17.5 14.327 17.5 15.2C17.4572 15.8588 17.8779 16.4705 18.55 16.727V18.3667H16.8V8.55C16.8 8.42998 16.7251 8.32022 16.6064 8.26658L14 7.08763V5.38333C14.0001 5.2419 13.8965 5.11757 13.7463 5.0787L9.1 3.87758V0.316667C9.1 0.141787 8.94329 0 8.75 0H1.75C1.55671 0 1.4 0.141787 1.4 0.316667V18.3667H0V19H21V18.3667H19.25ZM8.4 18.3667H2.1V17.7333H5.25V17.1H2.1V15.8333H5.25V15.2H2.1V13.9333H5.25V13.3H2.1V12.0333H5.25V11.4H2.1V10.1333H5.25V9.5H2.1V8.23333H5.25V7.6H2.1V6.33333H5.25V5.7H2.1V4.43333H5.25V3.8H2.1V2.53333H5.25V1.9H2.1V0.633333H8.4V18.3667ZM13.3 18.3667H9.1V17.7333H11.55V17.1H9.1V15.8333H11.55V15.2H9.1V13.9333H11.55V13.3H9.1V12.0333H11.55V11.4H9.1V10.1333H11.55V9.5H9.1V8.23333H11.55V7.6H9.1V6.33333H11.55V5.7H9.1V4.53657L13.3 5.6221V18.3667ZM16.1 18.3667H14V17.7333H15.05V17.1H14V15.8333H15.05V15.2H14V13.9333H15.05V13.3H14V12.0333H15.05V11.4H14V10.1333H15.05V9.5H14V7.7957L16.1 8.7457V18.3667ZM18.55 16.0138C18.3149 15.7974 18.1884 15.5032 18.2 15.2C18.2 14.6851 18.5206 14.25 18.9 14.25C19.2794 14.25 19.6 14.6851 19.6 15.2C19.6113 15.5032 19.4848 15.7973 19.25 16.0138V15.5167H18.55V16.0138Z" fill="black" stroke="black" stroke-width="4" mask="url(#path-1-inside-1)"/>
</svg>
										<span class="pl-2">Industrial park</span>
									</div>
									<a href="javascript:;" class="ml-3 d-flex" >
										<svg width="17" height="18" viewBox="0 0 17 18" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M12.2499 4.8223L4.75 8.14355" stroke="black" stroke-width="2" stroke-linecap="round"/>
<path d="M12.2499 13.6792L4.75 10.3579" stroke="black" stroke-width="2" stroke-linecap="round"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M2.87498 11.4647C3.91051 11.4647 4.74997 10.4734 4.74997 9.25055C4.74997 8.0277 3.91051 7.03638 2.87498 7.03638C1.83946 7.03638 1 8.0277 1 9.25055C1 10.4734 1.83946 11.4647 2.87498 11.4647Z" stroke="black" stroke-width="2"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M14.125 5.92932C15.1605 5.92932 16 4.938 16 3.71515C16 2.4923 15.1605 1.50098 14.125 1.50098C13.0895 1.50098 12.25 2.4923 12.25 3.71515C12.25 4.938 13.0895 5.92932 14.125 5.92932Z" stroke="black" stroke-width="2"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M14.125 16.9999C15.1605 16.9999 16 16.0086 16 14.7857C16 13.5629 15.1605 12.5715 14.125 12.5715C13.0895 12.5715 12.25 13.5629 12.25 14.7857C12.25 16.0086 13.0895 16.9999 14.125 16.9999Z" stroke="black" stroke-width="2"/>
</svg>
									</a>
									<a href="javascript:;" class="ml-3 d-flex">
										<svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M12.0547 1.386C12.6641 1.13117 13.3173 1 13.9769 1C14.6366 1 15.2897 1.13117 15.8991 1.386C16.5086 1.64084 17.0622 2.01436 17.5286 2.48521C17.995 2.9558 18.3651 3.51452 18.6175 4.12946C18.87 4.74449 19 5.4037 19 6.06944C19 6.73517 18.87 7.39438 18.6175 8.00941C18.365 8.62439 17.995 9.18315 17.5285 9.65376C17.5284 9.65379 17.5285 9.65373 17.5285 9.65376L10.4259 16.8219C10.1906 17.0594 9.80901 17.0594 9.57369 16.8219L2.4711 9.65376C1.52917 8.70314 1 7.41382 1 6.06944C1 4.72505 1.52917 3.43573 2.4711 2.48511C3.41303 1.53449 4.69057 1.00043 6.02266 1.00043C7.35475 1.00043 8.63229 1.53449 9.57422 2.48511L9.99979 2.91461L10.4253 2.48521C10.8916 2.01436 11.4453 1.64084 12.0547 1.386ZM16.6762 3.34507C16.3218 2.98722 15.901 2.70335 15.4378 2.50967C14.9747 2.31599 14.4782 2.21631 13.9769 2.21631C13.4756 2.21631 12.9792 2.31599 12.516 2.50967C12.0529 2.70335 11.6321 2.98722 11.2777 3.34507L10.4259 4.2047C10.1906 4.44219 9.80901 4.44219 9.57369 4.2047L8.72202 3.34517C8.0061 2.62265 7.03512 2.21674 6.02266 2.21674C5.0102 2.21674 4.03922 2.62265 3.3233 3.34517C2.60739 4.06769 2.20519 5.04764 2.20519 6.06944C2.20519 7.09123 2.60739 8.07118 3.3233 8.7937L9.99979 15.5318L16.6763 8.7937C17.0308 8.43602 17.3122 8.01124 17.5041 7.54381C17.696 7.07639 17.7948 6.57539 17.7948 6.06944C17.7948 5.56348 17.696 5.06248 17.5041 4.59506C17.3122 4.12764 17.0307 3.70275 16.6762 3.34507Z" fill="#535250"/>
<path d="M17.5285 9.65376C17.995 9.18315 18.365 8.62439 18.6175 8.00941C18.87 7.39438 19 6.73517 19 6.06944C19 5.4037 18.87 4.74449 18.6175 4.12946C18.3651 3.51452 17.995 2.9558 17.5286 2.48521C17.0622 2.01436 16.5086 1.64084 15.8991 1.386C15.2897 1.13117 14.6366 1 13.9769 1C13.3173 1 12.6641 1.13117 12.0547 1.386C11.4453 1.64084 10.8916 2.01436 10.4253 2.48521L9.99979 2.91461L9.57422 2.48511C8.63229 1.53449 7.35475 1.00043 6.02266 1.00043C4.69057 1.00043 3.41303 1.53449 2.4711 2.48511C1.52917 3.43573 1 4.72505 1 6.06944C1 7.41382 1.52917 8.70314 2.4711 9.65376L9.57369 16.8219C9.80901 17.0594 10.1906 17.0594 10.4259 16.8219L17.5285 9.65376ZM17.5285 9.65376C17.5285 9.65373 17.5284 9.65379 17.5285 9.65376ZM16.6762 3.34507L17.1025 2.91504M16.6762 3.34507C16.3218 2.98722 15.901 2.70335 15.4378 2.50967C14.9747 2.31599 14.4782 2.21631 13.9769 2.21631C13.4756 2.21631 12.9792 2.31599 12.516 2.50967C12.0529 2.70335 11.6321 2.98722 11.2777 3.34507L10.4259 4.2047C10.1906 4.44219 9.80901 4.44219 9.57369 4.2047L8.72202 3.34517C8.0061 2.62265 7.03512 2.21674 6.02266 2.21674C5.0102 2.21674 4.03922 2.62265 3.3233 3.34517C2.60739 4.06769 2.20519 5.04764 2.20519 6.06944C2.20519 7.09123 2.60739 8.07118 3.3233 8.7937L9.99979 15.5318L16.6763 8.7937C17.0308 8.43602 17.3122 8.01124 17.5041 7.54381C17.696 7.07639 17.7948 6.57539 17.7948 6.06944C17.7948 5.56348 17.696 5.06248 17.5041 4.59506C17.3122 4.12764 17.0307 3.70275 16.6762 3.34507Z" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
									</a>
								</div>
								<div class="wdt-product-price text-right mt-3">
									' . $product_price . '
								</div>
							</div>
						</div>
					</div>
				</div>
				';
			}
		}

		echo $html;
	}

	public function form($instance)
	{

	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
}