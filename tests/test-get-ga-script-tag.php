<?php
/**
 * Create GA Tag
 *
 * @package Ys_Simple_Google_Analytics
 */

/**
 * Sample test case.
 */
class Get_GA_Script_Tag_Test extends WP_UnitTestCase {

	/**
	 * GAタグ生成のテスト
	 */
	function test_get_the_google_analytics_tag() {
		// 設定追加
		update_option( 'YSSGA_GA_Tracking_ID', 'UA-12345678-0' );

		$html = yssga_get_the_google_analytics_tag();
		// 出力されるはずのscriptタグ
		$result = <<<EOD
<script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');ga('create', 'UA-12345678-0', 'auto');ga('send', 'pageview');</script>
EOD;
		$result .=  PHP_EOL;
		$this->assertEquals( $result , $html );
	}

	/**
	 * 設定が空の場合のテスト
	 */
	function test_get_the_google_analytics_tag_設定が空の場合() {

		// 設定追加
		update_option( 'YSSGA_GA_Tracking_ID', '' );

		$html = yssga_get_the_google_analytics_tag();

		$this->assertEquals( '', $html );
	}
}
