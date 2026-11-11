<?php
/**
 * Editable Elementor version of the theme's demo layouts.
 *
 * The demo sections are PHP templates, which Elementor cannot edit. When a page
 * that uses one of the designed templates is opened in the Elementor editor for
 * the first time (and has no saved Elementor content yet), the same layout is
 * offered to the editor as real Elementor containers and widgets. Headings, text,
 * buttons, icons and images can then be edited directly in Elementor.
 *
 * Nothing is written to the database until the visitor clicks Publish/Update in
 * Elementor, so opening the editor and leaving never changes the live page.
 *
 * Dynamic sections (services, pricing, team, testimonials, FAQs, forms, logos, map)
 * are added as Shortcode widgets because their content lives in the WordPress
 * admin (Services, Team, Testimonials, ...), not in the page.
 *
 * @package Blush_Salon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ==========================================================================
   Small builders for Elementor's JSON structure
   ========================================================================== */

/**
 * Unique 7 character element id.
 *
 * @return string
 */
function blush_el_id() {
	static $n = 0;
	++$n;
	return substr( md5( 'blush-demo-' . $n ), 0, 7 );
}

/**
 * Slider/size control value.
 *
 * @param float|int $size Size.
 * @param string    $unit Unit.
 * @return array
 */
function blush_el_size( $size, $unit = 'px' ) {
	return array(
		'unit'  => $unit,
		'size'  => $size,
		'sizes' => array(),
	);
}

/**
 * Dimensions control value (padding, radius).
 *
 * @param int    $top    Top.
 * @param int    $right  Right.
 * @param int    $bottom Bottom.
 * @param int    $left   Left.
 * @param string $unit   Unit.
 * @return array
 */
function blush_el_box( $top, $right = null, $bottom = null, $left = null, $unit = 'px' ) {
	$right  = null === $right ? $top : $right;
	$bottom = null === $bottom ? $top : $bottom;
	$left   = null === $left ? $right : $left;
	return array(
		'unit'     => $unit,
		'top'      => (string) $top,
		'right'    => (string) $right,
		'bottom'   => (string) $bottom,
		'left'     => (string) $left,
		'isLinked' => $top === $right && $right === $bottom && $bottom === $left,
	);
}

/**
 * Container element.
 *
 * @param array $settings Settings.
 * @param array $elements Child elements.
 * @return array
 */
function blush_el_container( $settings, $elements = array() ) {
	return array(
		'id'       => blush_el_id(),
		'elType'   => 'container',
		'isInner'  => false,
		'settings' => $settings,
		'elements' => $elements,
	);
}

/**
 * Widget element.
 *
 * @param string $type     Widget type.
 * @param array  $settings Settings.
 * @return array
 */
function blush_el_widget( $type, $settings ) {
	return array(
		'id'         => blush_el_id(),
		'elType'     => 'widget',
		'widgetType' => $type,
		'settings'   => $settings,
		'elements'   => array(),
	);
}

/**
 * Link control value.
 *
 * @param string $url URL.
 * @return array
 */
function blush_el_link( $url ) {
	return array(
		'url'               => (string) $url,
		'is_external'       => '',
		'nofollow'          => '',
		'custom_attributes' => '',
	);
}

/**
 * Heading widget.
 *
 * @param string $text   Text.
 * @param string $tag    h1-h6 or p.
 * @param string $color  Hex color.
 * @param int    $size   Desktop font size in px.
 * @param int    $mobile Mobile font size in px.
 * @param array  $extra  Extra settings.
 * @return array
 */
function blush_el_heading( $text, $tag, $color, $size, $mobile, $extra = array() ) {
	return blush_el_widget(
		'heading',
		array_merge(
			array(
				'title'                        => $text,
				'header_size'                  => $tag,
				'title_color'                  => $color,
				'typography_typography'        => 'custom',
				'typography_font_size'         => blush_el_size( $size ),
				'typography_font_size_mobile'  => blush_el_size( $mobile ),
				'typography_line_height'       => blush_el_size( 1.2, 'em' ),
				'_css_classes'                 => 'blush-el-display',
				'_element_width'               => 'initial',
				'_element_custom_width'        => blush_el_size( 760 ),
				'_element_custom_width_mobile' => blush_el_size( 100, '%' ),
			),
			$extra
		)
	);
}

