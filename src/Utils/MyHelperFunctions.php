<?php
  namespace Drupal\citation\Utils;

  /**
   * Class MyHelperFunctions.
   */
  class MyHelperFunctions {

    /**
     * Get current multisite directory name.
     *
     * @return string
     *   The basename of the matching multisite directory.
     */
    public function getMultisiteAlias() {

      $site_path = \Drupal::service('site.path');
      $site      = explode('/', $site_path);

      return $site[1];
    }

    public static function splitName($full_name){
      /* separar el nombre completo en espacios */
      $tokens = explode(' ', trim($full_name));
      /* arreglo donde se guardan las "palabras" del nombre */
      $names = array();
      /* palabras de apellidos (y nombres) compuestos */
      $special_tokens = array('da', 'de', 'del', 'la', 'las', 'los', 'mac', 'mc', 'van', 'von', 'y', 'i', 'san', 'santa');
      $name = [];

      $prev = "";
      foreach($tokens as $token) {
          $_token = strtolower($token);
          if(in_array($_token, $special_tokens)) {
              $prev .= "$token ";
          } else {
              $names[] = $prev. $token;
              $prev = "";
          }
      }

      $num_nombres = count($names);
      $nombres = $apellidos = $apellido1 = "";

      switch ($num_nombres) {
          case 0:
              $nombres = '';
              $nombres_iniciales = '';
              break;
          case 1:
              $nombres = $names[0];
              $nombres_iniciales = strtoupper(substr($names[0],0,1)).'.';
              break;
          case 2:
              $nombres    = $names[0];
              $apellidos  = $names[1];
              $apellido1  = $names[1];
              break;
          case 3:
              $apellidos = $names[1] . ' ' . $names[2];
              $apellido1  = $names[1];
              $nombres   = $names[0];
              $nombres_iniciales = strtoupper(substr($names[0],0,1)).'.';
              break;
          case 4:
              $apellidos = $names[2] . ' ' . $names[3];
              $apellido1 = $names[2];
              $nombres   = $names[0] . ' ' . $names[1];
              $nombres_iniciales = strtoupper(substr($names[0],0,1).'. '.substr($names[1],0,1)).'.';
              break;
          case 5:
            $apellidos = $names[3] . ' ' . $names[4];
            $apellido1 = $names[3];
            $nombres   = $names[0] . ' ' . $names[1] . ' ' . $names[2];
            $nombres_iniciales = strtoupper(substr($names[0],0,1).'. '.substr($names[1],0,1).'. '.substr($names[2],0,1)).'.';
            break;
          default:
              $apellidos = $names[0] . ' '. $names[1];
              $apellido1 = $names[0];

              unset($names[0]);
              unset($names[1]);

              $nombres = implode(' ', $names);
              break;
      }

      $name['iniciales']  = $nombres_iniciales;
      $name['nombre']     = mb_convert_case($nombres, MB_CASE_TITLE, 'UTF-8');
      $name['apellidos']  = mb_convert_case($apellidos, MB_CASE_TITLE, 'UTF-8');
      $name['apellido1']  = $apellido1;


      return $name;
    }

  }
