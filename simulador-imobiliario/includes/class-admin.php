 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/includes/class-admin.php
index 0000000000000000000000000000000000000000..0dd1c988cb0492c1516481d9208791f8b42caad4 100644
--- a//dev/null
+++ b/simulador-imobiliario/includes/class-admin.php
@@ -0,0 +1,418 @@
+<?php
+/**
+ * Admin UI for Simulador Imobiliário.
+ *
+ * @package Simulador_Imobiliario
+ */
+
+if ( ! defined( 'ABSPATH' ) ) {
+    exit;
+}
+
+class SIMIMOB_Admin {
+
+    /**
+     * Instance.
+     *
+     * @var SIMIMOB_Admin|null
+     */
+    protected static $instance = null;
+
+    /**
+     * Tabs.
+     *
+     * @var array
+     */
+    protected $tabs = array();
+
+    /**
+     * Return instance.
+     */
+    public static function get_instance() {
+        if ( null === self::$instance ) {
+            self::$instance = new self();
+        }
+
+        return self::$instance;
+    }
+
+    /**
+     * Constructor.
+     */
+    private function __construct() {
+        add_action( 'admin_menu', array( $this, 'register_menu' ) );
+        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
+
+        $this->tabs = array(
+            'geral'       => __( 'Geral', 'simulador-imobiliario' ),
+            'financiamento' => __( 'Financiamento', 'simulador-imobiliario' ),
+            'obras'       => __( 'Saldo de Obras', 'simulador-imobiliario' ),
+            'pdf'         => __( 'Relatórios & PDF', 'simulador-imobiliario' ),
+            'avancado'    => __( 'Avançado', 'simulador-imobiliario' ),
+        );
+    }
+
+    /**
+     * Register admin menu.
+     */
+    public function register_menu() {
+        add_menu_page(
+            __( 'Simulador Imobiliário', 'simulador-imobiliario' ),
+            __( 'Simulador Imobiliário', 'simulador-imobiliario' ),
+            'manage_options',
+            'simimob',
+            array( $this, 'render_page' ),
+            'dashicons-building',
+            56
+        );
+    }
+
+    /**
+     * Enqueue admin assets.
+     */
+    public function enqueue_assets( $hook ) {
+        if ( 'toplevel_page_simimob' !== $hook ) {
+            return;
+        }
+
+        wp_enqueue_style( 'simimob-admin', SIMIMOB_PLUGIN_URL . 'assets/css/admin.css', array(), '1.0.0' );
+        wp_enqueue_script( 'simimob-admin', SIMIMOB_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ), '1.0.0', true );
+        wp_localize_script(
+            'simimob-admin',
+            'SIMIMOB_ADMIN',
+            array(
+                'addRow'    => __( 'Adicionar linha', 'simulador-imobiliario' ),
+                'removeRow' => __( 'Remover', 'simulador-imobiliario' ),
+            )
+        );
+    }
+
+    /**
+     * Render admin page.
+     */
+    public function render_page() {
+        if ( ! current_user_can( 'manage_options' ) ) {
+            return;
+        }
+
+        $active_tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'geral'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
+        if ( ! isset( $this->tabs[ $active_tab ] ) ) {
+            $active_tab = 'geral';
+        }
+
+        $options = SIMIMOB_Settings::get_options();
+        ?>
+        <div class="wrap simimob-admin">
+            <h1><?php esc_html_e( 'Simulador Imobiliário — Financiamento & Obras', 'simulador-imobiliario' ); ?></h1>
+            <h2 class="nav-tab-wrapper">
+                <?php foreach ( $this->tabs as $tab => $label ) : ?>
+                    <?php $class = $active_tab === $tab ? 'nav-tab nav-tab-active' : 'nav-tab'; ?>
+                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=simimob&tab=' . $tab ) ); ?>" class="<?php echo esc_attr( $class ); ?>"><?php echo esc_html( $label ); ?></a>
+                <?php endforeach; ?>
+            </h2>
+            <form method="post" action="options.php">
+                <?php
+                settings_fields( 'simimob_settings_group' );
+                wp_nonce_field( 'simimob_save_settings', 'simimob_nonce' );
+
+                switch ( $active_tab ) {
+                    case 'financiamento':
+                        $this->render_tab_financiamento( $options );
+                        break;
+                    case 'obras':
+                        $this->render_tab_obras( $options );
+                        break;
+                    case 'pdf':
+                        $this->render_tab_pdf( $options );
+                        break;
+                    case 'avancado':
+                        $this->render_tab_avancado( $options );
+                        break;
+                    case 'geral':
+                    default:
+                        $this->render_tab_geral( $options );
+                        break;
+                }
+                submit_button();
+                ?>
+            </form>
+        </div>
+        <?php
+    }
+
+    /**
+     * Render Geral tab.
+     */
+    protected function render_tab_geral( $options ) {
+        $logo_id = isset( $options['logo_pdf'] ) ? attachment_url_to_postid( $options['logo_pdf'] ) : 0;
+        ?>
+        <table class="form-table">
+            <tr>
+                <th scope="row"><label for="primary_color"><?php esc_html_e( 'Cor primária', 'simulador-imobiliario' ); ?></label></th>
+                <td>
+                    <input type="text" id="primary_color" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[primary_color]" value="<?php echo esc_attr( $options['primary_color'] ); ?>" class="regular-text simimob-color-field" />
+                    <p class="description"><?php esc_html_e( 'Herdar cores do tema usando CSS vars. Deixe vazio para usar o padrão.', 'simulador-imobiliario' ); ?></p>
+                </td>
+            </tr>
+            <tr>
+                <th scope="row"><label for="typography"><?php esc_html_e( 'Tipografia', 'simulador-imobiliario' ); ?></label></th>
+                <td>
+                    <input type="text" id="typography" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[typography]" value="<?php echo esc_attr( $options['typography'] ); ?>" class="regular-text" />
+                    <p class="description"><?php esc_html_e( 'Defina uma pilha de fontes personalizada ou deixe vazio para herdar do tema.', 'simulador-imobiliario' ); ?></p>
+                </td>
+            </tr>
+            <tr>
+                <th scope="row"><?php esc_html_e( 'Logo padrão para PDFs', 'simulador-imobiliario' ); ?></th>
+                <td>
+                    <input type="url" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[logo_pdf]" value="<?php echo esc_attr( $options['logo_pdf'] ); ?>" class="regular-text" />
+                    <p class="description"><?php esc_html_e( 'Se vazio, utiliza o logotipo padrão do site.', 'simulador-imobiliario' ); ?></p>
+                </td>
+            </tr>
+            <tr>
+                <th scope="row"><label for="timezone"><?php esc_html_e( 'Fuso horário / Locale', 'simulador-imobiliario' ); ?></label></th>
+                <td>
+                    <input type="text" id="timezone" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[timezone]" value="<?php echo esc_attr( $options['timezone'] ); ?>" class="regular-text" />
+                    <p class="description"><?php esc_html_e( 'Ex.: America/Sao_Paulo', 'simulador-imobiliario' ); ?></p>
+                </td>
+            </tr>
+            <tr>
+                <th scope="row"><?php esc_html_e( 'Modelo de compartilhamento WhatsApp', 'simulador-imobiliario' ); ?></th>
+                <td>
+                    <textarea name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[share_whatsapp]" rows="4" class="large-text"><?php echo esc_textarea( $options['share_whatsapp'] ); ?></textarea>
+                    <p class="description"><?php esc_html_e( 'Use placeholders como {empreendimento}, {cliente}, {saldo}.', 'simulador-imobiliario' ); ?></p>
+                </td>
+            </tr>
+            <tr>
+                <th scope="row"><?php esc_html_e( 'Modelo de e-mail', 'simulador-imobiliario' ); ?></th>
+                <td>
+                    <input type="text" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[share_email_subject]" value="<?php echo esc_attr( $options['share_email_subject'] ); ?>" class="regular-text" />
+                    <textarea name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[share_email_body]" rows="4" class="large-text"><?php echo esc_textarea( $options['share_email_body'] ); ?></textarea>
+                </td>
+            </tr>
+        </table>
+        <?php
+    }
+
+    /**
+     * Render Financiamento tab.
+     */
+    protected function render_tab_financiamento( $options ) {
+        $modalidades = array(
+            'A' => __( 'Imóvel em obras — Associativo (repasse imediato)', 'simulador-imobiliario' ),
+            'B' => __( 'Imóvel em obras — Incorporação (financia apenas no Habite-se)', 'simulador-imobiliario' ),
+            'C' => __( 'Imóvel pronto novo — Associativo', 'simulador-imobiliario' ),
+            'D' => __( 'Imóvel pronto novo — Financiamento alienado à construtora', 'simulador-imobiliario' ),
+            'E' => __( 'Imóvel pronto retomada pela construtora', 'simulador-imobiliario' ),
+        );
+        ?>
+        <table class="form-table">
+            <tr>
+                <th scope="row"><?php esc_html_e( 'Modalidades ativas', 'simulador-imobiliario' ); ?></th>
+                <td>
+                    <?php foreach ( $modalidades as $key => $label ) : ?>
+                        <label>
+                            <input type="checkbox" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[modalidades][]" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array( $key, (array) $options['modalidades'], true ), true ); ?> />
+                            <?php echo esc_html( $label ); ?>
+                        </label><br />
+                    <?php endforeach; ?>
+                </td>
+            </tr>
+            <tr>
+                <th scope="row"><?php esc_html_e( 'Permitir pular etapa de capacidade de crédito', 'simulador-imobiliario' ); ?></th>
+                <td>
+                    <label>
+                        <input type="checkbox" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[permitir_pular_capacidade]" value="1" <?php checked( $options['permitir_pular_capacidade'], true ); ?> />
+                        <?php esc_html_e( 'Habilitar', 'simulador-imobiliario' ); ?>
+                    </label>
+                </td>
+            </tr>
+        </table>
+        <h3><?php esc_html_e( 'Parâmetros por modalidade', 'simulador-imobiliario' ); ?></h3>
+        <table class="widefat simimob-table">
+            <thead>
+                <tr>
+                    <th><?php esc_html_e( 'Modalidade', 'simulador-imobiliario' ); ?></th>
+                    <th><?php esc_html_e( 'Descrição', 'simulador-imobiliario' ); ?></th>
+                    <th><?php esc_html_e( 'LTV máx. (%)', 'simulador-imobiliario' ); ?></th>
+                    <th><?php esc_html_e( 'Renda comprometível (%)', 'simulador-imobiliario' ); ?></th>
+                    <th><?php esc_html_e( 'Taxa ref. (% a.a.)', 'simulador-imobiliario' ); ?></th>
+                    <th><?php esc_html_e( 'Taxa ref. (% a.m.)', 'simulador-imobiliario' ); ?></th>
+                    <th><?php esc_html_e( 'Prazo máx. (meses)', 'simulador-imobiliario' ); ?></th>
+                    <th><?php esc_html_e( 'Idade máx.', 'simulador-imobiliario' ); ?></th>
+                    <th><?php esc_html_e( 'Entrada mínima (%)', 'simulador-imobiliario' ); ?></th>
+                </tr>
+            </thead>
+            <tbody>
+                <?php foreach ( $modalidades as $key => $label ) :
+                    $param = isset( $options['modalidade_params'][ $key ] ) ? $options['modalidade_params'][ $key ] : array();
+                    ?>
+                    <tr>
+                        <td><strong><?php echo esc_html( $key ); ?></strong></td>
+                        <td><input type="text" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[modalidade_params][<?php echo esc_attr( $key ); ?>][descricao]" value="<?php echo esc_attr( isset( $param['descricao'] ) ? $param['descricao'] : '' ); ?>" /></td>
+                        <td><input type="number" step="0.01" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[modalidade_params][<?php echo esc_attr( $key ); ?>][ltv_max]" value="<?php echo esc_attr( isset( $param['ltv_max'] ) ? $param['ltv_max'] : '' ); ?>" /></td>
+                        <td><input type="number" step="0.01" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[modalidade_params][<?php echo esc_attr( $key ); ?>][renda_percent]" value="<?php echo esc_attr( isset( $param['renda_percent'] ) ? $param['renda_percent'] : '' ); ?>" /></td>
+                        <td><input type="number" step="0.01" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[modalidade_params][<?php echo esc_attr( $key ); ?>][taxa_aa]" value="<?php echo esc_attr( isset( $param['taxa_aa'] ) ? $param['taxa_aa'] : '' ); ?>" /></td>
+                        <td><input type="number" step="0.0001" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[modalidade_params][<?php echo esc_attr( $key ); ?>][taxa_am]" value="<?php echo esc_attr( isset( $param['taxa_am'] ) ? $param['taxa_am'] : '' ); ?>" /></td>
+                        <td><input type="number" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[modalidade_params][<?php echo esc_attr( $key ); ?>][prazo_max]" value="<?php echo esc_attr( isset( $param['prazo_max'] ) ? $param['prazo_max'] : '' ); ?>" /></td>
+                        <td><input type="number" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[modalidade_params][<?php echo esc_attr( $key ); ?>][idade_max]" value="<?php echo esc_attr( isset( $param['idade_max'] ) ? $param['idade_max'] : '' ); ?>" /></td>
+                        <td><input type="number" step="0.01" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[modalidade_params][<?php echo esc_attr( $key ); ?>][entrada_min]" value="<?php echo esc_attr( isset( $param['entrada_min'] ) ? $param['entrada_min'] : '' ); ?>" /></td>
+                    </tr>
+                <?php endforeach; ?>
+            </tbody>
+        </table>
+        <h3><?php esc_html_e( 'Coeficientes opcionais', 'simulador-imobiliario' ); ?></h3>
+        <table class="widefat simimob-repeatable" data-name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[coeficientes]">
+            <thead>
+                <tr>
+                    <th><?php esc_html_e( 'Faixa de renda', 'simulador-imobiliario' ); ?></th>
+                    <th><?php esc_html_e( 'Faixa de idade', 'simulador-imobiliario' ); ?></th>
+                    <th><?php esc_html_e( 'Coeficiente', 'simulador-imobiliario' ); ?></th>
+                    <th></th>
+                </tr>
+            </thead>
+            <tbody>
+                <?php
+                $coeficientes = ! empty( $options['coeficientes'] ) ? $options['coeficientes'] : array( array() );
+                foreach ( $coeficientes as $index => $coef ) :
+                    ?>
+                    <tr>
+                        <td><input type="text" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[coeficientes][<?php echo esc_attr( $index ); ?>][faixa]" value="<?php echo esc_attr( isset( $coef['faixa'] ) ? $coef['faixa'] : '' ); ?>" /></td>
+                        <td><input type="text" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[coeficientes][<?php echo esc_attr( $index ); ?>][idade]" value="<?php echo esc_attr( isset( $coef['idade'] ) ? $coef['idade'] : '' ); ?>" /></td>
+                        <td><input type="number" step="0.01" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[coeficientes][<?php echo esc_attr( $index ); ?>][coeficiente]" value="<?php echo esc_attr( isset( $coef['coeficiente'] ) ? $coef['coeficiente'] : '' ); ?>" /></td>
+                        <td><button type="button" class="button simimob-remove-row">&times;</button></td>
+                    </tr>
+                <?php endforeach; ?>
+            </tbody>
+        </table>
+        <button type="button" class="button button-secondary simimob-add-row" data-template="coeficientes"><?php esc_html_e( 'Adicionar coeficiente', 'simulador-imobiliario' ); ?></button>
+        <?php
+    }
+
+    /**
+     * Render Obras tab.
+     */
+    protected function render_tab_obras( $options ) {
+        ?>
+        <table class="form-table">
+            <tr>
+                <th scope="row"><label for="intermediaria_limite"><?php esc_html_e( 'Intermediária ≤ X vezes a mensal', 'simulador-imobiliario' ); ?></label></th>
+                <td>
+                    <input type="number" step="0.01" id="intermediaria_limite" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[intermediaria_limite]" value="<?php echo esc_attr( $options['intermediaria_limite'] ); ?>" />
+                </td>
+            </tr>
+            <tr>
+                <th scope="row"><?php esc_html_e( 'Entrada/Sinal mínima (%)', 'simulador-imobiliario' ); ?></th>
+                <td><input type="number" step="0.01" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[entrada_minima]" value="<?php echo esc_attr( $options['entrada_minima'] ); ?>" /></td>
+            </tr>
+            <tr>
+                <th scope="row"><?php esc_html_e( 'Entrada/Sinal máxima (%)', 'simulador-imobiliario' ); ?></th>
+                <td><input type="number" step="0.01" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[entrada_maxima]" value="<?php echo esc_attr( $options['entrada_maxima'] ); ?>" /></td>
+            </tr>
+            <tr>
+                <th scope="row"><?php esc_html_e( 'Mensal mínima (R$)', 'simulador-imobiliario' ); ?></th>
+                <td><input type="number" step="0.01" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[mensal_minima]" value="<?php echo esc_attr( $options['mensal_minima'] ); ?>" /></td>
+            </tr>
+            <tr>
+                <th scope="row"><?php esc_html_e( 'Mensal máxima (R$)', 'simulador-imobiliario' ); ?></th>
+                <td><input type="number" step="0.01" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[mensal_maxima]" value="<?php echo esc_attr( $options['mensal_maxima'] ); ?>" /></td>
+            </tr>
+        </table>
+        <h3><?php esc_html_e( 'Pró-soluto por empreendimento', 'simulador-imobiliario' ); ?></h3>
+        <table class="widefat simimob-repeatable" data-name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[empreendimentos]">
+            <thead>
+                <tr>
+                    <th><?php esc_html_e( 'Nome', 'simulador-imobiliario' ); ?></th>
+                    <th><?php esc_html_e( 'Limite pró-soluto (%)', 'simulador-imobiliario' ); ?></th>
+                    <th><?php esc_html_e( 'Meses de obra padrão', 'simulador-imobiliario' ); ?></th>
+                    <th><?php esc_html_e( 'Entrega padrão (AAAA-MM)', 'simulador-imobiliario' ); ?></th>
+                    <th><?php esc_html_e( 'Política própria', 'simulador-imobiliario' ); ?></th>
+                    <th></th>
+                </tr>
+            </thead>
+            <tbody>
+                <?php
+                $empreendimentos = ! empty( $options['empreendimentos'] ) ? $options['empreendimentos'] : array( array() );
+                foreach ( $empreendimentos as $index => $item ) :
+                    ?>
+                    <tr>
+                        <td><input type="text" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[empreendimentos][<?php echo esc_attr( $index ); ?>][nome]" value="<?php echo esc_attr( isset( $item['nome'] ) ? $item['nome'] : '' ); ?>" /></td>
+                        <td><input type="number" step="0.01" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[empreendimentos][<?php echo esc_attr( $index ); ?>][prosoluto]" value="<?php echo esc_attr( isset( $item['prosoluto'] ) ? $item['prosoluto'] : '' ); ?>" /></td>
+                        <td><input type="number" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[empreendimentos][<?php echo esc_attr( $index ); ?>][meses_obra]" value="<?php echo esc_attr( isset( $item['meses_obra'] ) ? $item['meses_obra'] : '' ); ?>" /></td>
+                        <td><input type="text" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[empreendimentos][<?php echo esc_attr( $index ); ?>][entrega_padrao]" value="<?php echo esc_attr( isset( $item['entrega_padrao'] ) ? $item['entrega_padrao'] : '' ); ?>" placeholder="2025-06" /></td>
+                        <td><textarea name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[empreendimentos][<?php echo esc_attr( $index ); ?>][politica]" rows="2" class="widefat"><?php echo esc_textarea( isset( $item['politica'] ) ? $item['politica'] : '' ); ?></textarea></td>
+                        <td><button type="button" class="button simimob-remove-row">&times;</button></td>
+                    </tr>
+                <?php endforeach; ?>
+            </tbody>
+        </table>
+        <button type="button" class="button button-secondary simimob-add-row" data-template="empreendimentos"><?php esc_html_e( 'Adicionar empreendimento', 'simulador-imobiliario' ); ?></button>
+        <?php
+    }
+
+    /**
+     * Render PDF tab.
+     */
+    protected function render_tab_pdf( $options ) {
+        ?>
+        <table class="form-table">
+            <tr>
+                <th scope="row"><?php esc_html_e( 'Ativar botão PDF', 'simulador-imobiliario' ); ?></th>
+                <td>
+                    <label>
+                        <input type="checkbox" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[pdf_ativo]" value="1" <?php checked( $options['pdf_ativo'], true ); ?> />
+                        <?php esc_html_e( 'Exibir botão de impressão/PDF', 'simulador-imobiliario' ); ?>
+                    </label>
+                </td>
+            </tr>
+            <tr>
+                <th scope="row"><?php esc_html_e( 'Cabeçalho do PDF', 'simulador-imobiliario' ); ?></th>
+                <td><textarea name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[pdf_header]" rows="4" class="large-text"><?php echo esc_textarea( $options['pdf_header'] ); ?></textarea></td>
+            </tr>
+            <tr>
+                <th scope="row"><?php esc_html_e( 'Rodapé do PDF', 'simulador-imobiliario' ); ?></th>
+                <td><textarea name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[pdf_footer]" rows="4" class="large-text"><?php echo esc_textarea( $options['pdf_footer'] ); ?></textarea></td>
+            </tr>
+            <tr>
+                <th scope="row"><label for="pdf_marca_dagua"><?php esc_html_e( 'Marca d’água (texto)', 'simulador-imobiliario' ); ?></label></th>
+                <td><input type="text" id="pdf_marca_dagua" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[pdf_marca_dagua]" value="<?php echo esc_attr( $options['pdf_marca_dagua'] ); ?>" class="regular-text" /></td>
+            </tr>
+        </table>
+        <?php
+    }
+
+    /**
+     * Render Avançado tab.
+     */
+    protected function render_tab_avancado( $options ) {
+        ?>
+        <table class="form-table">
+            <tr>
+                <th scope="row"><?php esc_html_e( 'Exportar configurações', 'simulador-imobiliario' ); ?></th>
+                <td>
+                    <textarea readonly rows="4" class="large-text" onclick="this.select();"><?php echo esc_textarea( wp_json_encode( $options ) ); ?></textarea>
+                    <p class="description"><?php esc_html_e( 'Copie e salve este JSON para importar em outro site.', 'simulador-imobiliario' ); ?></p>
+                </td>
+            </tr>
+            <tr>
+                <th scope="row"><?php esc_html_e( 'Importar configurações', 'simulador-imobiliario' ); ?></th>
+                <td>
+                    <textarea name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[export_string]" rows="4" class="large-text"></textarea>
+                    <p class="description"><?php esc_html_e( 'Cole o JSON exportado anteriormente e salve.', 'simulador-imobiliario' ); ?></p>
+                </td>
+            </tr>
+            <tr>
+                <th scope="row"><?php esc_html_e( 'Ativar REST API', 'simulador-imobiliario' ); ?></th>
+                <td><label><input type="checkbox" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[rest_enabled]" value="1" <?php checked( $options['rest_enabled'], true ); ?> /> <?php esc_html_e( 'Disponibilizar endpoints públicos', 'simulador-imobiliario' ); ?></label></td>
+            </tr>
+            <tr>
+                <th scope="row"><?php esc_html_e( 'Telemetria', 'simulador-imobiliario' ); ?></th>
+                <td><label><input type="checkbox" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[telemetria]" value="1" <?php checked( $options['telemetria'], true ); ?> /> <?php esc_html_e( 'Permitir envio de dados anônimos (desativado por padrão)', 'simulador-imobiliario' ); ?></label></td>
+            </tr>
+            <tr>
+                <th scope="row"><?php esc_html_e( 'Limpeza na desinstalação', 'simulador-imobiliario' ); ?></th>
+                <td><label><input type="checkbox" name="<?php echo esc_attr( SIMIMOB_Settings::OPTION_KEY ); ?>[limpeza]" value="1" <?php checked( $options['limpeza'], true ); ?> /> <?php esc_html_e( 'Apagar configurações ao remover o plugin', 'simulador-imobiliario' ); ?></label></td>
+            </tr>
+        </table>
+        <?php
+    }
+}
 
EOF
)
