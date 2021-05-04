<?php

/**
 * The One Page Checkout core.
 */
class One_Page_Checkout {
	/**
	 * The plugin version.
	 *
	 * @var string
	 */
	private $version = '0.0.1';

	/**
	 * Instaniate the One Page Checkout.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_head', array( $this, 'register_styles_and_scripts' ) );
		add_filter( 'template_include', array( $this, 'load_checkout_template' ) );

		add_action( 'wp_ajax_opc_get_cart_contents', array( $this, 'get_cart_contents' ) );
		add_action( 'wp_ajax_nopriv_opc_get_cart_contents', array( $this, 'get_cart_contents' ) );

		add_action( 'wp_ajax_opc_remove_item_from_cart', array( $this, 'remove_item_from_cart' ) );
		add_action( 'wp_ajax_nopriv_opc_remove_item_from_cart', array( $this, 'remove_item_from_cart' ) );

	}

	/**
	 * Determines if the current page is the page marked as 'Checkout' in the WooCommerce admin.
	 *
	 * @return bool
	 */
	public function is_on_checkout_page() {
		return wc_get_page_id( 'checkout' ) === get_the_ID();
	}

	/**
	 * Registers the One Page Checkout CSS and JS files.
	 *
	 * @return void
	 */
	public function register_styles_and_scripts() {
		if ( $this->is_on_checkout_page() ) {
			$styles = array(
				'one-page-checkout-base-styles' => plugins_url( '../dist/one-page-checkout.css', __FILE__ ),
			);

			$scripts = array(
				'one-page-checkout-base-component' => plugins_url( '../dist/one-page-checkout.js', __FILE__ ),
			);

			foreach ( $styles as $handle => $path ) {
				wp_enqueue_style( $handle, $path, array(), $this->version );
			}

			foreach ( $scripts as $handle => $path ) {
				wp_enqueue_script( $handle, $path, array(), $this->version, true );
			}
		}
	}

	/**
	 * Loads the checkout.php template.
	 *
	 * @param string $template The template file path.
	 * @return string The template file path.
	 */
	public function load_checkout_template( $template ) {
		if ( $this->is_on_checkout_page() ) {
			$template = dirname( __FILE__ ) . '/templates/checkout.php';
		}

		return $template;
	}

	public function verify_nonce() {
		if ( ! check_ajax_referer( 'opc-security-nonce', 'security', false ) ) {
			wp_send_json_error( 'Invalid security token sent.', 401 );
			wp_die();
		}
	}

	public function get_cart_contents() {
		$this->verify_nonce();

		$cart = WC()->cart;

		return wp_send_json(
			array(
				'total' => $cart->get_cart_contents_total(),
				'items' => array_map(
					function( $cart_item_key, $cart_item ) use ( $cart ) {
						$product = $cart_item['data'];

						return array(
							'cart_item_key'  => $cart_item_key,
							'title'          => $product->get_title(),
							'image_url'      => wp_get_attachment_image_src( $product->get_image_id(), 'thumbnail' )[0],
							'price'          => $product->get_price(),
							'quantity'       => $cart_item['quantity'],
						);
					},
					array_keys( $cart->get_cart() ),
					$cart->get_cart()
				),
			)
		);
	}

	public function remove_item_from_cart() {
		$this->verify_nonce();

		$cart_item_key = $_GET['cart_item_key'];

		WC()->cart->remove_cart_item( $cart_item_key );
	}
}
