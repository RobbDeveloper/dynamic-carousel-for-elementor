<?php
/**
 * Dynamic Carousel Widget for Elementor
 * 
 * A fully customizable carousel widget that supports images, videos, and Elementor templates
 * with dynamic width slides and responsive controls.
 */

namespace ElementorCustomWidgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH')) exit;

class Dynamic_Carousel_Widget extends Widget_Base {

    public function get_name() {
        return 'dynamic-carousel';
    }

    public function get_title() {
        return __('Dynamic Carousel', 'elementor-custom-widgets');
    }

    public function get_icon() {
        return 'eicon-slider-push';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_keywords() {
        return ['carousel', 'slider', 'gallery', 'video', 'template', 'dynamic', 'acf'];
    }

    public function get_script_depends() {
        return ['dynamic-carousel-script'];
    }

    public function get_style_depends() {
        return ['dynamic-carousel-style'];
    }

    protected function register_controls() {
        
        // Content Section - Slides
        $this->start_controls_section(
            'section_slides',
            [
                'label' => __('Slides', 'elementor-custom-widgets'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'slide_type',
            [
                'label' => __('Slide Type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => 'image',
                'options' => [
                    'image' => __('Single Image', 'elementor-custom-widgets'),
                    'acf_gallery' => __('ACF Gallery', 'elementor-custom-widgets'),
                    'video' => __('Video', 'elementor-custom-widgets'),
                    'template' => __('Elementor Template', 'elementor-custom-widgets'),
                ],
            ]
        );

        // Single Image Controls
        $repeater->add_control(
            'image',
            [
                'label' => __('Choose Image', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => ['active' => true],
                'default' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
                'condition' => ['slide_type' => 'image'],
            ]
        );

        $repeater->add_control(
            'image_aspect_ratio',
            [
                'label' => __('Aspect Ratio', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => '16-9',
                'options' => [
                    '1-1' => '1:1',
                    '2-3' => '2:3',
                    '3-2' => '3:2',
                    '4-3' => '4:3',
                    '16-9' => '16:9',
                    '21-9' => '21:9',
                    'custom' => __('Custom', 'elementor-custom-widgets'),
                ],
                'condition' => ['slide_type' => 'image'],
            ]
        );

        $repeater->add_control(
            'image_custom_ratio',
            [
                'label' => __('Custom Ratio (width/height)', 'elementor-custom-widgets'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0.1,
                'max' => 10,
                'step' => 0.1,
                'default' => 1.5,
                'condition' => [
                    'slide_type' => 'image',
                    'image_aspect_ratio' => 'custom',
                ],
            ]
        );

        // ACF Gallery Controls
        $repeater->add_control(
            'acf_gallery_field',
            [
                'label' => __('ACF Gallery Field Name', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'placeholder' => __('gallery_field_name', 'elementor-custom-widgets'),
                'description' => __('Enter the ACF gallery field name', 'elementor-custom-widgets'),
                'condition' => ['slide_type' => 'acf_gallery'],
            ]
        );

        $repeater->add_control(
            'acf_gallery_aspect_ratio',
            [
                'label' => __('Gallery Images Aspect Ratio', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => 'original',
                'options' => [
                    'original' => __('Original (Auto)', 'elementor-custom-widgets'),
                    '1-1' => '1:1',
                    '2-3' => '2:3',
                    '3-2' => '3:2',
                    '4-3' => '4:3',
                    '16-9' => '16:9',
                    '21-9' => '21:9',
                    'custom' => __('Custom', 'elementor-custom-widgets'),
                ],
                'condition' => ['slide_type' => 'acf_gallery'],
            ]
        );

        $repeater->add_control(
            'acf_gallery_custom_ratio',
            [
                'label' => __('Custom Ratio (width/height)', 'elementor-custom-widgets'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0.1,
                'max' => 10,
                'step' => 0.1,
                'default' => 1.5,
                'condition' => [
                    'slide_type' => 'acf_gallery',
                    'acf_gallery_aspect_ratio' => 'custom',
                ],
            ]
        );

        // Video Controls
        $repeater->add_control(
            'video_type',
            [
                'label' => __('Video Type', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => 'youtube',
                'options' => [
                    'youtube' => __('YouTube', 'elementor-custom-widgets'),
                    'vimeo' => __('Vimeo', 'elementor-custom-widgets'),
                    'hosted' => __('Self Hosted', 'elementor-custom-widgets'),
                ],
                'condition' => ['slide_type' => 'video'],
            ]
        );

        $repeater->add_control(
            'youtube_url',
            [
                'label' => __('YouTube URL', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'placeholder' => 'https://www.youtube.com/watch?v=...',
                'label_block' => true,
                'condition' => [
                    'slide_type' => 'video',
                    'video_type' => 'youtube',
                ],
            ]
        );

        $repeater->add_control(
            'vimeo_url',
            [
                'label' => __('Vimeo URL', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'placeholder' => 'https://vimeo.com/...',
                'label_block' => true,
                'condition' => [
                    'slide_type' => 'video',
                    'video_type' => 'vimeo',
                ],
            ]
        );

        $repeater->add_control(
            'hosted_video',
            [
                'label' => __('Video File', 'elementor-custom-widgets'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => ['active' => true],
                'media_type' => 'video',
                'condition' => [
                    'slide_type' => 'video',
                    'video_type' => 'hosted',
                ],
            ]
        );

        $repeater->add_control(
            'hosted_video_url',
            [
                'label' => __('Or Video URL', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'placeholder' => 'https://example.com/video.mp4',
                'description' => __('Alternative: paste direct video URL', 'elementor-custom-widgets'),
                'label_block' => true,
                'condition' => [
                    'slide_type' => 'video',
                    'video_type' => 'hosted',
                ],
            ]
        );

        $repeater->add_control(
            'video_aspect_ratio',
            [
                'label' => __('Aspect Ratio', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SELECT,
                'default' => '16-9',
                'options' => [
                    '1-1' => '1:1',
                    '2-3' => '2:3',
                    '3-2' => '3:2',
                    '4-3' => '4:3',
                    '16-9' => '16:9',
                    '21-9' => '21:9',
                    'custom' => __('Custom', 'elementor-custom-widgets'),
                ],
                'condition' => ['slide_type' => 'video'],
            ]
        );

        $repeater->add_control(
            'video_custom_ratio',
            [
                'label' => __('Custom Ratio (width/height)', 'elementor-custom-widgets'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0.1,
                'max' => 10,
                'step' => 0.1,
                'default' => 1.5,
                'condition' => [
                    'slide_type' => 'video',
                    'video_aspect_ratio' => 'custom',
                ],
            ]
        );

        // Template Controls
        $repeater->add_control(
            'template_id',
            [
                'label' => __('Template ID', 'elementor-custom-widgets'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'placeholder' => __('123 or [elementor-template id="123"]', 'elementor-custom-widgets'),
                'condition' => ['slide_type' => 'template'],
            ]
        );

        $repeater->add_responsive_control(
            'template_width',
            [
                'label' => __('Template Width', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => ['min' => 100, 'max' => 2000],
                    '%' => ['min' => 10, 'max' => 100],
                    'vw' => ['min' => 10, 'max' => 100],
                ],
                'default' => ['unit' => 'px', 'size' => 400],
                'condition' => ['slide_type' => 'template'],
            ]
        );

        $repeater->add_control(
            'slide_link',
            [
                'label' => __('Link', 'elementor-custom-widgets'),
                'type' => Controls_Manager::URL,
                'dynamic' => ['active' => true],
                'placeholder' => __('https://your-link.com', 'elementor-custom-widgets'),
                'condition' => ['slide_type!' => 'acf_gallery'],
            ]
        );

        $this->add_control(
            'slides',
            [
                'label' => __('Slides', 'elementor-custom-widgets'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['slide_type' => 'image', 'image_aspect_ratio' => '16-9'],
                ],
                'title_field' => '{{{ slide_type }}}',
            ]
        );

        $this->end_controls_section();

        // Carousel Settings
        $this->start_controls_section(
            'section_carousel_settings',
            [
                'label' => __('Carousel Settings', 'elementor-custom-widgets'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'carousel_height',
            [
                'label' => __('Carousel Height', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => ['min' => 100, 'max' => 1000],
                    'vh' => ['min' => 10, 'max' => 100],
                ],
                'default' => ['unit' => 'px', 'size' => 500],
                'selectors' => [
                    '{{WRAPPER}} .dynamic-carousel-slide' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slide_spacing',
            [
                'label' => __('Slide Spacing', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 0, 'max' => 100]],
                'default' => ['unit' => 'px', 'size' => 20],
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __('Autoplay', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => __('Autoplay Speed (ms)', 'elementor-custom-widgets'),
                'type' => Controls_Manager::NUMBER,
                'default' => 3000,
                'condition' => ['autoplay' => 'yes'],
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => __('Infinite Loop', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'transition_speed',
            [
                'label' => __('Transition Speed (ms)', 'elementor-custom-widgets'),
                'type' => Controls_Manager::NUMBER,
                'default' => 500,
            ]
        );

        $this->end_controls_section();

        // Navigation Style
        $this->start_controls_section(
            'section_navigation_style',
            [
                'label' => __('Navigation Arrows', 'elementor-custom-widgets'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'show_arrows',
            [
                'label' => __('Show Arrows', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'arrow_prev_icon',
            [
                'label' => __('Previous Icon', 'elementor-custom-widgets'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-chevron-left',
                    'library' => 'fa-solid',
                ],
                'condition' => ['show_arrows' => 'yes'],
            ]
        );

        $this->add_control(
            'arrow_next_icon',
            [
                'label' => __('Next Icon', 'elementor-custom-widgets'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-chevron-right',
                    'library' => 'fa-solid',
                ],
                'condition' => ['show_arrows' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'arrows_size',
            [
                'label' => __('Arrow Size', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => 10, 'max' => 100]],
                'default' => ['unit' => 'px', 'size' => 40],
                'selectors' => [
                    '{{WRAPPER}} .carousel-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .carousel-arrow i' => 'font-size: calc({{SIZE}}{{UNIT}} / 2);',
                    '{{WRAPPER}} .carousel-arrow svg' => 'width: calc({{SIZE}}{{UNIT}} / 2); height: calc({{SIZE}}{{UNIT}} / 2);',
                ],
                'condition' => ['show_arrows' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'arrows_position',
            [
                'label' => __('Arrow Position', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => ['px' => ['min' => -100, 'max' => 100]],
                'default' => ['unit' => 'px', 'size' => 20],
                'selectors' => [
                    '{{WRAPPER}} .carousel-arrow-left' => 'left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .carousel-arrow-right' => 'right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => ['show_arrows' => 'yes'],
            ]
        );

        $this->start_controls_tabs('arrows_style_tabs');

        $this->start_controls_tab('arrows_normal_tab', [
            'label' => __('Normal', 'elementor-custom-widgets'),
        ]);

        $this->add_control(
            'arrows_icon_color',
            [
                'label' => __('Icon Color', 'elementor-custom-widgets'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .carousel-arrow' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .carousel-arrow svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_background',
            [
                'label' => __('Background Color', 'elementor-custom-widgets'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.5)',
                'selectors' => ['{{WRAPPER}} .carousel-arrow' => 'background-color: {{VALUE}};'],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('arrows_hover_tab', [
            'label' => __('Hover', 'elementor-custom-widgets'),
        ]);

        $this->add_control(
            'arrows_hover_icon_color',
            [
                'label' => __('Icon Color', 'elementor-custom-widgets'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .carousel-arrow:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .carousel-arrow:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_hover_background',
            [
                'label' => __('Background Color', 'elementor-custom-widgets'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.8)',
                'selectors' => ['{{WRAPPER}} .carousel-arrow:hover' => 'background-color: {{VALUE}};'],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'arrows_border',
                'selector' => '{{WRAPPER}} .carousel-arrow',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'arrows_border_radius',
            [
                'label' => __('Border Radius', 'elementor-custom-widgets'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .carousel-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Pagination Style
        $this->start_controls_section(
            'section_pagination_style',
            [
                'label' => __('Pagination Dots', 'elementor-custom-widgets'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label' => __('Show Pagination', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'pagination_position',
            [
                'label' => __('Position from Bottom', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SLIDER,
                'default' => ['unit' => 'px', 'size' => 20],
                'selectors' => [
                    '{{WRAPPER}} .carousel-pagination' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_size',
            [
                'label' => __('Dot Size', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SLIDER,
                'default' => ['unit' => 'px', 'size' => 10],
                'selectors' => [
                    '{{WRAPPER}} .carousel-dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_spacing',
            [
                'label' => __('Dot Spacing', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SLIDER,
                'default' => ['unit' => 'px', 'size' => 8],
                'selectors' => [
                    '{{WRAPPER}} .carousel-dot:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('pagination_style_tabs');

        $this->start_controls_tab('pagination_normal_tab', [
            'label' => __('Normal', 'elementor-custom-widgets'),
        ]);

        $this->add_control(
            'dots_color',
            [
                'label' => __('Dot Color', 'elementor-custom-widgets'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.5)',
                'selectors' => ['{{WRAPPER}} .carousel-dot' => 'background-color: {{VALUE}};'],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('pagination_hover_tab', [
            'label' => __('Hover', 'elementor-custom-widgets'),
        ]);

        $this->add_control(
            'dots_hover_color',
            [
                'label' => __('Dot Color', 'elementor-custom-widgets'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.8)',
                'selectors' => ['{{WRAPPER}} .carousel-dot:hover' => 'background-color: {{VALUE}};'],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('pagination_active_tab', [
            'label' => __('Active', 'elementor-custom-widgets'),
        ]);

        $this->add_control(
            'dots_active_color',
            [
                'label' => __('Dot Color', 'elementor-custom-widgets'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => ['{{WRAPPER}} .carousel-dot.active' => 'background-color: {{VALUE}};'],
            ]
        );

        $this->add_responsive_control(
            'dots_active_scale',
            [
                'label' => __('Scale', 'elementor-custom-widgets'),
                'type' => Controls_Manager::SLIDER,
                'default' => ['size' => 1.2],
                'range' => ['px' => ['min' => 0.5, 'max' => 2, 'step' => 0.1]],
                'selectors' => ['{{WRAPPER}} .carousel-dot.active' => 'transform: scale({{SIZE}});'],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'dots_border_radius',
            [
                'label' => __('Border Radius', 'elementor-custom-widgets'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => ['unit' => '%', 'top' => 50, 'right' => 50, 'bottom' => 50, 'left' => 50],
                'selectors' => [
                    '{{WRAPPER}} .carousel-dot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Slide Style
        $this->start_controls_section(
            'section_slide_style',
            [
                'label' => __('Slides', 'elementor-custom-widgets'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'slide_border_radius',
            [
                'label' => __('Border Radius', 'elementor-custom-widgets'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dynamic-carousel-slide' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $slides = $settings['slides'];

        if (empty($slides)) {
            return;
        }

        $carousel_id = 'carousel-' . $this->get_id();

        // Build selector matching Elementor's {{WRAPPER}} pattern
        $wrapper_selector = '.elementor-element.elementor-element-' . $this->get_id();
        $post_id = get_the_ID();
        if ($post_id) {
            $wrapper_selector = '.post-' . $post_id . ' ' . $wrapper_selector;
        }

        // Get responsive slide spacing - default to 20px if not set
        $slide_spacing = isset($settings['slide_spacing']['size']) ? $settings['slide_spacing'] : ['size' => 20, 'unit' => 'px'];

        // Get transition speed (responsive control)
        $transition_speed = isset($settings['transition_speed']) ? intval($settings['transition_speed']) : 500;

        $carousel_settings = [
            'autoplay' => $settings['autoplay'] === 'yes',
            'autoplaySpeed' => isset($settings['autoplay_speed']) ? intval($settings['autoplay_speed']) : 3000,
            'loop' => $settings['loop'] === 'yes',
            'slideSpacing' => $slide_spacing,
            'transitionSpeed' => $transition_speed,
        ];

        $processed_slides = $this->process_slides($slides, $settings);

        // Output inline styles for dynamic colors
        ?>
        <style>
            <?php
            // Dots normal color
            if (!empty($settings['dots_color'])) : ?>
                <?php echo esc_attr($wrapper_selector); ?> .carousel-dot {
                    background-color: <?php echo esc_attr($settings['dots_color']); ?>;
                }
            <?php endif;

            // Dots hover color
            if (!empty($settings['dots_hover_color'])) : ?>
                <?php echo esc_attr($wrapper_selector); ?> .carousel-dot:hover {
                    background-color: <?php echo esc_attr($settings['dots_hover_color']); ?>;
                }
            <?php endif;

            // Dots active color
            if (!empty($settings['dots_active_color'])) : ?>
                <?php echo esc_attr($wrapper_selector); ?> .carousel-dot.active {
                    background-color: <?php echo esc_attr($settings['dots_active_color']); ?>;
                }
            <?php endif; ?>
        </style>

        <div class="dynamic-carousel-wrapper" id="<?php echo esc_attr($carousel_id); ?>" data-settings='<?php echo wp_json_encode($carousel_settings); ?>'>
            <div class="dynamic-carousel-container">
                <div class="dynamic-carousel-track">
                    <?php foreach ($processed_slides as $index => $slide) : 
                        $slide_width = $this->calculate_slide_width($slide, $settings);
                        $slide_styles = [];
                        
                        if ($slide_width) {
                            $slide_styles[] = 'width: ' . $slide_width;
                        }
                        
                        if ($index < count($processed_slides) - 1) {
                            $slide_styles[] = 'margin-right: ' . $slide_spacing['size'] . $slide_spacing['unit'];
                        }
                        
                        $style_attr = !empty($slide_styles) ? 'style="' . esc_attr(implode('; ', $slide_styles)) . '"' : '';
                        ?>
                        
                        <div class="dynamic-carousel-slide" data-slide-index="<?php echo esc_attr($index); ?>" <?php echo $style_attr; ?>>
                            <?php
                            if (isset($slide['slide_type']) && $slide['slide_type'] === 'placeholder') {
                                // Render placeholder for empty ACF galleries in editor
                                $this->render_placeholder_slide($slide);
                            } else {
                                $has_link = !empty($slide['slide_link']['url']);
                                if ($has_link) {
                                    $this->add_link_attributes('slide_link_' . $index, $slide['slide_link']);
                                    echo '<a ' . $this->get_render_attribute_string('slide_link_' . $index) . ' class="carousel-slide-link">';
                                }

                                switch ($slide['slide_type']) {
                                    case 'image':
                                        $this->render_image_slide($slide);
                                        break;
                                    case 'video':
                                        $this->render_video_slide($slide);
                                        break;
                                    case 'template':
                                        $this->render_template_slide($slide);
                                        break;
                                }

                                if ($has_link) {
                                    echo '</a>';
                                }
                            }
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <?php if ($settings['show_arrows'] === 'yes') : ?>
                <div class="carousel-navigation">
                    <button class="carousel-arrow carousel-arrow-left" aria-label="<?php esc_attr_e('Previous slide', 'elementor-custom-widgets'); ?>">
                        <?php
                        if (!empty($settings['arrow_prev_icon']['value'])) {
                            \Elementor\Icons_Manager::render_icon($settings['arrow_prev_icon'], ['aria-hidden' => 'true']);
                        } else {
                            // Fallback to default icon
                            ?>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                            </svg>
                            <?php
                        }
                        ?>
                    </button>
                    <button class="carousel-arrow carousel-arrow-right" aria-label="<?php esc_attr_e('Next slide', 'elementor-custom-widgets'); ?>">
                        <?php
                        if (!empty($settings['arrow_next_icon']['value'])) {
                            \Elementor\Icons_Manager::render_icon($settings['arrow_next_icon'], ['aria-hidden' => 'true']);
                        } else {
                            // Fallback to default icon
                            ?>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                            </svg>
                            <?php
                        }
                        ?>
                    </button>
                </div>
            <?php endif; ?>
            
            <?php if ($settings['show_pagination'] === 'yes') : ?>
                <div class="carousel-pagination">
                    <?php foreach ($processed_slides as $index => $slide) : ?>
                        <button class="carousel-dot <?php echo $index === 0 ? 'active' : ''; ?>" 
                                data-slide-index="<?php echo esc_attr($index); ?>"
                                aria-label="<?php echo esc_attr(sprintf(__('Go to slide %d', 'elementor-custom-widgets'), $index + 1)); ?>">
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    protected function process_slides($slides, $settings) {
        $processed_slides = [];

        foreach ($slides as $slide) {
            if ($slide['slide_type'] === 'acf_gallery') {
                $gallery_images = $this->get_acf_gallery_images($slide);

                if (!empty($gallery_images)) {
                    foreach ($gallery_images as $image_id) {
                        $processed_slides[] = [
                            'slide_type' => 'image',
                            'image' => [
                                'id' => $image_id,
                                'url' => wp_get_attachment_image_url($image_id, 'full'),
                            ],
                            'image_aspect_ratio' => isset($slide['acf_gallery_aspect_ratio']) ? $slide['acf_gallery_aspect_ratio'] : 'original',
                            'image_custom_ratio' => isset($slide['acf_gallery_custom_ratio']) ? $slide['acf_gallery_custom_ratio'] : 1.5,
                            'slide_link' => [],
                        ];
                    }
                } else {
                    // If ACF gallery is empty, show placeholder in editor
                    if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                        $processed_slides[] = [
                            'slide_type' => 'placeholder',
                            'message' => __('ACF Gallery field is empty or not found', 'elementor-custom-widgets'),
                        ];
                    }
                }
            } else {
                $processed_slides[] = $slide;
            }
        }

        return $processed_slides;
    }

    protected function get_acf_gallery_images($slide) {
        if (empty($slide['acf_gallery_field'])) {
            return [];
        }

        if (!function_exists('get_field')) {
            return [];
        }

        $field_name = $slide['acf_gallery_field'];

        // Get current post ID for dynamic content
        $post_id = get_the_ID();

        // Support for Elementor dynamic tags
        if (strpos($field_name, '[elementor-') !== false) {
            $field_name = do_shortcode($field_name);
        }

        // Get ACF gallery field from current post
        $gallery = get_field($field_name, $post_id);

        if (!$gallery || !is_array($gallery)) {
            return [];
        }

        $image_ids = [];

        foreach ($gallery as $image) {
            if (is_numeric($image)) {
                $image_ids[] = $image;
            } elseif (is_array($image) && isset($image['ID'])) {
                $image_ids[] = $image['ID'];
            } elseif (is_array($image) && isset($image['id'])) {
                $image_ids[] = $image['id'];
            }
        }

        return $image_ids;
    }

    protected function render_image_slide($slide) {
        if (empty($slide['image']['url'])) {
            return;
        }

        // Support dynamic tags
        $image_url = $slide['image']['url'];
        $image_id = isset($slide['image']['id']) ? $slide['image']['id'] : 0;

        // Handle dynamic content
        if (strpos($image_url, '[elementor-') !== false) {
            $image_url = do_shortcode($image_url);
        }
        ?>
        <div class="carousel-slide-content carousel-slide-image">
            <?php if ($image_id && is_numeric($image_id)) : ?>
                <?php echo wp_get_attachment_image($image_id, 'full', false, ['class' => 'carousel-image']); ?>
            <?php else : ?>
                <img src="<?php echo esc_url($image_url); ?>" alt="" class="carousel-image">
            <?php endif; ?>
        </div>
        <?php
    }

    protected function render_video_slide($slide) {
        $video_type = isset($slide['video_type']) ? $slide['video_type'] : 'youtube';
        ?>
        <div class="carousel-slide-content carousel-slide-video">
            <?php
            switch ($video_type) {
                case 'youtube':
                    if (!empty($slide['youtube_url'])) {
                        $youtube_url = $slide['youtube_url'];

                        // Support ACF dynamic tags
                        if (strpos($youtube_url, '[elementor-') !== false) {
                            $youtube_url = do_shortcode($youtube_url);
                        }

                        $embed_url = $this->get_youtube_embed_url($youtube_url);
                        if ($embed_url) {
                            ?>
                            <iframe
                                src="<?php echo esc_url($embed_url); ?>"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                                class="carousel-video-iframe">
                            </iframe>
                            <?php
                        }
                    }
                    break;

                case 'vimeo':
                    if (!empty($slide['vimeo_url'])) {
                        $vimeo_url = $slide['vimeo_url'];

                        // Support ACF dynamic tags
                        if (strpos($vimeo_url, '[elementor-') !== false) {
                            $vimeo_url = do_shortcode($vimeo_url);
                        }

                        $embed_url = $this->get_vimeo_embed_url($vimeo_url);
                        if ($embed_url) {
                            ?>
                            <iframe
                                src="<?php echo esc_url($embed_url); ?>"
                                frameborder="0"
                                allow="autoplay; fullscreen; picture-in-picture"
                                allowfullscreen
                                class="carousel-video-iframe">
                            </iframe>
                            <?php
                        }
                    }
                    break;

                case 'hosted':
                    $video_url = '';

                    if (!empty($slide['hosted_video']['url'])) {
                        $video_url = $slide['hosted_video']['url'];
                    } elseif (!empty($slide['hosted_video_url'])) {
                        $video_url = $slide['hosted_video_url'];
                    }

                    // Support ACF dynamic tags
                    if ($video_url && strpos($video_url, '[elementor-') !== false) {
                        $video_url = do_shortcode($video_url);
                    }

                    if ($video_url) {
                        ?>
                        <video controls class="carousel-video" controlsList="nodownload">
                            <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                            <?php esc_html_e('Your browser does not support the video tag.', 'elementor-custom-widgets'); ?>
                        </video>
                        <?php
                    }
                    break;
            }
            ?>
        </div>
        <?php
    }

    protected function render_template_slide($slide) {
        if (empty($slide['template_id'])) {
            return;
        }

        $template_id = $slide['template_id'];

        // Support ACF dynamic tags
        if (strpos($template_id, '[elementor-') !== false) {
            $template_id = do_shortcode($template_id);
        }
        ?>
        <div class="carousel-slide-content carousel-slide-template">
            <?php
            // Extract template ID from shortcode if provided
            if (strpos($template_id, '[elementor-template') !== false) {
                echo do_shortcode($template_id);
            } elseif (strpos($template_id, '[') === 0) {
                echo do_shortcode($template_id);
            } else {
                // Clean numeric ID
                $template_id = preg_replace('/[^0-9]/', '', $template_id);
                if ($template_id && is_numeric($template_id)) {
                    echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($template_id);
                }
            }
            ?>
        </div>
        <?php
    }

    protected function render_placeholder_slide($slide) {
        $message = isset($slide['message']) ? $slide['message'] : __('No content available', 'elementor-custom-widgets');
        ?>
        <div class="carousel-slide-content carousel-slide-placeholder" style="display: flex; align-items: center; justify-content: center; background: #f5f5f5; color: #999;">
            <p style="margin: 0; padding: 20px; text-align: center;"><?php echo esc_html($message); ?></p>
        </div>
        <?php
    }

    protected function calculate_slide_width($slide, $settings) {
        $carousel_height = isset($settings['carousel_height']) ? $settings['carousel_height'] : ['size' => 500, 'unit' => 'px'];
        $height_value = $carousel_height['size'];
        $height_unit = $carousel_height['unit'];
        
        if ($slide['slide_type'] === 'template') {
            $template_width = isset($slide['template_width']) ? $slide['template_width'] : ['size' => 400, 'unit' => 'px'];
            return $template_width['size'] . $template_width['unit'];
        }
        
        $aspect_ratio_key = $slide['slide_type'] === 'image' ? 'image_aspect_ratio' : 'video_aspect_ratio';
        $custom_ratio_key = $slide['slide_type'] === 'image' ? 'image_custom_ratio' : 'video_custom_ratio';
        
        $aspect_ratio = isset($slide[$aspect_ratio_key]) ? $slide[$aspect_ratio_key] : '16-9';
        
        if ($aspect_ratio === 'original' && isset($slide['image']['id'])) {
            $image_id = $slide['image']['id'];
            $image_meta = wp_get_attachment_metadata($image_id);
            
            if ($image_meta && isset($image_meta['width']) && isset($image_meta['height']) && $image_meta['height'] > 0) {
                $ratio = $image_meta['width'] / $image_meta['height'];
            } else {
                $ratio = 16/9;
            }
        } else {
            $ratio_map = [
                '1-1' => 1,
                '2-3' => 2/3,
                '3-2' => 3/2,
                '4-3' => 4/3,
                '16-9' => 16/9,
                '21-9' => 21/9,
            ];
            
            if ($aspect_ratio === 'custom' && isset($slide[$custom_ratio_key])) {
                $ratio = floatval($slide[$custom_ratio_key]);
            } else {
                $ratio = isset($ratio_map[$aspect_ratio]) ? $ratio_map[$aspect_ratio] : 16/9;
            }
        }
        
        if ($height_unit === 'px') {
            return ($height_value * $ratio) . 'px';
        } else {
            return 'calc(' . $height_value . $height_unit . ' * ' . $ratio . ')';
        }
    }

    protected function get_youtube_embed_url($url) {
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }
        return false;
    }

    protected function get_vimeo_embed_url($url) {
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
            return 'https://player.vimeo.com/video/' . $matches[1];
        }
        return false;
    }

    protected function content_template() {
        // Editor preview template
    }
}