<?php
/**
 * Plugin Name:     Ys Simple Google Analytics
 * Plugin URI:      https://github.com/yosiakatsuki/ys-simple-google-analytics
 * Description:     This plugin enables Google Analytics for your WordPress site.
 * Author:          yosiakatsuki
 * Author URI:      https://yosiakatsuki.net/
 * Text Domain:     ys-simple-google-analytics
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Ys_Simple_Google_Analytics
 */

/*
	Copyright (c) 2016 Yoshiaki Ogata (https://yosiakatsuki.net/)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 *	Ys_Simple_Google_Analytics
 */
class Ys_Simple_Google_Analytics {

	/**
	 * __construct
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'initialize' ) );
	}

	/**
	 * initialize
	 */
	public function initialize() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'wp_head', array( $this, 'the_google_analytics_tag' ) );
	}

	/**
	 * GAタグを出力
	 */
	public function the_google_analytics_tag() {
		echo $this->get_the_google_analytics_tag();
	}

	/**
	 * headに出力するGAタグを取得
	 *
	 * @return string headタグに出力するGoogle Analyticsのコード
	 */
	public function get_the_google_analytics_tag() {

		$tracking_id = get_option( 'YSSGA_GA_Tracking_ID', '' );

		if ( '' == $tracking_id ) {
			return '';
		}

		$tracking_code = "ga('create', '{$tracking_id}', 'auto');" . PHP_EOL;
		$tracking_code .= "ga('send', 'pageview');";

		$tracking_code = apply_filters( 'YSSGA_GA_Tracking_Code', $tracking_code );

		$tag = <<<EOD
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
{$tracking_code}
</script>
EOD;
		return $tag;
	}

	/**
	 * 設定ページの追加
	 */
	public function admin_menu() {

		add_options_page(
			__( 'YS Simple Google Analytics', 'ys-simple-google-analytics' ),
			__( 'YS Simple Google Analytics', 'ys-simple-google-analytics' ),
			'manage_options',
			'ys-simple-google-analytics',
			array( $this, 'options_page' )
		);
	}

	/**
	 * 設定項目準備
	 */
	public function admin_init() {
		register_setting(
			'YSSGA_Settings',
			'YSSGA_GA_Tracking_ID',
			'esc_attr'
		);
	}

	/**
	 * 設定ページ
	 */
	public function options_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		?>

<div class="wrap">
<h2><?php esc_html_e( 'YS Sinmple Google Analytics', 'ys-simple-google-analytics' ); ?></h2>
	<form method="post" action="options.php">

	<?php
		settings_fields( 'YSSGA_Settings' );
		do_settings_sections( 'YSSGA_Settings' );

		$tracking_id = get_option( 'YSSGA_GA_Tracking_ID', '' );
	?>

	<h2><?php esc_html_e( 'YS Sinmple Google Analytics', 'ys-simple-google-analytics' ); ?></h2>
	<div class="inside">
		<p><?php esc_html_e( 'Enter your Google Analytics tracking code below.', 'ys-simple-google-analytics' ); ?></p>
		<table class="form-table">
			<tr valign="top">
				<th style="min-width:230px;">
					Google Analytics Tracking Code<br />
					(Universal Analytics)
				</th>
				<td scope="row">
					<input type="text" name="YSSGA_GA_Tracking_ID" id="YSSGA_GA_Tracking_ID" value="<?php echo esc_attr( $tracking_id ); ?>" placeholder="UA-00000000-0" />
				</td>
			</tr>
		</table>
	</div>
	<?php submit_button(); ?>
	</form>
</div><!-- /.warp -->
		<?php
	}

}
// end Ys_Simple_Google_Analytics

$yssga = new Ys_Simple_Google_Analytics;