/**
 * Small label above a heading.
 *
 * @param string $text  Text.
 * @param string $color Hex color.
 * @return array
 */
function blush_el_eyebrow( $text, $color ) {
	return blush_el_widget(
		'heading',
		array(
			'title'                       => $text,
			'header_size'                 => 'p',
			'title_color'                 => $color,
			'typography_typography'       => 'custom',
			'typography_font_size'        => blush_el_size( 18 ),
			'typography_font_style'       => 'italic',
			'_css_classes'                => 'blush-el-display',
		)
	);
}

/**
 * Text editor widget.
 *
 * @param string $text  Plain text.
 * @param string $color Hex color.
 * @param int    $size  Font size in px.
 * @param array  $extra Extra settings.
 * @return array
 */
function blush_el_text( $text, $color, $size = 17, $extra = array() ) {
	return blush_el_widget(
		'text-editor',
		array_merge(
			array(
				'editor'                      => '<p>' . esc_html( $text ) . '</p>',
				'text_color'                  => $color,
				'typography_typography'       => 'custom',
				'typography_font_size'        => blush_el_size( $size ),
				'typography_line_height'      => blush_el_size( 1.6, 'em' ),
				'_element_width'              => 'initial',
				'_element_custom_width'       => blush_el_size( 700 ),
				'_element_custom_width_mobile' => blush_el_size( 100, '%' ),
			),
			$extra
		)
	);
}

/**
 * Button widget.
 *
 * @param string $text  Label.
 * @param string $url   Link.
 * @param string $bg    Background color.
 * @param string $color Text color.
 * @return array
 */
function blush_el_button( $text, $url, $bg, $color ) {
	return blush_el_widget(
		'button',
		array(
			'text'                  => $text,
			'link'                  => blush_el_link( $url ),
			'align'                 => 'left',
			'size'                  => 'lg',
			'background_color'      => $bg,
			'button_text_color'     => $color,
			'border_radius'         => blush_el_box( 10 ),
			'typography_typography' => 'custom',
			'typography_font_weight' => '600',
		)
	);
}

/**
 * Image widget.
 *
 * @param string $url   Image URL.
 * @param array  $extra Extra settings.
 * @return array
 */
function blush_el_image( $url, $extra = array() ) {
	return blush_el_widget(
		'image',
		array_merge(
			array(
				'image'                => array(
					'url' => $url,
					'id'  => '',
				),
				'image_size'           => 'full',
				'image_border_radius'  => blush_el_box( 16 ),
			),
			$extra
		)
	);
}

/**
 * Icon box widget.
 *
 * @param string $icon     Font Awesome class, e.g. "fas fa-star".
 * @param string $title    Title.
 * @param string $text     Description.
 * @param string $position left or top.
 * @param array  $c        Colors: icon_bg, icon, title, text.
 * @return array
 */
function blush_el_icon_box( $icon, $title, $text, $position, $c ) {
	return blush_el_widget(
		'icon-box',
		array(
			'selected_icon'               => array(
				'value'   => $icon,
				'library' => 'fa-solid',
			),
			'view'                        => 'stacked',
			'shape'                       => 'circle',
			'title_text'                  => $title,
			'description_text'            => $text,
			'position'                    => $position,
			'position_mobile'             => $position,
			'title_size'                  => 'h3',
			'primary_color'               => $c['icon_bg'],
			'secondary_color'             => $c['icon'],
			'icon_size'                   => blush_el_size( 22 ),
			'icon_space'                  => blush_el_size( 18 ),
			'title_color'                 => $c['title'],
			'description_color'           => $c['text'],
			'title_typography_typography' => 'custom',
			'title_typography_font_size'  => blush_el_size( 22 ),
			'_css_classes'                => 'blush-el-display',
		)
	);
}

