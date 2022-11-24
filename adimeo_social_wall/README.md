# Adimeo Social Wall

Contact développeurs

 - Nicolas Fabing (nfabing@adimeo.com)

## Mise en place

Ce module étend le module de contrib drupal/social_wall en y ajoutant des options supplémentaires.

Ce module ajoute une intégration avec les réseaux sociaux suivants :

 - **Youtube** : Permet d'afficher les dernières vidéos d'une chaine Youtube.
 - **Twitter with account infos** : Reprend le fonctionnement du plugin Twitter déjà présent et y ajoute des informations sur le compte en plus du dernier Tweet.

## Fonctionnement

Chaque réseau social est un plugin du type SocialNetwork.

Pour chaque nouveau réseau social, il faut créer le plugin, déclarer le thème et préparer le template Twig.


## Pour plus tard... (Possible évols)

 - Ajout de Linkedin
