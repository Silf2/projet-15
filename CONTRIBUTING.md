# Contribution au projet

Si vous êtes ici, c'est que vous éprouvez de l'intérêt à développer et améliorer ce projet !
Voici les informations à connaître pour contribuer.

## Soumission de problème 

Si vous rencontrez un bug, veuillez suivre ces quelques étapes : 

- **Consultez les issues existantes :**  Il est possible que le problème que vous rencontrez ait déjà été signalé, alors assurez-vous que ça ne soit pas le cas.
- **Créez une nouvelle issue :** Si aucun signalement n'a déjà été fait, créez une nouvelle issue la plus détaillée possible. De quel type est le bug que vous rencontrez ? Comment le reproduire ? La version de Symfony sous laquelle vous étiez quand le bug est arrivé ? Le message d'erreur que vous avez eu ?
- **Ajoutez un titre clair :** Soyez clair dans le titre de votre issue pour que les autres utilisateurs puissent comprendre d'un simple coup d'oeil l'issue !

## Proposer de nouvelles fonctionnalités 

Si vous souhaitez améliorer le projet en proposant des fonctionnalités, ouvrez une issue de suggestion et fournissez une description détaillée de la fonctionnalité que vous proposez, telle que son objectif, ses cas d'utilisation, ou encore d'autres choses qui peuvent vous sembler importantes.

**Attendez d'avoir le feu vert avant de commencer à la développer vous-même !** Attendez le retour des gens avant de vous lancer dans le développement, car il est possible que la fonctionnalité proposée ne s'intègre pas bien dans notre projet et qu'elle soit malheureusement refusée.

## Contribuer au code

Les contributions au code sont bienvenues. Voici comment procéder : 

- **Clonez** le dépôt sur votre compte GitHub
- **Créez une nouvelle branche** à partir de `main` avec un nom clair et descriptif de ce que vous faites : 

```bash
git checkout -b Nom-de-la-fonctionnalité
```

- Faites vos **modifications** .
- Implémentez les **tests** de ces modifications.
- Réalisez des **commits** réguliers et explicites :

```bash
git commit -m "Ajout de ma nouvelle fonctionnalité"
```

- **Poussez** vos modifications sur votre dépôt :

```bash
git push origin Nom-de-la-fonctionnalité
```

- Soumettez une **pull-request** vers le dépôt principal. Faites en sorte de la décrire brièvement, et si possible de la lier à une issue existante.

## Contribuer aux tests

Assurer la qualité du code est essentiel, alors toute aide est la bienvenue !

- Ecrivez des tests unitaires pour toute fonctionnalité ou bugfix si ce n'est pas déjà fait ou si les tests existants ne sont pas qualitatifs.
- **Assurez-vous que les tests passent** avant de soumettre la pull-request ! Executez les avec : 

```bash
php bin/phpunit
```

