<?php

namespace Drupal\adimeo_tools\Service;

use Drupal\Core\Entity\Entity;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Menu\MenuLinkInterface;
use Drupal\menu_link_content\Plugin\Menu\MenuLinkContent;

/**
 * Class LanguageService.
 *
 * Service allowing to get load and translate elements.
 *
 * @package Drupal\adimeo_tools\Service
 */
class LanguageService {

  /**
   * Service name.
   */
  const SERVICE_NAME = 'adimeo_tools.language';

  /**
   * Taxonomy entity id.
   */
  const TERM = 'taxonomy_term';

  /**
   * Node entity id.
   */
  const NODE = 'node';

  /**
   * Mode of translation: default if not exists.
   *
   * In this mode, the translations returns the entity in its default language
   * if no translation exists for defined languages.
   */
  const MODE_DEFAULT_LANGUAGE_IF_NO_TRANSLATION_EXISTS = 'DEFAULT_LANGUAGE_IF_NO_TRANSLATION_EXISTS';

  /**
   * Mode of translation: no entity if no languages exists.
   *
   * In this mode, the translations returns null if no translation
   * exists for defined languages.
   */
  const MODE_NO_ENTITY_IF_NO_TRANSLATION_EXISTS = 'NO_ENTITY_IF_NO_TRANSLATION_EXISTS';

  /**
   * Cache.
   *
   * @var array
   */
  protected static $cache = [];

  /**
   * Translate the entity if needed.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity to translate.
   * @param string $languageId
   *   The language in which the entity should be translated.
   * @param string $mode
   *   Mode of recovery of the translated entity.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   The translated entity.
   */
  static public function translate(EntityInterface $entity = NULL, $languageId = NULL, $mode = self::MODE_DEFAULT_LANGUAGE_IF_NO_TRANSLATION_EXISTS) {

    if ($entity) {
      $languageId = isset($languageId) ? $languageId : self::getCurrentLanguageId();

      // Création du cache de language.
      if (!array_key_exists($languageId, static::$cache)) {
        static::$cache[$languageId] = [];
      }

      // Création de l'id de cache.
      $cacheId = $entity->getEntityTypeId() . '_' . $entity->id() . '_' . $mode;

      // Si l'id de cache n'existe pas, c'est que l'entité n'a pas été loadée :
      if (!array_key_exists($cacheId, static::$cache[$languageId])) {
        $resultEntity = $entity;

        // Translate only if language is the not the default one.
        if ($entity->language()->getId() !== $languageId) {
          $resultEntity = \Drupal::service('entity.repository')
            ->getTranslationFromContext($entity, $languageId);
        }

        if ($mode == self::MODE_NO_ENTITY_IF_NO_TRANSLATION_EXISTS && $resultEntity->language()
          ->getId() != $languageId
        ) {
          $resultEntity = NULL;
        }

        static::$cache[$languageId][$cacheId] = $resultEntity;
      }

      return static::$cache[$languageId][$cacheId];
    }

    return NULL;
  }

  /**
   * Translate a list of entities if needed.
   *
   * @param array $entities
   *   The list of entities to translate.
   * @param string $languageId
   *   The language in which the entity should be translated.
   * @param string $mode
   *   Mode of recovery of the translated entity.
   *
   * @return array
   *   The list of translated entities
   */
  static public function translateMultiple(array $entities, $languageId = NULL, $mode = self::MODE_DEFAULT_LANGUAGE_IF_NO_TRANSLATION_EXISTS) {
    $languageId = isset($languageId) ? $languageId : self::getCurrentLanguageId();

    // Translate only if language is the not the default one.
    $translatedEntities = [];
    foreach ($entities as $key => $entity) {
      $translatedEntities[$key] = self::translate($entity, $languageId, $mode);
    }
    $translatedEntities = array_filter($translatedEntities);
    return $translatedEntities;
  }

  /**
   * Load an entity by id in a current|specific language.
   *
   * @param string $type
   *   The entity type to load.
   * @param string|int $id
   *   The entity to load.
   * @param string $languageId
   *   The language in which the entity should be translated.
   * @param string $mode
   *   Mode of recovery of the translated entity.
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   *   The translated entity
   */
  static public function load($type, $id, $languageId = NULL, $mode = self::MODE_DEFAULT_LANGUAGE_IF_NO_TRANSLATION_EXISTS) {
    $languageId = isset($languageId) ? $languageId : self::getCurrentLanguageId();

    // Translate only if language is the not the default one.
    if ($entityManager = static::getEntityManager($type)) {
      $entityToTranslate = $entityManager->load($id);
      return static::translate($entityToTranslate, $languageId, $mode);
    }

    return NULL;
  }

