<?php wp_head(); ?>

<script>
	var OnePageCheckout = {
		nonce: "<?php print wp_create_nonce( 'opc-security-nonce' ); ?>",
		ajax_url: "<?php print admin_url( 'admin-ajax.php' ); ?>"
	};
</script>

<div id="one-page-checkout">
	<one-page-checkout />
</div>

<?php wp_footer(); ?>
