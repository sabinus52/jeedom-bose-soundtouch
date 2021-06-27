![capture](../images/icon-48.png) **Documentation du plugin Bose SoundTouch**

# Description 

Ce plugin permet de contrôler les enceintes Bose SoundTouch.

On peut dorénavant choisir entre 2 styles de widget.

Rendu visuel du widget *Télécommande* / *Player* :

![capture](../images/remote.png) ![capture](../images/player.png)

**Accès aux infos** :
- Source sélectionnée, Etat de l'enceinte
- Volume, son coupé
- Etat de lecture, pause
- Preview, artiste, titre
- Shuffle, repéter tous ou un seul
- Zone MulitRoom (master|slave|none)

**Actions possibles** :
- Allumage, arrêt de l'enceinte
- Volume : ajustement en pourcentage, mute, up, down
- Choix des présélections
- Play, pause, stop, piste suivante et précédente
- Activation du shuffle et repeat
- Selection des sources
- Ajouter ou enlever une enceinte d'une zone MultiRoom

***Remarque*** : TV ne fonctionne seulement si une TV est connectée en sortie de l'enceinte en HDMI. AUX ne fonctionne que sur certaines enceintes et est maintenant déprécié par Bose.



# Configuration du plugin

Après téléchargement du plugin, il vous suffit juste d’activer celui-ci, il n’y a aucune configuration à ce niveau.


# Configuration des équipements

Pour se faire ajouter et paraméter une enceinte, cliquer sur *Plugins / Multimédia / Bose SoundTouch*

Puis cliquer sur l'icône **Ajouter** et définir :

- Nom de l'équipement
- Objet parent
- Cocher *Activer* pour que l'équipement soit utilisable
- Cocher *Visible* pour le rendre visible sur le dashboard
- Nom d'hôte ou l'adresse IP de l'enceinte
- Format du widget

Pour terminer, cliquer sur **Sauvegarder** et l'enceinte est prête à être contrôler


# MultiRoom

> Pour profiter du MultiRoom, Il sera nécessaire de cliquer sur le bouton `Recréer les commandes manquantes` pour prendre en compte la création des commandes de la gestion du MultiRoom.

Des nouvelles commandes `Ajout zone xxxxx` et `Suppression zone xxxxx` permettent de les utiliser dans vos scénarios pour gérer votre MultiRoom

La commande info `MultiRoom` indique l'état du MultiRoom pour chaque enceinte avec las valeur suivates :
- `master` : c'est l'enceinte qui est maître de la zone MultiRoom.
- `slave` : cette enceinte est en mode esclave dans une zone MultiRoom.
- `none` : aucune zone MultiRoom active.


# Liens utiles

- Topic sur le forum Jeedom Community : [https://community.jeedom.com/t/plugin-bose-soundtouch-discussion-generale/55311](https://community.jeedom.com/t/plugin-bose-soundtouch-discussion-generale/55311)
- Notes de version : [https://sabinus52.github.io/jeedom-bose-soundtouch/fr_FR/changelog](https://sabinus52.github.io/jeedom-bose-soundtouch/fr_FR/changelog)
- Dépôt Github : [https://github.com/sabinus52/jeedom-bose-soundtouch](https://github.com/sabinus52/jeedom-bose-soundtouch)

