<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * WC_Appointments_Admin_Customer_Meta_Box class.
 */
class WC_Appointments_Admin_Customer_Meta_Box {

	/**
	 * Meta box ID.
	 *
	 * @var string
	 */
	public $id;

	/**
	 * Meta box title.
	 *
	 * @var string
	 */
	public $title;

	/**
	 * Meta box context.
	 *
	 * @var string
	 */
	public $context;

	/**
	 * Meta box priority.
	 *
	 * @var string
	 */
	public $priority;

	/**
	 * Meta box post types.
	 * @var array
	 */
	public $post_types;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id         = 'woocommerce-customer-data';
		$this->title      = __( 'Customer details', 'woocommerce-appointments' );
		$this->context    = 'side';
		$this->priority   = 'default';
		$this->post_types = array( 'wc_appointment' );
	}

	/**
	 * Meta box content.
	 */
	public function meta_box_inner( $post ) {
 		global $appointment;

 		if ( ! is_a( $appointment, 'WC_Appointment' ) || $appointment->get_id() !== $post->ID ) {
 			$appointment = get_wc_appointment( $post->ID );
 		}
 		?>
 		<table class="appointment-customer-details">
 			<?php
			$appointment_customer = $appointment->get_customer();
			#print '<pre>'; print_r( $appointment_customer ); print '</pre>';
			?>
			<tr>
				<th><?php esc_html_e( 'Name:', 'woocommerce-appointments' ); ?></th>
				<td><?php echo esc_html( $appointment_customer->full_name ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Email:', 'woocommerce-appointments' ); ?></th>
				<td><?php echo make_clickable( sanitize_email( $appointment_customer->email ) ); // WPCS: XSS ok. ?></td>
			</tr>
			<?php if ( $appointment_customer->address ) { ?>
				<tr>
					<th><?php esc_html_e( 'Address:', 'woocommerce-appointments' ); ?></th>
					<td><?php echo wp_kses( $appointment_customer->address, array( 'br' => array() ) ); ?></td>
				</tr>
			<?php } ?>
			<?php if ( $appointment_customer->phone ) { ?>
				<tr>
					<th><?php esc_html_e( 'Phone:', 'woocommerce-appointments' ); ?></th>
					<td><?php echo esc_html( $appointment_customer->phone ); ?></td>
				</tr>
			<?php } ?>
			<?php if ( $appointment_customer->user_id ) { ?>
				<tr class="view">
					<th>&nbsp;</th>
					<td><a class="button button-small" target="_blank" href="<?php echo esc_url( admin_url( 'user-edit.php?user_id=' . absint( $appointment_customer->user_id ) ) ); ?>"><?php echo esc_html( 'View User', 'woocommerce-appointments' ); ?></a></td>
				</tr>
			<?php } ?>
			<?php do_action( 'woocommerce_admin_appointment_data_after_customer_details', $post->ID ); ?>
 		</table>
 		<?php
 	}
}

return new WC_Appointments_Admin_Customer_Meta_Box();
