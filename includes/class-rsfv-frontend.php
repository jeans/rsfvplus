<?php
/**
 * RSFV+ Frontend Class
 *
 * Handles frontend logic including playback controls, aspect ratio, lazy loading, play button, and asset enqueueing.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class RSFV_Frontend {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_footer', array( $this, 'inject_play_icon_svg' ) );
	}

	/**
	 * Enqueue frontend scripts and styles.
	 */
	public function enqueue_scripts() {
		// Enqueue extended JS for frontend video features.
		wp_enqueue_script(
			'rsfvplus-frontend',
			plugins_url( 'assets/js/rsfvplus-frontend.js', RSFVPLUS_PLUGIN_FILE ),
			array( 'jquery' ),
			RSFVPLUS_VERSION,
			true
		);

		// Enqueue frontend styles.
		wp_enqueue_style(
			'rsfvplus-frontend',
			plugins_url( 'assets/css/rsfvplus-frontend.css', RSFVPLUS_PLUGIN_FILE ),
			array(),
			RSFVPLUS_VERSION
		);
	}

	/**
	 * Inject the play icon SVG into the footer.
	 */
	public function inject_play_icon_svg() {
		?>
		<svg id="rsfvplus-play-icon" style="display:none;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
			<polygon points="6,4 20,12 6,20"/>
		</svg>
		<?php
	}

	/**
	 * Output frontend video wrapper with controls, aspect ratio, lazy loading, and play button.
	 *
	 * @param string $video_url The URL of the video file.
	 * @param array  $args      Additional arguments (e.g., aspect ratio, lazy, autoplay).
	 */
	public function render_video( $video_url, $args = array() ) {
		$defaults = array(
			'aspect_ratio' => '16:9',
			'lazy'         => true,
			'autoplay'     => false,
			'controls'     => true,
			'play_button'  => true,
		);
		$args = wp_parse_args( $args, $defaults );

		// Aspect ratio inline style.
		$aspect_ratio = explode( ':', $args['aspect_ratio'] );
		$padding_pct = ( (int) $aspect_ratio[1] / (int) $aspect_ratio[0] ) * 100;

		$classes = array( 'rsfvplus-video-wrapper' );
		if ( $args['lazy'] ) {
			$classes[] = 'rsfvplus-lazy';
		}
		if ( $args['play_button'] ) {
			$classes[] = 'rsfvplus-has-playbtn';
		}

		?>
		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" style="position:relative;padding-top:<?php echo esc_attr( $padding_pct ); ?>%;">
			<video
				<?php if ( $args['lazy'] ): ?>
					data-src="<?php echo esc_url( $video_url ); ?>"
					poster="<?php echo esc_url( $this->get_poster_url( $video_url ) ); ?>"
				<?php else: ?>
					src="<?php echo esc_url( $video_url ); ?>"
				<?php endif; ?>
				<?php if ( $args['autoplay'] ) echo 'autoplay '; ?>
				<?php if ( $args['controls'] ) echo 'controls '; ?>
				style="position:absolute;top:0;left:0;width:100%;height:100%;"
				class="rsfvplus-video"
			></video>
			<?php if ( $args['play_button'] ): ?>
				<button class="rsfvplus-play-btn" aria-label="Play">
					<svg class="rsfvplus-inline-icon"><use xlink:href="#rsfvplus-play-icon"></use></svg>
				</button>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Get poster URL for the video (can be extended to generate/choose a thumbnail).
	 *
	 * @param string $video_url
	 * @return string
	 */
	protected function get_poster_url( $video_url ) {
		// Placeholder: Return a default poster or derive from video URL.
		return plugins_url( 'assets/img/default-poster.jpg', RSFVPLUS_PLUGIN_FILE );
	}
}
