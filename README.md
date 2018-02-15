# Oizo

## Présentation :

Le site permet la création de comptes d'utilisateurs permettant de poster des messages publics.
L'utilisateur connecté peut choisir un pseudonyme, une photo de profil et une description, et poster des messages publics.
Il peut également s'abonner à d'autres personnes, il verra alors leurs messages dans son flux d'actualité, ainsi que les messages dont il est mentionné par un @.
Une barre de recherche permet de chercher des utilisateurs, on pourra alors voir son profil, ses followers et ses derniers messages.
Une personne non-connecté voit l'ensemble des messages, mais ne peut en poster. Elle se verra proposer de se créer un compte.

## Caractéristiques techniques

- Langages utilisés : PHP - JavaScript - HTML - CSS.
- Création de la base de données en PostgreSQL.
- Gestion de comptes d'utilisateurs.

### Web Services disponibles en JSON :
  - uploadAvatar : permet de redimenstionner et de saugarder une image de profil (48px et 256px).
  - setProfile : update le profil.
  - postMessage : rajoute le message dans la base de données.
  - logout : s'indentifier.
  - login : se déconnecter.
  - getAvatar : renvoi l'image de profil de l'utilisateur (2 tailles disponibles).
  - unfollow : enlever un utilisateur à la liste des follows.
  - follow : rajouter un utilisateur à la liste des follows.
  - findUsers : recherche un utilisateur.
  - findMessages : messages que peut voir l'utilisateur connecté.
  - createUser : enregistre un nouvel utilisateur.
  
  
  
Projet réalisé dans le cadre de la Licence Informatique de Lille1 (FIL) - 2017.
