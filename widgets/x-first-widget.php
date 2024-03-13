<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Elementor_xFirst_Widget extends \Elementor\Widget_Base {

    public function get_name() {
		return 'xfirstname';
	}

    public function get_title() {
        return esc_html__( 'xFirst', 'elementor-xfirst-widget' );
	}

    public function get_icon() {
		return 'eicon-code';
	}

    public function get_categories() {
		return [ 'basic' ];
	}

    public function get_keywords() {
		return [ 'xfirst', 'url', 'link' ];
	}

    public function get_custom_help_url() {
		return 'https://developers.elementor.com/docs/widgets/';
	}

    protected function register_controls() {

        // Controles de Conteúdo
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Configurações', 'elementor-xfirst-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
    
        // Modo de Exibição
        $this->add_control(
            'display_mode',
            [
                'label' => __('Modo de Exibição', 'elementor-xfirst-widget'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'latest' => __('Último Post', 'elementor-xfirst-widget'),
                    'multiple' => __('Vários Posts', 'elementor-xfirst-widget'),
                ],
                'default' => 'multiple',
            ]
        );
    
        // Quantidade de Colunas
        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Colunas', 'elementor-xfirst-widget'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'selectors' => [
                    '{{WRAPPER}} .meu-ultimo-post' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
                'condition' => [
                    'display_mode' => 'multiple',
                ],
            ]
        );
    
        // Quantidade de Posts por Página
        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Posts por Página', 'elementor-xfirst-widget'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => '5',
                'condition' => [
                    'display_mode' => 'multiple',
                ],
            ]
        );
    
        $this->end_controls_section();

        // Start Title Style Section
        $this->start_controls_section(
            'posttitle_style_section',
            [
                'label' => __('Post Title', 'elementor-xfirst-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
    
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'label' => __('Tipografia do Título', 'elementor-xfirst-widget'),
                    'selector' => '{{WRAPPER}} .x-post-title',
                ]
            );

            $this->add_control(
                'text_color',
                [
                    'label' => esc_html__( 'Text Color', 'textdomain' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .x-post-title a' => 'color: {{VALUE}}',
                    ],
                ]
            );

        $this->end_controls_section();


        // IMAGE
        $this->start_controls_section(
            'image_style_section',
            [
                'label' => __('Image', 'elementor-xfirst-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
    
            $this->add_control(
                'image_width',
                [
                    'label' => __('Largura da Imagem (px)', 'elementor-xfirst-widget'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => ['px'],
                    'range' => [
                        'px' => [
                            'min' => 50,
                            'max' => 1000,
                            'step' => 5,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .post-thumbnail' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'custom_dimension',
                [
                    'label' => esc_html__( 'Image Dimension', 'textdomain' ),
                    'type' => \Elementor\Controls_Manager::IMAGE_DIMENSIONS,
                    'description' => esc_html__( 'Crop the original image size to any custom size. Set custom width or height to keep the original size ratio.', 'elementor-xfirst-widget' ),
                    'default' => [
                        'width' => '',
                        'height' => '',
                    ],
                ]
            );

        $this->end_controls_section();


        //DESCRIPTION
        $this->start_controls_section(
            'description_style_section',
            [
                'label' => __('Description', 'elementor-xfirst-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
    
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name' => 'description_typography',
                    'label' => __('Tipografia da Descrição', 'elementor-xfirst-widget'),
                    'selector' => '{{WRAPPER}} .x-post-excerpt',
                ]
            );

            $this->add_control(
                'text_color',
                [
                    'label' => esc_html__( 'Text Color', 'textdomain' ),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .x-post-excerpt' => 'color: {{VALUE}}',
                    ],
                ]
            );
    
        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();
    
        $args = [
            'post_status' => 'publish',
            'posts_per_page' => $settings['display_mode'] === 'latest' ? 1 : $settings['posts_per_page'],
        ];
    
        $query = new WP_Query($args);
    
        if (!$query->have_posts()) {
            echo 'Nenhum post encontrado.';
            return;
        }
    
        echo '<div class="meu-ultimo-post" style="display: grid; grid-gap: 20px;">';
        while ($query->have_posts()) {
            $query->the_post();
    
            $post_id = get_the_ID();
            $post_thumbnail = get_the_post_thumbnail_url($post_id, 'full');
            $post_permalink = get_permalink($post_id);
            $post_title = get_the_title();
            $post_excerpt = get_the_excerpt();
    
            // Structure Post
            // Estrutura do Post
            echo '<div class="post-item">';
            if ($post_thumbnail) {
                echo '<img class="post-thumbnail" src="' . esc_url($post_thumbnail) . '" alt="' . esc_attr($post_title) . '">';
            }
            echo '<h2 class="x-post-title"><a href="' . esc_url($post_permalink) . '">' . esc_html($post_title) . '</a></h2>';
            echo '<p class="x-post-excerpt">' . esc_html($post_excerpt) . '</p>';
            echo '</div>';
        }
        echo '</div>';
    
        wp_reset_postdata();
    }

}