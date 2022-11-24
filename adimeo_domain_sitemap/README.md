# Adimeo Domain Sitemap

Contact développeurs

 - Nicolas Fabing (nfabing@adimeo.com)

## Mise en place

Ce module propose un plugin URL generator personnalisé qui prend en compte le domaine actif lors de la récupération des URLS.
Pour l'utiliser, il faut simplement l'ajouter dans un "sitemap type" depuis le back-office.

Depuis la page "/admin/config/search/simplesitemap/types" créer ou modifier un type de sitemap.
Puis, dans le champ "URL generator", ajouter le "Entity Domain URL generator".

Après avoir enregistré le type de sitemap, il faudra régénérer les sitemaps.

**Note** : Ce générateur remplace le "Entity URL generator", il faut donc penser à le retirer de la liste.


## Fonctionnement

Ce module ajoute une nouvelle instance de plugin de type UrlGenerator.
Cette instance ajoute une condition sur le domaine actif. Ceci permet de récupérer uniquement les entités accessibles sur le domaine actif.

Afin d'altérer l'affichage selon le domaine actif, on change le controller par défaut pour la route permettant de consulter le sitemap. (voir EntityDomainUrlGenerator.php)
Ce controller custom régénère le sitemap si l'utilisateur se trouve sur un autre domaine que celui actuellement mit en cache.


## Pour plus tard... (Possible évols)

 - Enregistrer les sitemaps de tous les domaines en même temps au lieu d'en régénérer un à chaque changement de domaine.
