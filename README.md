# Création d'une API en PHP

## Objectifs

- Créer une API REST basique en PHP

Vous avez dans ce repo une base de données sqlite appelée 'users.sqlite' contenant une table 'users' avec les champs suivants :

- id (int) clé primaire
- first_name (text) Prénom
- last_name (text) Nom
- email (text) Email
- avatar (text) URL de l'avatar

Nous allons créer une API Rest pour gérer ces utilisateurs.

Est fourni ci-joint le squelette d'une api qui gère déjà le GET sur la ressource /api/users

## Exercices

1. Créer une collection dans PostMan pour tester nos requêtes sur l'API

- On voit que les méthodes GET sont déjà implémentées pour récupérer la liste des utilisateurs et un utilisateur en particulier
- La méthode POST pour la création d'un utilisateur est aussi déjà présente.

2. En s'inspirant du code déjà présent, implémenter la méthode DELETE pour supprimer un utilisateur

DELETE /api/users/{id} -> doit supprimer un utilisateur de la base de données

3. En s'inspirant du code déjà présent, implémenter la méthode PATCH pour mettre à jour un utilisateur. Rappel : PATCH est utilisé pour mettre à jour une partie des données d'une ressource.

PATCH /api/users/{id} -> doit mettre à jour un utilisateur dans la base de données et retourner un json contenant les informations de l'utilisateur mis à jour

- Pour faire cela, demandez vous comment vous allez gérer les paramètres de la requête PATCH. Vous pouvez vous inspirer de la méthode POST déjà implémentée.
- Également mettez en oeuvre un système de validation des données utilisateurs pour vérifier que les données envoyées sont correctes.

4. Facultatif: Créer une page web minimaliste qui affiche les données utilisateurs, ainsi qu'une page pour ajouter un utilisateur.

5. Créer un système d'authentification via une clé API obtenue au moment de la création d'un utilisateur. Cette clé API devra être envoyée dans le header de chaque requête pour pouvoir accéder aux ressources de l'API.
