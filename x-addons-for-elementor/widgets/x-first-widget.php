<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Elementor_xFirst_Widget extends \Elementor\Widget_Base {

    public function get_name() {
		return 'xfirstname';
	}

    public function get_title() {
		//return esc_html__( 'oEmbed', 'elementor-oembed-widget' );
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

        $this->start_controls_section(
            'content_section', // Identificador único para a seção de controles
            [
                'label' => __( 'Configurações', 'meu-ultimo-post-elementor' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
    
        // Adiciona um controle deslizante para definir o número de posts a serem exibidos
        $this->add_control(
            'posts_number', // Identificador único para o controle
            [
                'label' => __( 'Número de Posts', 'meu-ultimo-post-elementor' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => 1, // Por padrão, mostra apenas o último post
            ]
        );
    
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $number_of_posts = $settings['posts_number'];
    
        $recent_posts = wp_get_recent_posts([
            'numberposts' => $number_of_posts, // Usa o valor do controle
            'post_status' => 'publish', // Apenas posts publicados
        ]);
    
        if (count($recent_posts) === 0) {
            echo 'Nenhum post encontrado.';
            return;
        }
    
        echo '<div class="meu-ultimo-post">';
        foreach ($recent_posts as $post) {
            echo '<h2><a href="' . get_permalink($post['ID']) . '">' . $post['post_title'] . '</a></h2>';
            echo '<p>' . $post['post_excerpt'] . '</p>';
        }
        echo '</div>';
    }

}