# Transformation de scénarios multimédias modélisés avec des réseaux de Pétri, en application WEB exploitant la combinaison HTML-CSS-JS

## Description
Les réseaux de Pétri permettent de modéliser un nombre infini d’applications très diverses. De manière basique, ce sont des schémas composés de 4 éléments principaux : Token, Places, Transitions et arcs : 
1.	Token -> Sprites : objets définis par des propriétés
2.	Places, activités, ou « scenes » : contenant un ou plusieurs « sprite »
3.	Transitions : gère les « events » pour passer d’une activité à une autre
4.	Arcs : reliant les places aux transitions.

Un logiciel de modélisation de réseaux de Pétri pour générer des fichiers en représentant une description au format JSON est déjà existant. Nous voulons ici utiliser le JSON généré, correspondant à la description d’applications WEB, et ainsi concevoir une 
prochaine étape, qui transformera le réseau de Pétri en une application WEB fonctionnelle, exploitant la combinaison HTML-CSS-JS. 

PHP n'est pas utilisé dans le résultat généré. La navigation se fait en javascript et seul des pages HTML/CSS sont générées. 
Le moteur de génération des page et de parsage des fichiers JSON cependant est écrit intégralement en PHP et un semblant de javascript/Jquery est utilisé pour produire une interface utilisateur conviviale.

### Vues, Scenes et Sprites
<h6>Vues</h6>
Au niveau HTML, une vue ne contient que des iframes avec les attributs "src", "id" et "style". Les iframes contiennent chacune une scene. La vue offre donc une flexibilité assez grande. On peut réutiliser plusieurs fois une même scène dans différentes vues, disposées de manière différentes. La vue est l'un des deux éléments disponibles dans la navigation du site, le deuxième étant la scène. Des pages sont crées pour les vues et pour les scènes séparément. Donc si une scène n'est jamais utilisée dans une vue, elle est tout de même générée dans une page qui lui est dédiée. A l'intérieur d'une vue, les frames peuvent charger de nouveaux contenus dans d'autres frames ou à l'intérieur de leur propre frame, ou encore charger une nouvelle vue ou une simple scène.

<h6>Scènes</h6>
La scène peut contenir tout types d'éléments HTML. Elle pourrait au même titre que la vue contenir des iframes également si l'utilisateur le souhaite, par exemple pour afficher le contenu d'une page externe sur internet ou encore une video youtube (qui peut aussi plus simplement être intégrée par un élément "video" en spécifiant l'attribut "src"). Les éléments HTML intégrés dans une scène sont des Sprites.

<h6>Sprites</h6>
Les sprites représentent des éléments HTML natifs. Vous avez donc à loisir de leur attribuer tous les attributs disponibles en HTML ainsi qu'une valeur affichée, par exemple le texte d'un paragraphe, ou la valeur par défaut d'un champ de formulaire.  

## Scenes
<pre>
"scenes":[
      {
         "id":"page1",
		 "title":"Ma scene 1",
		 "mobile": true,
		 "lang":"fr",
		 "style": {
			"background": "#f8f8f8",
			"color":"brown",
			"font-size":"15px"
		  },
		  "css":{
			".contact-box": { "color":"#020015", "border":"1px solid #ddd", "background":"linear-gradient(to bottom,  rgba(222,239,255,1) 0%,rgba(152,190,222,1) 100%)", "border-radius":"8px", "padding":"6px", "margin":"20px auto" },
			".contact-box:hover": { "cursor":"pointer", "background":"linear-gradient(to top,  rgba(222,239,255,1) 0%,rgba(152,190,222,1) 100%)" },
			".float_l": { "float":"left", "padding-right":"8px" },
			"img": { "border-radius":"8px", "transition":"all 0.2s linear", "text-align":"center" },
			"img:hover": { "opacity": 0.8 }
		 },
		 "container":1,
		 "position": {
		 	"bottom": 0,
		 	"width": "100%",
		 	"height": "50%"
		 },
         "sprites":[
            {
               "id": 1,
               "nature":"video",
               "attr":{
					"src":"https://www.youtube.com/embed/Vpg9yizPP_g",
					"width":560,
					"height":315
			   },
			   "style":{
					"border":"3px solid black"
				},
				"eventProperties":{
					"endDuration": { "timer":2 }
 				}
            },
			{
				"id":2,
				"nature":"img",
				"attr":{
					"src":"yunna.jpg",
					"height":320
			   }
			},
			{
				"id": 3, "nature":"div",
				"attr":{"class":"contact-box float_l clear"}, "childs": [4, 5]
			},
			{
				"id":4, "nature":"div",
				"attr":{"class":"float_l"}, "style":{ "margin-right":"20px"},
				"childs": [6]
			},
			{
				"id":5, "nature":"div", "attr":{"class":"float_l"},
				"childs": [7, 8]
			},
			{
				"id":6, "nature":"img", "style":{"margin-right":"10px"}, "attr":{"src":"https://scontent-frx5-1.xx.fbcdn.net/v/t1.0-9/22045584_10214332095521962_2816500085493263649_n.jpg?oh=43eef7581d707362c13306aeac10e41a&oe=5A7A2AA9", "width":115}
			},
			{"id":7, "nature":"h2", "value":"Dominique Roduit"},
			{"id":8, "nature":"h5", "value":"EPFL"},
			{"id":9, "clone":3, "style":{"border":"2px solid black"}, "count":2 }
         ]
      }
</pre>


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
        <b>Exemple :</b> <code>"sprites":["1.endDuration", "2.click"]</code><br>Ici, la fin de video du sprite avec l'identifiant 1 va déclencher la transition out, et la meme transition out sera egalement déclenchée par le clique sur le sprite avec l'identifiant 2.</li>
      <li><code>"scenes"</code> : décrit la ou les scenes sources contenant les éléments déclencheurs de la transition out.</li>
      </ul>
  </li>
  </ul>
</p>



### Associations Out
<pre>"associationOut":{
    "id":"identifiant unique de l'association out",
    "regles":[ 
       {
          "id":"meme identifiant que l'association in liée",
          "scenes":["page2"] // La ou les destinations
       }
    ]
 }
</pre>


## Infos développeur
Un mode debug existe. Il suffit de passer la variable <code>$debug_mode = true;</code> sur la page <code>index.php</code> et les outils de debuggage seront disponibles sur toutes les pages générées. La génération en mode normal appel les fichiers PHP de manière asynchrone de manière à afficher une interface user-friendly munie d'un loader. Le mode debug ne génère pas d'appel asynchrone en javascript, les pages PHP sont inclues directement. De cette manière tous les messages d'erreurs eventuels peuvent être affichés. Le mode debug peut être conservé même lorsque le logiciel est prêt à être publié. Il suffit alors de passer la variable $debug_mode à <code>false</code> et aucune trace du mode debug ne sera visible.




