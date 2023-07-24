# Convert any multimedia scenarios described by a Petri network, into a web application

## Documentation
Please refer to the <a href="https://github.com/droduit/petri-media/blob/master/doc.pdf">complete PDF documentation</a>.

## Description
Petri networks allow modeling an infinite number of diverse applications. At a basic level, they consist of four main elements: Tokens, Places, Transitions, and Arcs:
1.	Token -> Sprites : objects defined by properties.
2.	Places, activities, or « scenes » : containers holding one or multiple sprites.
3.	Transitions : manage « events » to move from one activity to another.
4.	Arcs : connect places to transitions.

An existing software for Petri network modeling generates files representing descriptions in JSON format. Here, we aim to utilize the generated JSON, which corresponds to the description of web applications, to design the next step. This step will transform the Petri network into a functional web application, making use of HTML-CSS-JS combination.

## Déploiement
1. Create a folder named `uploads` with write permissions.
2. Ensure that the `generated` folder has write permissions.

## Demo
The `demo` folder contains examples of Petri networks in JSON format.
The password for `ex1` is `demo`.
