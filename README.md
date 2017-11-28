# Transformation de scénarios multimédias modélisés avec des réseaux de Pétri, en application WEB exploitant la combinaison HTML-CSS-JS

## Description
Les réseaux de Pétri permettent de modéliser un nombre infini d’applications très diverses. De manière basique, ce sont des schémas composés de 4 éléments principaux : Token, Places, Transitions et arcs : 
1.	Token -> Sprites : objets définis par des propriétés
2.	Places, activités, ou « scenes » : contenant un ou plusieurs « sprite »
3.	Transitions : gère les « events » pour passer d’une activité à une autre
4.	Arcs : reliant les places aux transitions.
Un logiciel de modélisation de réseaux de Pétri pour générer des fichiers en représentant une description au format JSON est déjà existant. Nous voulons ici utiliser le JSON généré, correspondant à la description d’applications WEB, et ainsi concevoir une prochaine étape, qui transformera le réseau de Pétri en une application WEB fonctionnelle, exploitant la combinaison HTML-CSS-JS. 


## Transitions

<p>Une transition représente une action déclenchée par un élément HTML, soit par lui même, par exemple la fin d'une video, ou une interaction de l'utilisateur avec cet élément. </p>

<p>Chaque transition contient un identifiant unique, une association In, et une association Out. Toutes les transitions d'une scène peuvent être groupées à l'intérieur d'un objet dans le tableau des transitions, sous forme de plusieurs règles.</p>

<p>Les associations In sont les prérequis; les conditions à remplir sur un sprite pour qu'une transition soit déclanchée. Elles décrivent la source et l'association out la destination.</p>

<p>Les associations Out sont liées aux associations In et décrivent l'aboutissement lors du déclenchement d'une transition décrite par l'association In. Concrètement, l'association out contient la ou les scenes de destination lorsque l'association In liée est déclenchée.</p>

Chaque association In / Out contient un identifiant, et une ou plusieurs règles

Ci-dessous, vous trouverez de plus amples informations concernant la signification des champs de l'association In et de l'association Out.

<li><code>"id"</code> : Identifiant unique de la transition.</li>

### Associations In

<p>
  <ul>
    <li><code>"id"</code> : Identifiant unique de l'association In.</li>
    <li><code>"regles"</code> : une règle s'applique sur un ou plusieurs <b>sprites</b> (éléments HTML) d'une ou plusieurs scenes.
      <ul>
      <li><code>"id"</code> : Doit être le même pour l'associationOut lié à l'associationIn.</li>
      <li><code>"sprites"</code> : liste contenant les sprites déclencheurs de la transition et l'action qui va appeler l'assocaition out.<br>
        <b>Exemple :</b> <code>"sprites":["1.endDuration", "2.click"]</code></li>
      <li><code>"scenes"</code> : décrit la ou les scenes sources contenant les éléments déclencheurs de la transition out.</li>
      </ul>
  </li>
  </ul>
</p>



### Associations Out
