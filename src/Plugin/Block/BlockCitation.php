<?php
namespace Drupal\citation\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;
use Drupal\node\NodeInterface;
use TheIconic\NameParser\Parser;
use Drupal\citation\Utils\MyHelperFunctions;


/**
 * Provides a 'DefaultBlock' block.
 *
 * @Block(
 *  id = "citation_block",
 *  admin_label = @Translation("Citation block"),
 * )
 */
class BlockCitation extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $node = \Drupal::routeMatch()->getParameter('node');
    $current_path = \Drupal::service('path.current')->getPath();
    $alias = \Drupal::service('path_alias.manager')->getAliasByPath($current_path);
    $types = NodeType::loadMultiple();
    $bundle = $node->bundle();
    $type_name = $types[$bundle]->label();
    $pid = $node->book['pid'];

    if (is_numeric($node)) {
      $node = Node::load($node);
    }

    if ($pid > 0 ){
      $pnode = Node::load($pid);
      $ptitulo = $pnode->get('title')->value;
      $autores = $this->get_autores($pnode->get('field_autoria')->value);
      $titulo  = $pnode->get('title')->value;
      $fecha   = $pnode->get('field_fecha_publicacion')->value;
    }else{
      $ptitulo = '';
      $autores = $this->get_autores($node->get('field_autoria')->value);
      $titulo  = $node->get('title')->value;
      $fecha   = $node->get('field_fecha_publicacion')->value;
    }

    if ($node instanceof NodeInterface) {
      //$data = addtoany_create_entity_data($node);
      $build = [
        '#url'                        => $alias,
        '#nodo'                       => $node->id(),
        '#autores'                    => $autores,
        '#titulo'                     => $titulo,
        '#theme'                      => 'citation_block',
        '#fecha'                      => $fecha,
        '#ptitulo'                    => $ptitulo,
        '#tipo'                       => $type_name,
        '#cache'                      => [
          'contexts' => ['url'],
        ],
        '#attached' => [
          'library' => [
            'citation/citation-libraries',
          ],
        ],
      ];
    }

    return $build;
  }


  private function get_autores($lista_autor){
    $autorSplit = [];
    $autors     = explode(",", $lista_autor);

    foreach ($autors as $key => $value) {
      $autor = MyHelperFunctions::splitName($autors[$key]);
      $autorTemp = $autor['apellido1'].', '.$autor['iniciales'];

      array_push($autorSplit, $autorTemp);
    }

    return $autorSplit;
  }

  public function get_apa($autores){

  }
}
