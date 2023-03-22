TP PHP : Ajouter un système de commentaires sur les articles de Blog
A rendre pour le 5 avril 23:59. M’envoyer à contact@mflasquin.fr le dépôt git. Attention,
n’oubliez pas votre script SQL dans le dépôt.
1 – Créer une nouvelle table ‘commentaire’ avec les champs suivants :
• id du commentaires (integer, not null, auto_increment, primary_key)
• commentaire (text not null)
• idUser, c’est-à-dire l’utilisateur qui écrit le commentaire (int clé étrangère vers la table
user.id)
2 – Créer la classe Commentaire et le classe CommentaireRepository
3 – Ajouter un formulaire d’ajout de commentaire sur un article, ce formulaire ne s’affiche que si on
est connecté.
4 – Dans un article, afficher la liste des commentaires existant ainsi que leur auteur.
Bonus :
- Permettre à un utilisateur de modifier/supprimer ses propres commentaires
Idées de fonctionnalités :
- Ajouter une notion de rôle sur les utilisateurs (user/admin)
- Ajout d’une page pour accepter ou non des commentaires (quand on est admin)