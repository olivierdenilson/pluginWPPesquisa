<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link
 * @since      1.0.0
 *
 * @package    Busca
 * @subpackage Busca/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Busca
 * @subpackage Busca/public
 * @author     Denilson Alves <denilson.oliveira@basis.com>
 *
 */


class Busca_Public {

	private $plugin_name;

	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

			add_shortcode('formulario_filtro_pesquisa', array($this,'custom_content_after_body_open_tag'));

	}

	// folha de stilo do plugin
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name.'-bootstrap', plugin_dir_url( __FILE__ ) . 'css/bootstrap.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-fontawesome', plugin_dir_url( __FILE__ ) . 'css/fontawesome.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/folha_pesquisa.css', array(), $this->version, 'all' );

	}

// JavaScript do plugin
	public function enqueue_scripts() {
		wp_enqueue_script( 'mark', plugin_dir_url( __FILE__ ) . 'js/jquery.mark.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/busca-public.min.js?v=2', array( 'jquery' ), $this->version, false );
	}


	function custom_content_after_body_open_tag()
	{
		if ( is_search() ) {
			global $wp_query;
			echo"<pre>";
			print_r($wp_query->request);
			echo"</pre>";
						
			
			$check0 = $check1 = $check2 = $check3 = $check4 = $check6 = $check6 = $radio0 = $radio1 = $radio2 = "";
			$selected = "selected";
			$checked = 'checked="checked"';





			switch ($_GET['ordering']) {

				case "relevance" :
					$check0 = $selected;
					break;

				case "DESC" :
					$check1 = $selected;
					break;

				case "ASC" :
					$check2 = $selected;
					break;

				case "popular" :
					$check3 = $selected;
					break;

				case "post_title" :
					$check4 = $selected;
					break;

				case "category" :
					$check5 = $selected;
					break;
					

				default :
					$check1 = $selected;
					break;
			}

			switch ($_GET['searchphrase']) {
				case "all" :
					$radio0 = $checked;
					break;

				case "any" :
					$radio1 = $checked;
					break;

				case "exact" :
					$radio2 = $checked;
					break;

				default :
					$radio0 = $checked;
					break;
			}

			function strip_param_from_url( $url, $param )
			{
			    $base_url = strtok($url, '?');
			    $parsed_url = parse_url($url);
			    $query = $parsed_url['query'];
			    parse_str( $query, $parameters );
			    unset( $parameters[$param] );
			    $new_query = http_build_query($parameters);
			    return $base_url.'?'.$new_query;
			}

  /** clases tipo de busca**/
			$active_all = $active_post = "";
			if (isset($_GET['post_type']) AND $_GET['post_type'] == 'post'){
					$active_post = 'active';
			}else{
					$active_all = 'active';
			}

      $current_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

			/** criar hrefs tipo de busca **/
				$current_url_post_type = strip_param_from_url($current_url, 'orderby');
				$current_url_post_type = strip_param_from_url($current_url_post_type, 'order');
				$current_url_post_type = strip_param_from_url($current_url_post_type, 'post_type');

				$href_all = $current_url_post_type.'&post_type=';
				$href_post = $current_url_post_type.'&post_type=post';

			/** criar hrefs recentes, relevância **/
				 $current_url_rr = strip_param_from_url($current_url, 'orderby');
				 $current_url_rr = strip_param_from_url($current_url_rr, 'order');

				 $post_type = ( isset($_GET['post_type']) && $_GET['post_type'] != 'all')? $_GET['post_type'] : '';
				 $counter = new WP_Query("s=".$_GET['s']."&searchphrase=all&ordering=desc&datainicial=".$_GET['datainicial']."&datafinal=".$_GET['datafinal']."&post_type=".$post_type);

				// toggle relevance
				$href_relevance = $href_relevance_arrow = "";

				if( strpos($current_url,'orderby=views') !== false &&strpos($current_url,'&order=asc') ){
					$href_relevance = $current_url_rr.'&orderby=views&order=desc';
					$href_relevance_arrow = '<i class="fa fa-long-arrow-down" aria-hidden="true"></i>';
				}else{
					$href_relevance = $current_url_rr.'&orderby=views&order=asc';
					if( strpos($current_url,'orderby=views') !== false &&strpos($current_url,'&order=desc') ){
							$href_relevance_arrow = '<i class="fa fa-long-arrow-up" aria-hidden="true"></i>';
					}

				}

				// toggle public_date
        $href_last_updated = $href_last_updated_arrow = "";
				if( strpos($current_url,'orderby=publish_date') !== false && strpos($current_url,'&order=asc') ){
					$href_last_updated = $current_url_rr.'&orderby=publish_date&order=desc';
					$href_last_updated_arrow = '<i class="fa fa-long-arrow-down" aria-hidden="true"></i>';
				}elseif(strpos($current_url,'orderby') == false && strpos($current_url,'order')==false){
          $href_last_updated = $current_url_rr.'&orderby=publish_date&order=asc';
          $href_last_updated_arrow = '<i class="fa fa-long-arrow-up" aria-hidden="true"></i>';
        }else{
					$href_last_updated = $current_url_rr.'&orderby=publish_date&order=asc';
					if( strpos($current_url,'orderby=publish_date') !== false &&strpos($current_url,'&order=desc') ){
							$href_last_updated_arrow = '<i class="fa fa-long-arrow-up" aria-hidden="true"></i>';
					}
				}

?>

<form method="GET" action="<?= esc_url(home_url('/'));?>" id="searcher">
      <h2>Busca Geral</h2>
        <div class="form-group col-md-6">
          <div class="form-group mx-sm-3 mb-2">
             <label for="searcher-field" class="sr-only">Palavra(s) ou frase para a pesquisa</label>
             <input type="text" name="s" value="<?php echo get_search_query();?>" size="10" id="searcher-field" class="form-control"
						  placeholder="Palavra(s) ou frase para a pesquisa">
          </div>
        </div>

      <div class=" col-md-6">
          <!--<input type="submit" value="Pesquisar" style="width: 50px;border-radius: 4px;">-->
         <button class="btn btn-primary mx-sm-3 mb-2" type="submit" name="submittype" value="Pesquisar" id="submit-field"> Pesquisar</button>

				 <?php if ($counter->found_posts > 0 AND isset($_GET['s']) AND $_GET['s'] != '' AND strlen($_GET['s']) > 2 ):?>
				 	 <button class="btn btn-primary mx-sm-3 mb-2"   type="submit" name="submittype" value="Limpar" id="limpar-field"> Limpar</button>
				 <?php endif;?>

      </div>
          <!-- Oculta button-->
      <div class="mais_opcoes col-md-12">
        <h5><i class="fa fa-angle-double-down" aria-hidden="true">
        </i> Mais opções de busca </h5>
      </div>
      <div class="ocultar_opcoes col-md-12">
        <h5>
          <i class="fa fa-angle-double-up" aria-hidden="true"></i>
          Ocultar opções de busca
        </h5>
      </div>

      <div class="op_mais_opcoes col-md-12">
        <!--button radio -->
        <div class="form-check col-md-12 disabled">
          <input class="form-check-input" type="radio" name="searchphrase" id="searchphraseall" value="all" <?php echo $radio0;?> checked>
          <label class="form-check-label" for="">Todas as Palavras (E)</label>
          <input class="form-check-input" type="radio" name="searchphrase" id="searchphraseany" value="any"<?php echo $radio1; ?> >
          <label class="form-check-label" for="">Qualquer palavra (OU)</label>
          <input class="form-check-input" type="radio" name="searchphrase" id="searchphraseexact" value="exact" <?php echo $radio2; ?>>
          <label class="form-check-label" for="">Frase exata</label>
        </div>

        <div class="col-md-12">
          <div class="form-group col-md-6">
            <label class="mr-sm-2" for="inlineFormCustomSelect">Ordenação</label>
            <select class="form-control" id="inlineFormCustomSelect" name="ordering">
							<option value="desc" <?php echo $_GET['ordering'] === 'desc' ? 'selected="selected"' : ''; ?>>Recentes primeiro</option>
							<option value="relevance" <?php echo $_GET['ordering'] === 'relevance' ? 'selected="selected"' : ''; ?>>Relevância</option>
              <option value="asc" <?php echo $_GET['ordering'] === 'asc' ? 'selected="selected"' : ''; ?>>Antigos primeiro</option>
              <option value="popular" <?php echo $_GET['ordering'] === 'popular' ? 'selected="selected"' : ''; ?>> Mais Populares </option>
              <option value="post_title" <?php echo $_GET['ordering'] === 'post_title' ? 'selected="selected"' : ''; ?>>Ordem Alfabética</option>
              <option value="category" <?php echo $_GET['ordering'] === 'category' ? 'selected="selected"' : ''; ?>>Categoria</option>
            </select>
          </div>
        </div>
          <div class="form-group col-md-6">
            <label for="">Data inicial:</label>
             <input class="form-control" type="date"name="datainicial" value="<?php echo $_GET['datainicial']; ?>">
          </div>
          <div class="form-group col-md-6">
            <label for="">Data inicial:</label>
             <input class="form-control" type="date" name="datafinal" value="<?php echo $_GET['datafinal']; ?>">
          </div>
        </div>
        </div></div>
      </form>
      <?php // Aqui é onde validamos as 3 letras ?>
      <?php if ($counter->found_posts > 0 AND isset($_GET['s']) AND $_GET['s'] != '' AND strlen($_GET['s']) > 2 ):?>
				<div class="row pescent">
          <div class="col-md-12" style="margin-bottom: 10px;">
          <div class="btn-group btn-group first-group lef_pes" role="group" style="float: left;">
              <a href="<?= $href_all;?>" type="button" class="btn btn-default btn-group <?php echo $active_all;?>" role="group"> Todos </a>
              <a href="<?= $href_post;?>"  type="button" class="btn btn-default btn-group <?php echo $active_post;?>" role="group"> Notícias </a>
			  <a href="<?= page;?>"  type="button" class="btn btn-default btn-group <?php echo $active_post;?>" role="group"> Paginas </a>
             <a type="button" class="btn btn-default btn-group" role="group" href="https://wwwh.cnj.jus.br/atos_normativos/" target="_blank"> Sistema </a>
          </div>
            <div class="btn-group btn-group second-group re_pes" role="group" style="float: right;">
              <a href="<?= $href_last_updated;?>" type="button" class="btn btn-default btn-group" href="javascript:void()" id="btn-recente" role="group"> Recentes: <?=$href_last_updated_arrow;?></a>
              <a href="<?= $href_relevance;?>"    type="button" class="btn btn-default btn-group" 		href="javascript:void()" id="btn-relevante" role="group"> Relevância: <?=$href_relevance_arrow;?></a> <br>
            </div>
					</div>
				</div>

    <?php endif;?>
</div>

<?php

}

}


}


