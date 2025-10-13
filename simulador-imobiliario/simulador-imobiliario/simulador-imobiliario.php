<?php
/**
* Plugin Name: Simulador Imobiliário - Financiamento & Obras
* URI do plug-in: https://example.com/
* Description: Simuladores integrados de financiamento e saldo de obras com shortcodes, blocos Gutenberg e widgets Elementor.
* Versão: 1.0.4
* Versão: 1.0.5
* Autor: OpenAI Codex
* URI do autor: https://example.com/
* Text Domain: simulador-imobiliario
* Caminho do domínio: /idiomas
*/

se ( ! definido ( 'ABSPATH' ) ) {
    saída ;
}

se ( ! definido ( 'SIMIMOB_PLUGIN_FILE' ) ) {
    define ( 'SIMIMOB_PLUGIN_FILE' , __FILE__ );
}

se ( ! definido ( 'SIMIMOB_VERSION' ) ) {
    define ( 'SIMIMOB_VERSION' , '1.0. 4 ' );
    define ( 'SIMIMOB_VERSION' , '1.0. 5 ' );
}

se ( ! definido ( 'SIMIMOB_PLUGIN_DIR' ) ) {
    define ( 'SIMIMOB_PLUGIN_DIR' , plugin_dir_path ( SIMIMOB_PLUGIN_FILE ) );
}

se ( ! definido ( 'SIMIMOB_PLUGIN_URL' ) ) {
    define ( 'SIMIMOB_PLUGIN_URL' , plugin_dir_url ( SIMIMOB_PLUGIN_FILE ) );
}

require_once SIMIMOB_PLUGIN_DIR . 'includes/helpers.php' ;
require_once SIMIMOB_PLUGIN_DIR . 'includes/class-settings.php' ;
require_once SIMIMOB_PLUGIN_DIR . 'includes/class-admin.php' ;
require_once SIMIMOB_PLUGIN_DIR . 'includes/class-shortcodes.php' ;
require_once SIMIMOB_PLUGIN_DIR . 'includes/class-rest.php' ;

/**
* Inicializar a funcionalidade do plugin.
*/
função simimob_init ( ) {
 
    load_plugin_textdomain ( 'simulador-imobiliario' , false , dirname ( plugin_basename (SIMIMOB_PLUGIN_FILE)). '/languages' );

    SIMIMOB_Settings :: obter_instância ();
    SIMIMOB_Admin :: obter_instância ();
    SIMIMOB_Shortcodes :: obter_instância ();
