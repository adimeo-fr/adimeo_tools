<?php

namespace Drupal\site_structure_export\Manager;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityFieldManager;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\site_structure_export\Gateway\SiteStructureExportGateway;

class SiteStructureExportManager {

  /** @var \Drupal\site_structure_export\Gateway\SiteStructureExportGateway  */
  protected $gateway;

  /**
   * The entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * Configuration Factory.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;


  /**
   * @param \Drupal\site_structure_export\Gateway\SiteStructureExportGateway $gateway
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   */
  public function __construct(SiteStructureExportGateway $gateway, EntityFieldManagerInterface $entityFieldManager,ConfigFactoryInterface $configFactory) {
    $this->gateway = $gateway;
    $this->entityFieldManager = $entityFieldManager;
    $this->configFactory = $configFactory;
  }

  public function getAllEntityTypeStructure() {
    $entityTypes = $this->gateway->getAllEntityTypeBundles();
    foreach($entityTypes as $entityType => $bundles) {
      foreach ($bundles as $bundle => $data) {
        $fields = $this->entityFieldManager->getFieldDefinitions($entityType, $bundle);
        foreach ($fields as $field_name => $field_definition) {
          $targetBundle = $field_definition->getTargetBundle();
          if (!empty($field_definition->getTargetBundle())) {
            $listFields[$field_name]['type'] = $field_definition->getType();
            $listFields[$field_name]['label'] = $field_definition->getLabel();
            $structure[$entityType][$bundle][$field_name]['type'] = $field_definition->getType();
            $structure[$entityType][$bundle][$field_name]['label'] = $field_definition->getLabel();
          }
        }
      }
    }
    $test = 1;
    return $structure;
  }


  public function exportToXsl() {
    // filename for download
    $filename = $this->configFactory->get('system.site')->get('name')."_structure.xls";

    header("Content-Disposition: attachment; filename=".$filename);
    header("Content-Type: application/vnd.ms-excel");

    $data = $this->getAllEntityTypeStructure();
    $flag = false;
    $header = ['ENTITY TYPE', 'BUNDLE', 'FIELD', 'FIELD TYPE', 'FIELD LABEL'];
    echo implode("\t",$header) ."\r\n";
    foreach($data as $type => $bundle) {
      array_walk_recursive($bundle, [$this ,'cleanData']);
      foreach ($bundle as $bundleName => $field) {
        foreach ($field as $fieldName => $data) {
          echo $type. "\t".$bundleName. "\t" .$fieldName. "\t" . $data['type'] ."\t". $data['label'] .  "\r\n";
        }
      }
    }
    exit;
  }

  public function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  public function exportToWord() {
    // filename for download
    $siteName = $this->configFactory->get('system.site')->get('name');
    $filename = $siteName."_structure.doc";


    header("Content-Type: application/vnd.msword");
    header("content-disposition: attachment;filename=" . $filename);

    $data = $this->getAllEntityTypeStructure();

    echo "<html>";
    echo "<body>";
    echo "<h1 style='color: #dc4453; margin-bottom: 50px'>Structure du site '".$siteName."'</h1>";

    foreach($data as $type => $bundle) {
      echo "<div><h2 style='color: #0d7ab8'>Entity type " .$type.":</h2>";
      foreach ($bundle as $bundleName => $field) {
        echo "<div><h3 style='color: #00836d'>Bundle ".$bundleName.":</h3>";
        foreach ($field as $fieldName => $data) {
          echo "<p><strong>Champ ".$fieldName.": </strong></p>";
          foreach ($data as $k => $v) {
            echo "<p>".$k.": ".$v." </p>";
          }
        }
        echo "</div>";
      }
      echo "</div>";
    }
    echo "</body>";
    echo "</html>";

    exit;
  }

}
