# Convert any multimedia scenarios described by a Petri network, into a web application exploiting HTML-CSS-JS
Original title : Transformation de scénarios multimédias modélisés avec des réseaux de Pétri, en application WEB exploitant la combinaison HTML-CSS-JS

## Documentation
Please see the <a href="https://github.com/droduit/petri-media/blob/master/doc.pdf">full PDF doc</a>

## Description
Les réseaux de Pétri permettent de modéliser un nombre infini d’applications très diverses. De manière basique, ce sont des schémas composés de 4 éléments principaux : Token, Places, Transitions et arcs : 
1.	Token -> Sprites : objets définis par des propriétés
2.	Places, activités, ou « scenes » : contenant un ou plusieurs « sprite »
3.	Transitions : gère les « events » pour passer d’une activité à une autre
4.	Arcs : reliant les places aux transitions.

Un logiciel de modélisation de réseaux de Pétri pour générer des fichiers en représentant une description au format JSON est déjà existant. Nous voulons ici utiliser le JSON généré, correspondant à la description d’applications WEB, et ainsi concevoir une 
prochaine étape, qui transformera le réseau de Pétri en une application WEB fonctionnelle, exploitant la combinaison HTML-CSS-JS. 

## Déploiement
1. Créer un dossier `uploads` avec les droits en écriture
2. Assurez-vous que le dossier `generated` possède les droits en écriture

## Demo
Le dossier `demo` contient des exemples de réseau de Petri au format JSON.
Le mot de passe de `ex1` est `demo`.