/**
 * Shortcode widget for a dynamic theme section.
 *
 * @param string $shortcode Shortcode.
 * @return array
 */
function blush_el_shortcode( $shortcode ) {
	return blush_el_widget( 'shortcode', array( 'shortcode' => $shortcode ) );
}

/**
 * Theme colors, taken from the Customizer.
 *
 * @return array
 */
function blush_el_colors() {
	$primary = blush_mod( 'color_primary' );
	$accent  = blush_mod( 'color_accent' );
	return array(
		'primary' => $primary ? $primary : '#EA5084',
		'ink'     => '#A8365D',
		'accent'  => $accent ? $accent : '#FDADA8',
		'blush'   => '#FEDBD9',
		'heading' => '#2B2826',
		'text'    => '#4B4845',
		'white'   => '#FFFFFF',
		'dark'    => '#4A1930',
	);
}

/**
 * Full width, boxed content section container.
 *
 * @param array $settings Extra container settings.
 * @param array $elements Children.
 * @return array
 */
function blush_el_section( $settings, $elements ) {
	return blush_el_container(
		array_merge(
			array(
				'content_width'          => 'boxed',
				'boxed_width'            => blush_el_size( 1280 ),
				'flex_direction'         => 'column',
				'flex_justify_content'   => 'center',
				'flex_align_items'       => 'flex-start',
				'flex_gap'               => array(
					'column'   => '18',
					'row'      => '18',
					'isLinked' => true,
					'unit'     => 'px',
				),
				'padding'                => blush_el_box( 80, 24, 80, 24 ),
				'padding_mobile'         => blush_el_box( 56, 20, 56, 20 ),
				'html_tag'               => 'section',
			),
			$settings
		),
		$elements
	);
}

/**
 * Background image + color overlay settings.
 *
 * @param string $url     Image URL.
 * @param string $overlay Overlay color.
 * @param string $position Background position.
 * @return array
 */
function blush_el_bg( $url, $overlay, $position = 'center right' ) {
	return array(
		'background_background'          => 'classic',
		'background_image'               => array(
			'url' => $url,
			'id'  => '',
		),
		'background_position'            => $position,
		'background_repeat'              => 'no-repeat',
		'background_size'                => 'cover',
		'background_overlay_background'  => 'classic',
		'background_overlay_color'       => $overlay,
		'background_overlay_opacity'     => blush_el_size( 0.88, 'px' ),
	);
}

/* ==========================================================================
   Sections
   ========================================================================== */

/**
 * Home hero.
 *
 * @return array
 */
function blush_el_sec_hero() {
	$c   = blush_el_colors();
	$els = array( blush_el_heading( blush_mod( 'hero_title' ), 'h1', $c['white'], 64, 36 ) );
	if ( '' !== blush_mod( 'hero_text' ) ) {
		$els[] = blush_el_text( blush_mod( 'hero_text' ), $c['white'], 24, array( '_css_classes' => 'blush-el-display' ) );
	}
	if ( '' !== blush_mod( 'hero_btn_text' ) ) {
		$els[] = blush_el_button( blush_mod( 'hero_btn_text' ), blush_link( 'hero_btn_url', 'services', '#services' ), $c['white'], $c['primary'] );
	}
	return blush_el_section(
		array_merge( array( 'min_height' => blush_el_size( 640 ) ), blush_el_bg( blush_img( 'hero_image', 'hero.svg' ), $c['ink'], 'center right' ) ),
		$els
	);
}

/**
 * Inner page hero (title + subtitle).
 *
 * @param string $fallback Fallback image file.
 * @return array
 */
