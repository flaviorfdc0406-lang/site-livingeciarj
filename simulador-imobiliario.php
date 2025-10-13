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
* Caminho do domínio: /simulador-imobiliário/idiomas
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

$simimob_dir       = trailingslashit ( plugin_dir_path ( __FILE__ )). 'simulador-imobiliário/' ;
$simimob_dir_url   = trailingslashit ( plugin_dir_url ( __FILE__ ) ) . 'simulador-imobiliario/' ;
$simimob_bootstrap = $simimob_dir . 'simulador-imobiliario.php' ;

se ( arquivo_existe ( $simimob_bootstrap )) {
    se ( ! definido ( 'SIMIMOB_PLUGIN_DIR' ) ) {
        define ( 'SIMIMOB_PLUGIN_DIR' , $simimob_dir );
    }

    se ( ! definido ( 'SIMIMOB_PLUGIN_URL' ) ) {
        define ( 'SIMIMOB_PLUGIN_URL' , $simimob_dir_url );
    }

    requer_uma vez $simimob_bootstrap ;
 
} outro {
    se ( ! função_existe ( 'simimob_missing_bootstrap_notice' ) ) {
        /**
         * Exibir um aviso de administrador quando o diretório do plugin empacotado não puder ser localizado.
         */
        função simimob_missing_bootstrap_notice ( ) {
 
            printf (
                '<div class="notice notice-error"><p>%s</p></div>' ,
                esc_html__ (
                    'O plugin Simulador Imobiliário foi instalado sem a pasta interna "simulador-imobiliario". Faça o download do arquivo ZIP oficial (simulador-imobiliario-v1.0.4.zip) e instale-o novamente.',
                    'O plugin Simulador Imobiliário foi instalado sem a pasta interna "simulador-imobiliario". Faça o download do arquivo ZIP oficial (simulador-imobiliario-v1.0.5.zip) e instale-o novamente.',
                    'simulador imobiliário'
                )
            );
        }
    }

    add_action ( 'administrador_avisos' , 'simimob_missing_bootstrap_notice' );
    add_action ( 'avisos_de_administração_de_rede' , 'simimob_missing_bootstrap_notice' );
}