  /**
   * Load multiple entities in a current|specific language.
   *
   * @param string $type
   *   The entity type to load.
   * @param array $ids
   *   The list of ids to load.
   * @param string $languageId
   *   The language in which the entity should be translated.
   * @param string $mode
   *   Mode of recovery of the translated entity.
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   *   The list of translated entites.
   */
  static public function loadMultiple($type, array $ids, $languageId = NULL, $mode = self::MODE_DEFAULT_LANGUAGE_IF_NO_TRANSLATION_EXISTS) {
    $languageId = isset($languageId) ? $languageId : self::getCurrentLanguageId();

    // Translate only if language is the not the default one.
    if ($entityManager = static::getEntityManager($type)) {
      $entitiesToTranslate = $entityManager->loadMultiple($ids);
      return static::translateMultiple($entitiesToTranslate, $languageId, $mode);
    }

    return NULL;
  }

  /**
   * Return the entityManager.
   *
   * @param string $type
   *   The type of the entity manager.
   *
   * @return \Drupal\Core\Entity\EntityStorageInterface
   *   The entity storage interface.
   *
   * @throws \Exception
   */
  static protected function getEntityManager($type) {
    if ($entityManager = \Drupal::entityTypeManager()->getStorage($type)) {
      return $entityManager;
    }
    throw new \Exception('Unable to load entity type manager for \'' . $type . '\'');
  }

  /**
   * Return the current Language.
   *
   * @return string
   *   The current language id.
   */
  static public function getCurrentLanguageId() {
    return \Drupal::languageManager()
      ->getCurrentLanguage(LanguageInterface::TYPE_CONTENT)
      ->getId();
  }

  /**
   * Check if the current language is the default language.
   *
   * @return bool
   *   Whether the current language is the default language or not.
   */
  static public function currentLanguageIsDefault() {
    return self::languageIdIsDefault(self::getCurrentLanguageId());
  }

  /**
   * Check if the passed language id is the default language.
   *
   * @param string $languageId
   *   The language id to check.
   *
   * @return bool
   *   Whether the current language is the default language or not.
   */
  static public function languageIdIsDefault($languageId) {
    return $languageId == \Drupal::languageManager()
      ->getDefaultLanguage()
      ->getId();
  }

  /**
   * Filter menus items by current language.
   *
   * @param array $items
   *   Menu items.
   */
  static public function filterMenusByCurrentLanguage(array &$items) {
    $language = \Drupal::languageManager()
      ->getCurrentLanguage(LanguageInterface::TYPE_CONTENT)
      ->getId();
    foreach ($items as $key => $item) {
      if (!$items[$key] = self::checkForMenuItemTranslation($item, $language)) {
        unset($items[$key]);
      }
    }
  }

  /**
   * Filter admin menus items by current language.
   *
   * @param array $form
   *   Original form.
   *
   * @see admin/structure/menu/manage/main
   */
  static public function filterFormMenusByCurrentLanguage(array &$form) {
    $language = \Drupal::languageManager()
      ->getCurrentLanguage(LanguageInterface::TYPE_CONTENT)
      ->getId();
    foreach ($form['links']['links'] as $key => $link) {
      if (preg_match('/^menu_plugin_id:menu_link_content:(.*)$/', $key, $matches)) {
        $menuLinkContent = \Drupal::service('entity.repository')
          ->loadEntityByUuid('menu_link_content', $matches[1]);
        $languages = $menuLinkContent->getTranslationLanguages();
        if (!array_key_exists($language, $languages)) {
          unset($form['links']['links'][$key]);
        }
      }
    }
  }

  /**
   * Private function.
   *
   * @param array $item
   *   Item.
   * @param string $language
   *   Language.
   *
   * @return bool|null
   *   Result.
   */
  static private function checkForMenuItemTranslation(array $item, $language) {
    $menuLinkEntity = self::loadLinkEntityByLink($item['original_link']);

    if ($menuLinkEntity != NULL) {
      $languages = $menuLinkEntity->getTranslationLanguages();
      if (!array_key_exists($language, $languages)) {
        return FALSE;
      }
      if (count($item['below']) > 0) {
        foreach ($item['below'] as $subkey => $subitem) {
          if (!$item['below'][$subkey] = self::checkForMenuItemTranslation($subitem, $language)) {
            unset($item['below'][$subkey]);
          }
        }
      }
      return $item;
    }

    return NULL;
  }

  /**
   * Load entity link.
   *
   * @param \Drupal\Core\Menu\MenuLinkInterface $menuLinkContentPlugin
   *   MenuLinkPlugin.
   *
   * @return null|Entity
   *   Result.
   */
  static private function loadLinkEntityByLink(MenuLinkInterface $menuLinkContentPlugin) {
    if ($menuLinkContentPlugin instanceof MenuLinkContent) {
      $menu_link = explode(':', $menuLinkContentPlugin->getPluginId(), 2);
      $uuid = $menu_link[1];
      $entity = \Drupal::service('entity.repository')
        ->loadEntityByUuid('menu_link_content', $uuid);
      return $entity;
    }
    return NULL;
  }

}