function blush_el_sec_page_hero( $fallback ) {
	$c        = blush_el_colors();
	$post_id  = get_the_ID();
	$subtitle = get_post_meta( $post_id, '_blush_hero_subtitle', true );
	if ( '' === (string) $subtitle ) {
		$subtitle = blush_mod( 'page_hero_subtitle' );
	}
	$image = has_post_thumbnail( $post_id ) ? get_the_post_thumbnail_url( $post_id, 'blush-wide' ) : '';
	if ( ! $image ) {
		$image = blush_img( 'page_hero_image', $fallback );
	}
	$els = array( blush_el_heading( get_the_title( $post_id ), 'h1', $c['white'], 52, 34 ) );
	if ( '' !== (string) $subtitle ) {
		$els[] = blush_el_text( $subtitle, $c['white'], 20, array( '_css_classes' => 'blush-el-display' ) );
	}
	return blush_el_section(
		array_merge(
			array(
				'min_height'     => blush_el_size( 340 ),
				'flex_align_items' => 'center',
				'padding'        => blush_el_box( 72, 24, 72, 24 ),
			),
			blush_el_bg( $image, $c['ink'], 'center center' )
		),
		$els
	);
}

/**
 * "Who we are".
 *
 * @return array
 */
function blush_el_sec_about() {
	$c      = blush_el_colors();
	$images = array();
	for ( $i = 1; $i <= 4; $i++ ) {
		$images[] = blush_el_image(
			blush_img( 'about_img_' . $i, 'about-' . $i . '.svg' ),
			array(
				'_element_width'        => 'initial',
				'_element_custom_width' => blush_el_size( 48, '%' ),
			)
		);
	}
	$collage = blush_el_container(
		array(
			'content_width'          => 'full',
			'width'                  => blush_el_size( 50, '%' ),
			'width_mobile'           => blush_el_size( 100, '%' ),
			'flex_direction'         => 'row',
			'flex_wrap'              => 'wrap',
			'flex_justify_content'   => 'space-between',
			'flex_gap'               => array(
				'column'   => '16',
				'row'      => '16',
				'isLinked' => true,
				'unit'     => 'px',
			),
			'padding'                => blush_el_box( 0 ),
		),
		$images
	);

	$right = array();
	if ( '' !== blush_mod( 'years_number' ) ) {
		$right[] = blush_el_text( blush_mod( 'years_number' ) . ' ' . blush_mod( 'years_label' ), $c['primary'], 20, array( 'typography_font_weight' => '700' ) );
	}
	$right[] = blush_el_eyebrow( blush_mod( 'about_eyebrow' ), $c['heading'] );
	$right[] = blush_el_heading( blush_mod( 'about_title' ), 'h2', $c['primary'], 44, 30 );
	$right[] = blush_el_text( blush_mod( 'about_text' ), $c['text'] );
	$right[] = blush_el_icon_box( 'fas fa-store', blush_mod( 'about_f1_title' ), blush_mod( 'about_f1_text' ), 'left', array( 'icon_bg' => $c['accent'], 'icon' => $c['white'], 'title' => $c['primary'], 'text' => $c['text'] ) );
	$right[] = blush_el_icon_box( 'fas fa-star', blush_mod( 'about_f2_title' ), blush_mod( 'about_f2_text' ), 'left', array( 'icon_bg' => $c['accent'], 'icon' => $c['white'], 'title' => $c['primary'], 'text' => $c['text'] ) );

	$content = blush_el_container(
		array(
			'content_width'  => 'full',
			'width'          => blush_el_size( 50, '%' ),
			'width_mobile'   => blush_el_size( 100, '%' ),
			'flex_direction' => 'column',
			'flex_gap'       => array(
				'column'   => '16',
				'row'      => '16',
				'isLinked' => true,
				'unit'     => 'px',
			),
			'padding'        => blush_el_box( 0, 0, 0, 24 ),
			'padding_mobile' => blush_el_box( 0 ),
		),
		$right
	);

	return blush_el_section(
		array(
			'flex_direction'        => 'row',
			'flex_direction_mobile' => 'column',
			'flex_align_items'      => 'center',
			'flex_gap'              => array(
				'column'   => '48',
				'row'      => '32',
				'isLinked' => false,
				'unit'     => 'px',
			),
		),
		array( $collage, $content )
	);
}

/**
 * Gradient call-to-action banner.
 *
 * @return array
 */
