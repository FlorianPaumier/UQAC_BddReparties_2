#Installation
Lancer le docker pour la bdd

Copier .env vers .env.local

lancer la commande bin/console doctrine:database:create

lancer la commande bin/console doctrine:schema:update

lancer la commande bin/console doctrine:migrations:migrate
