<?php
$heading      = get_sub_field( 'newsletter_heading' );
$button_text  = get_sub_field( 'newsletter_button_text' );
$placeholder  = get_sub_field( 'newsletter_placeholder' );
$form_action  = get_sub_field( 'newsletter_form_action' );
$form_mode    = strtolower( trim( (string) get_sub_field( 'newsletter_form_mode' ) ) ) ?: 'default';
$form_html    = get_sub_field( 'newsletter_form_html' );

$uid = function_exists( 'wp_unique_id' ) ? wp_unique_id( 'newsletter-' ) : uniqid( 'newsletter-', false );

$heading     = $heading ? $heading : 'Sign up for Updates & Newsletters.';
$button_text = $button_text ? $button_text : 'Subscribe Now';
$placeholder = $placeholder ? $placeholder : 'Your Email Address';

$form_id    = 'widget-subscribe-form-' . $uid;
$input_id   = 'widget-subscribe-form-email-' . $uid;
$input_name = 'widget-subscribe-form-email';
?>

<div class="section section-colored rounded px-4">
	<div class="row justify-content-center align-items-center">
		<div class="col-lg-5">
			<h3 class="mb-4 mb-lg-0"><?php echo esc_html( $heading ); ?></h3>
		</div>
		<div class="col-lg-6">
			<div class="widget subscribe-widget" data-loader="button">
				<div class="widget-subscribe-form-result"></div>

				<?php if ( 'html' === $form_mode && $form_html ) : ?>
					<?php echo wp_kses_post( $form_html ); ?>
				<?php else : ?>
					<form id="<?php echo esc_attr( $form_id ); ?>" action="<?php echo $form_action ? esc_url( $form_action ) : '#'; ?>" method="post" class="mb-0 d-flex">
						<input
							type="email"
							id="<?php echo esc_attr( $input_id ); ?>"
							name="<?php echo esc_attr( $input_name ); ?>"
							class="form-control form-control-lg not-dark required email"
							placeholder="<?php echo esc_attr( $placeholder ); ?>"
							autocomplete="email"
						>
						<button class="button button-large button-black button-dark fw-medium ls-0 button-rounded m-0 ms-3" type="submit">
							<?php echo esc_html( $button_text ); ?>
						</button>
					</form>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