function blush_el_sec_cta() {
	$c   = blush_el_colors();
	$els = array(
		blush_el_heading( blush_mod( 'cta_title' ), 'h2', $c['white'], 42, 28, array( '_element_custom_width' => blush_el_size( 620 ) ) ),
		blush_el_text( blush_mod( 'cta_text' ), $c['white'], 17, array( '_element_custom_width' => blush_el_size( 620 ) ) ),
	);
	if ( '' !== blush_mod( 'cta_btn_text' ) ) {
		$els[] = blush_el_button( blush_mod( 'cta_btn_text' ), blush_link( 'cta_btn_url', 'services', '#services' ), $c['dark'], $c['white'] );
	}
	return blush_el_section(
		array_merge( array( 'min_height' => blush_el_size( 440 ) ), blush_el_bg( blush_img( 'cta_image', 'cta-makeup.svg' ), $c['ink'], 'center right' ) ),
		$els
	);
}

/**
 * "Why choose us".
 *
 * @return array
 */
function blush_el_sec_why() {
	$c     = blush_el_colors();
	$icons = array( 1 => 'fas fa-leaf', 2 => 'fas fa-gem', 3 => 'fas fa-cut', 4 => 'fas fa-star' );
	$boxes = array();
	foreach ( $icons as $i => $icon ) {
		if ( '' === blush_mod( 'why_f' . $i . '_title' ) ) {
			continue;
		}
		$boxes[] = blush_el_container(
			array(
				'content_width'  => 'full',
				'width'          => blush_el_size( 48, '%' ),
				'width_mobile'   => blush_el_size( 100, '%' ),
				'padding'        => blush_el_box( 0 ),
			),
			array( blush_el_icon_box( $icon, blush_mod( 'why_f' . $i . '_title' ), blush_mod( 'why_f' . $i . '_text' ), 'left', array( 'icon_bg' => $c['white'], 'icon' => $c['primary'], 'title' => $c['white'], 'text' => $c['white'] ) ) )
		);
	}
	$grid = blush_el_container(
		array(
			'content_width'        => 'full',
			'flex_direction'       => 'row',
			'flex_wrap'            => 'wrap',
			'flex_justify_content' => 'space-between',
			'flex_gap'             => array(
				'column'   => '24',
				'row'      => '28',
				'isLinked' => true,
				'unit'     => 'px',
			),
			'padding'              => blush_el_box( 0 ),
		),
		$boxes
	);
	return blush_el_section(
		blush_el_bg( blush_img( 'why_image', 'why-bg.svg' ), $c['ink'], 'center right' ),
		array(
			blush_el_eyebrow( blush_mod( 'why_eyebrow' ), $c['white'] ),
			blush_el_heading( blush_mod( 'why_title' ), 'h2', $c['white'], 44, 30 ),
			blush_el_text( blush_mod( 'why_text' ), $c['white'] ),
			$grid,
		)
	);
}

/**
 * Video banner.
 *
 * @return array
 */
function blush_el_sec_video() {
	$c   = blush_el_colors();
	$els = array();
	if ( '' !== blush_mod( 'video_url' ) ) {
		$els[] = blush_el_widget(
			'icon',
			array(
				'selected_icon'  => array(
					'value'   => 'fas fa-play',
					'library' => 'fa-solid',
				),
				'link'           => blush_el_link( blush_mod( 'video_url' ) ),
				'view'           => 'stacked',
				'shape'          => 'circle',
				'primary_color'  => $c['white'],
				'secondary_color' => $c['primary'],
				'size'           => blush_el_size( 36 ),
				'align'          => 'center',
			)
		);
	}
	$els[] = blush_el_heading( blush_mod( 'video_title' ), 'h2', $c['white'], 44, 30, array( 'align' => 'center' ) );
	$els[] = blush_el_text( blush_mod( 'video_text' ), $c['white'], 17, array( 'align' => 'center' ) );
	if ( '' !== blush_mod( 'video_btn_text' ) ) {
		$els[] = blush_el_button( blush_mod( 'video_btn_text' ), blush_link( 'video_btn_url', 'about' ), $c['accent'], $c['dark'] );
	}
	return blush_el_section(
		array_merge(
			array(
				'min_height'       => blush_el_size( 460 ),
				'flex_align_items' => 'center',
			),
			blush_el_bg( blush_img( 'video_image', 'video-bg.svg' ), $c['dark'], 'center center' )
		),
		$els
	);
}

/**
 * Vision / Mission / Motto.
 *
 * @return array
 */
function blush_el_sec_values() {
	$c     = blush_el_colors();
	$icons = array( 1 => 'fas fa-eye', 2 => 'fas fa-bullseye', 3 => 'fas fa-star' );
	$cards = array();
	foreach ( $icons as $i => $icon ) {
		if ( '' === blush_mod( 'value_' . $i . '_title' ) ) {
			continue;
		}
		$cards[] = blush_el_container(
			array(
				'content_width'          => 'full',
				'width'                  => blush_el_size( 31, '%' ),
				'width_mobile'           => blush_el_size( 100, '%' ),
				'background_background'  => 'classic',
				'background_color'       => $c['white'],
				'border_radius'          => blush_el_box( 16 ),
				'padding'                => blush_el_box( 32 ),
			),
			array( blush_el_icon_box( $icon, blush_mod( 'value_' . $i . '_title' ), blush_mod( 'value_' . $i . '_text' ), 'top', array( 'icon_bg' => $c['accent'], 'icon' => $c['white'], 'title' => $c['heading'], 'text' => $c['text'] ) ) )
		);
	}
	$row = blush_el_container(
		array(
			'content_width'        => 'full',
			'flex_direction'       => 'row',
			'flex_wrap'            => 'wrap',
			'flex_justify_content' => 'space-between',
			'flex_gap'             => array(
				'column'   => '24',
				'row'      => '24',
				'isLinked' => true,
				'unit'     => 'px',
			),
			'padding'              => blush_el_box( 0 ),
		),
		$cards
	);
	return blush_el_section(
		array(
			'background_background' => 'classic',
			'background_color'      => $c['blush'],
			'flex_align_items'      => 'center',
		),
		array(
			blush_el_eyebrow( blush_mod( 'values_eyebrow' ), $c['heading'] ),
			blush_el_heading( blush_mod( 'values_title' ), 'h2', $c['primary'], 40, 28, array( 'align' => 'center' ) ),
			$row,
		)
	);
}

/**
 * Wrap a dynamic theme section as a Shortcode widget in a plain container.
 *
 * @param string $shortcode Shortcode.
 * @return array
 */
function blush_el_sec_shortcode( $shortcode ) {
	return blush_el_container(
		array(
			'content_width'  => 'full',
			'flex_direction' => 'column',
			'padding'        => blush_el_box( 0 ),
		),
		array( blush_el_shortcode( $shortcode ) )
	);
}

/* ==========================================================================
   Page layouts
   ========================================================================== */

/**
 * Elementor elements for a page template, or an empty array if there is none.
 *
 * @param int $post_id Page ID.
 * @return array
 */
function blush_el_demo_elements( $post_id ) {
	$slug = (string) get_post_meta( $post_id, '_wp_page_template', true );
	if ( 'default' === $slug ) {
		$slug = '';
	}

	if ( ( '' === $slug || 'front-page.php' === $slug ) && (int) get_option( 'page_on_front' ) === (int) $post_id ) {
		$slug = 'front-page.php';
	}

	$previous = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;
	// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	$GLOBALS['post'] = get_post( $post_id );
	setup_postdata( $GLOBALS['post'] );

	switch ( $slug ) {
		case 'front-page.php':
			$elements = array(
				blush_el_sec_hero(),
				blush_el_sec_shortcode( '[blush_booking_bar]' ),
				blush_el_sec_about(),
				blush_el_sec_cta(),
				blush_el_sec_shortcode( '[blush_services limit="6"]' ),
				blush_el_sec_shortcode( '[blush_booking_form]' ),
				blush_el_sec_why(),
				blush_el_sec_shortcode( '[blush_testimonials]' ),
				blush_el_sec_shortcode( '[blush_logos]' ),
				blush_el_sec_video(),
			);
			break;
		case 'page-templates/template-about.php':
			$elements = array(
				blush_el_sec_page_hero( 'page-hero.svg' ),
				blush_el_sec_about(),
				blush_el_sec_values(),
				blush_el_sec_cta(),
				blush_el_sec_shortcode( '[blush_team]' ),
				blush_el_sec_shortcode( '[blush_logos]' ),
			);
			break;
		case 'page-templates/template-services.php':
			$elements = array(
				blush_el_sec_page_hero( 'hero-services.svg' ),
				blush_el_sec_shortcode( '[blush_services show_button="false"]' ),
				blush_el_sec_why(),
				blush_el_sec_shortcode( '[blush_testimonials]' ),
				blush_el_sec_shortcode( '[blush_logos]' ),
				blush_el_sec_video(),
			);
			break;
		case 'page-templates/template-pricing.php':
			$elements = array(
				blush_el_sec_page_hero( 'hero-pricing.svg' ),
				blush_el_sec_shortcode( '[blush_pricing]' ),
				blush_el_sec_why(),
				blush_el_sec_shortcode( '[blush_faqs]' ),
			);
			break;
		case 'page-templates/template-contact.php':
			$elements = array(
				blush_el_sec_page_hero( 'hero-contact.svg' ),
				blush_el_sec_shortcode( '[blush_contact_form]' ),
				blush_el_sec_shortcode( '[blush_map]' ),
			);
			break;
		default:
			$elements = array();
	}

	if ( $previous ) {
		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$GLOBALS['post'] = $previous;
		setup_postdata( $previous );
	} else {
		wp_reset_postdata();
	}

	return (array) apply_filters( 'blush_elementor_demo_elements', $elements, $post_id, $slug );
}

/* ==========================================================================
   Hand the layout to the Elementor editor (without saving it)
   ========================================================================== */

/**
 * Is the Elementor editor (or its preview frame) loading this post right now?
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function blush_el_seed_context( $post_id ) {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended
	if ( is_admin() && isset( $_GET['action'], $_GET['post'] ) && 'elementor' === $_GET['action'] && (int) $_GET['post'] === (int) $post_id ) {
		return true;
	}
	if ( isset( $_GET['elementor-preview'] ) && (int) $_GET['elementor-preview'] === (int) $post_id ) {
		return true;
	}
	// phpcs:enable
	return false;
}

/**
 * Serve the demo layout as the page's Elementor data until real data is saved.
 *
 * @param mixed  $value    Short-circuit value.
 * @param int    $post_id  Post ID.
 * @param string $meta_key Meta key.
 * @param bool   $single   Single value requested.
 * @return mixed
 */
function blush_el_seed_meta( $value, $post_id, $meta_key, $single ) {
	static $busy = false;

	if ( null !== $value || $busy || ! in_array( $meta_key, array( '_elementor_data', '_elementor_edit_mode' ), true ) ) {
		return $value;
	}
	if ( 'page' !== get_post_type( $post_id ) || ! blush_el_seed_context( $post_id ) ) {
		return $value;
	}

	$busy = true;
	$raw  = get_post_meta( $post_id, '_elementor_data', true );
	$busy = false;

	$saved = false;
	if ( is_array( $raw ) ) {
		$saved = ! empty( $raw );
	} elseif ( is_string( $raw ) ) {
		$trimmed = trim( $raw );
		$saved   = '' !== $trimmed && '[]' !== $trimmed && '""' !== $trimmed;
	}
	if ( $saved ) {
		return $value;
	}

	if ( '_elementor_edit_mode' === $meta_key ) {
		$busy     = true;
		$elements = blush_el_demo_elements( $post_id );
		$busy     = false;
		if ( empty( $elements ) ) {
			return $value;
		}
		return $single ? 'builder' : array( 'builder' );
	}

	$busy     = true;
	$elements = blush_el_demo_elements( $post_id );
	$busy     = false;
	if ( empty( $elements ) ) {
		return $value;
	}

	$json = wp_json_encode( $elements );
	return $single ? $json : array( $json );
}
add_filter( 'get_post_metadata', 'blush_el_seed_meta', 10, 4 );
