# CAHIER DES CHARGES FONCTIONNEL ET TECHNIQUE — VERSION 1.6

## Plateforme intégrée de gestion des projets, chantiers, ressources et activités BTP

### Site vitrine institutionnel + application web de gestion strictement mono-entreprise

**Version :** 1.6  
**Statut :** Document de référence pour conception, développement, recette et déploiement  
**Périmètre :** une seule entreprise, sans agences ni filiales  
**Domaines :** Bâtiment, Génie civil, Travaux publics, VRD, Aménagement, Lotissement, Énergie, activités connexes  
**Extension agro-industrie :** hors MVP, module futur optionnel  
**Stack cible :** Laravel 13+, Livewire 4, Blade, Tailwind CSS, Flux UI, PostgreSQL, Redis, Spatie Laravel Permission  
**Architecture :** application web monolithique, modulaire, sécurisée, strictement mono-entreprise

---

# TABLE DES MATIÈRES

1. Objet et contexte  
2. Vision et objectifs  
3. Principes directeurs  
4. Périmètre et hors périmètre  
5. Utilisateurs et responsabilités  
6. Organisation interne de l’entreprise  
7. Architecture fonctionnelle globale  
8. Site vitrine institutionnel  
9. CMS et publication  
10. CRM et demandes commerciales  
11. Authentification et gestion des comptes  
12. RBAC et autorisations  
13. Projets  
14. Templates de phases  
15. Lots de travaux  
16. Tâches, dépendances et jalons  
17. Planning et Gantt  
18. Suivi de l’avancement  
19. Chantiers  
20. Journal et rapports de chantier  
21. Pointage et ressources humaines opérationnelles  
22. Matériels et engins  
23. Matériaux et stocks  
24. Achats  
25. Budget et contrôle des coûts  
26. Dépenses et engagements  
27. Factures et paiements  
28. Situations de travaux  
29. Décomptes  
30. Avenants  
31. QHSE  
32. Réception et réserves  
33. GED  
34. Photos et médias  
35. Foncier et lotissements  
36. Portail client / maître d’ouvrage  
37. Notifications  
38. Recherche globale  
39. Reporting et KPI  
40. Audit et traçabilité  
41. Mode terrain et connectivité intermittente  
42. Architecture technique  
43. Architecture Livewire SFC/MFC  
44. Actions et services métier  
45. Modèle PostgreSQL  
46. Contraintes et intégrité  
47. Workflows et machines d’état  
48. Sécurité  
49. Performance  
50. Sauvegarde et continuité  
51. Tests  
52. CI/CD et déploiement  
53. UX/UI et accessibilité  
54. SEO  
55. Environnements  
56. Roadmap et MVP  
57. Backlog initial  
58. User Stories prioritaires  
59. Critères d’acceptation  
60. Livrables  
61. Risques et mesures de maîtrise  
62. Definition of Done  
63. Dictionnaire des termes métier  
64. Annexes techniques

---

# 1. OBJET ET CONTEXTE

Le présent cahier des charges définit les besoins fonctionnels, techniques et organisationnels pour la réalisation d’un écosystème numérique destiné à une entreprise unique exerçant principalement dans le BTP, le génie civil, les travaux publics, la VRD, l’aménagement, le lotissement et l’énergie.

La solution comprend :

1. un site vitrine institutionnel public ;
2. une application web privée de gestion opérationnelle et administrative des projets et chantiers.

Les deux ensembles partagent le même socle Laravel et PostgreSQL, mais leurs espaces, droits et flux de données sont strictement séparés.

## 1.1 Architecture strictement mono-entreprise

La solution est conçue exclusivement pour une entreprise.

Elle ne comporte pas :

- de multi-tenancy ;
- de `tenant_id` ;
- de `organization_id` ;
- de système de tenants ;
- de société cliente supplémentaire ;
- de filiales ;
- d’agences ;
- de bases ou schémas par entreprise ;
- de résolution de tenant ;
- de package de tenancy tel que Stancl/Tenancy ;
- de mécanisme spécifique de transformation SaaS.

Cette décision est contractuelle et architecturale pour le périmètre du projet.

## 1.2 Organisation interne

L’organisation interne est limitée à :

```text
Entreprise
├── Directions
├── Départements / Services
├── Équipes
├── Employés
└── Utilisateurs
```

Les directions et départements sont représentés par une même hiérarchie organisationnelle si cela simplifie le modèle, mais aucune de ces unités n’est une entreprise indépendante.

---

# 2. VISION ET OBJECTIFS

## 2.1 Vision

Mettre à disposition de l’entreprise un système d’information métier central permettant de suivre un projet depuis son acquisition ou son contrat, sa préparation et son exécution, jusqu’à sa réception et son archivage.

La chaîne fonctionnelle principale est :

```text
Prospect / Client
      ↓
Contrat / Opportunité
      ↓
Projet
      ↓
Chantier
      ↓
Planning
      ↓
Travaux
      ↓
Ressources
      ↓
Avancement
      ↓
Coûts
      ↓
Qualité / Sécurité
      ↓
Réception
      ↓
Reporting
      ↓
Archivage
```

## 2.2 Objectifs métiers

La plateforme doit permettre de :

- centraliser les données ;
- réduire la dépendance aux fichiers Excel et échanges dispersés ;
- suivre les projets et chantiers ;
- maîtriser les délais ;
- maîtriser les coûts ;
- structurer les responsabilités ;
- produire les rapports de chantier ;
- conserver les preuves documentaires ;
- suivre les ressources ;
- piloter les risques et événements QHSE ;
- exposer au client uniquement les informations autorisées ;
- suivre les programmes et lots fonciers ;
- disposer d’indicateurs fiables de pilotage.

---

# 3. PRINCIPES DIRECTEURS

## 3.1 Centralisation

Les données métier doivent être enregistrées dans l’application et rattachées à leur contexte.

## 3.2 Traçabilité

Les opérations critiques doivent être historisées.

## 3.3 Séparation des responsabilités

Le rôle système, la fonction métier et le périmètre de responsabilité sont distincts.

## 3.4 Validation

Les objets sensibles peuvent passer par des statuts et workflows contrôlés.

## 3.5 Simplicité

Aucune abstraction multi-entreprise ne doit être introduite.

## 3.6 Mobile-first terrain

Les usages chantier prioritaires sont conçus pour smartphone et tablette.

## 3.7 Réversibilité fonctionnelle

Une fonctionnalité peut évoluer dans le futur, mais aucune architecture SaaS n’est créée dans le MVP ou la V1.

## 3.8 Historisation

Une donnée validée ne doit pas être modifiée silencieusement.

---

# 4. PÉRIMÈTRE ET HORS PÉRIMÈTRE

## 4.1 Modules inclus

- site vitrine ;
- CMS ;
- authentification ;
- utilisateurs ;
- employés ;
- directions / départements / équipes ;
- rôles et permissions ;
- référentiels ;
- CRM ;
- clients ;
- fournisseurs ;
- sous-traitants ;
- projets ;
- templates de phases ;
- phases de projet ;
- lots de travaux ;
- tâches ;
- dépendances ;
- jalons ;
- planning ;
- suivi d’avancement ;
- chantiers ;
- journal de chantier ;
- rapports quotidiens et hebdomadaires ;
- pointage ;
- matériels ;
- maintenance ;
- matériaux ;
- stock ;
- achats ;
- budgets ;
- engagements ;
- dépenses ;
- factures ;
- paiements ;
- situations de travaux ;
- décomptes ;
- avenants ;
- QHSE ;
- risques ;
- incidents ;
- non-conformités ;
- réceptions et réserves ;
- GED ;
- photos ;
- programmes ;
- lots fonciers ;
- portail client ;
- notifications ;
- recherche globale ;
- audit ;
- reporting ;
- exports PDF / XLSX / CSV.

## 4.2 Hors périmètre

- comptabilité générale ;
- paie ;
- ERP complet ;
- BIM ;
- édition native DWG ;
- SIG avancé ;
- IoT ;
- application mobile native ;
- paiement en ligne ;
- multi-tenant ;
- SaaS multi-entreprises ;
- filiales ;
- agences ;
- IA avancée.

---

# 5. UTILISATEURS ET RESPONSABILITÉS

| Profil | Responsabilités principales |
|---|---|
| Super Administrateur | Configuration globale et sécurité |
| Administrateur général | Administration de l’application |
| Direction Générale | Pilotage global et décisions |
| Direction Technique | Supervision technique |
| Directeur / Chef de Projet | Pilotage du projet |
| Conducteur de Travaux | Coordination de l’exécution |
| Chef de Chantier | Suivi quotidien terrain |
| Ingénieur | Suivi technique |
| Technicien | Exécution / relevés |
| QHSE | Qualité, sécurité, environnement |
| Achats | Approvisionnements |
| Logistique | Ressources et mouvements |
| Finance | Budget, dépenses, factures, paiements |
| Responsable Études | Études, documents et préparation |
| Responsable Foncier | Programmes, lots, réservations |
| Commercial | Prospects, opportunités, demandes |
| Client / Maître d’Ouvrage | Consultation et validation selon workflow |
| Sous-traitant | Données et actions autorisées |
| Fournisseur | Données et interactions autorisées |
| Consultant | Accès limité selon mission |

---

# 6. ORGANISATION INTERNE

## 6.1 Structure

```text
Direction
   ↓
Département / Service
   ↓
Équipe
   ↓
Employé
   ↓
Compte utilisateur éventuel
```

## 6.2 Employee vs User

`Employee` représente la personne travaillant pour l’entreprise.

`User` représente le compte donnant accès au système.

Un employé peut donc exister sans compte utilisateur.

Exemple :

```text
Ouvrier
└── Employee
    └── aucun User obligatoire
```

---

# 7. ARCHITECTURE FONCTIONNELLE GLOBALE

```text
SITE VITRINE
│
├── Présentation
├── Expertises
├── Services
├── Réalisations
├── Programmes / Lotissements
├── Actualités
├── Médias / Références
└── Contact / Devis

APPLICATION PRIVÉE
│
├── Administration
├── CRM
├── Projets
├── Chantiers
├── Ressources
├── Achats
├── Finance
├── QHSE
├── GED
├── Foncier
├── Portail client
├── Reporting
└── Audit
```

---

# 8. SITE VITRINE INSTITUTIONNEL

## 8.1 Objectifs

Le site public doit :

- présenter l’entreprise ;
- valoriser son expertise ;
- démontrer son savoir-faire ;
- présenter les réalisations ;
- présenter les services ;
- présenter les programmes et lotissements ;
- générer des leads ;
- recevoir des demandes de devis ;
- publier les actualités.

## 8.2 Modules

1. Présentation de l’entreprise
2. Expertise et services
3. Projets et réalisations
4. Programmes et lotissements
5. Actualités et publications
6. Commercial / contact
7. Médias et références
8. CMS / administration

## 8.3 Présentation de l’entreprise

Pages :

- accueil ;
- à propos ;
- histoire ;
- vision ;
- mission ;
- valeurs ;
- organisation ;
- équipe ;
- certifications ;
- engagements ;
- moyens humains et techniques.

## 8.4 Accueil

Contenu :

- hero ;
- slogan ;
- résumé ;
- chiffres clés ;
- expertises ;
- services ;
- réalisations mises en avant ;
- programmes ;
- témoignages ;
- partenaires ;
- CTA ;
- contact rapide.

Les chiffres clés ne doivent pas contenir d’indicateur « agences ».

---

# 9. CMS ET PUBLICATION

Le CMS doit permettre de gérer :

- pages ;
- expertises ;
- services ;
- projets publics ;
- programmes publics ;
- lots publics ;
- actualités ;
- médias ;
- partenaires ;
- témoignages ;
- équipe ;
- menus ;
- SEO ;
- paramètres du site.

## 9.1 Brouillon / publication

Statuts minimaux :

```text
Brouillon
Publié
Archivé
```

## 9.2 Séparation public / interne

Un projet interne n’est jamais public automatiquement.

Un contenu public doit être explicitement publié.

---

# 10. CRM ET DEMANDES COMMERCIALES

## 10.1 Flux

```text
Prospect
   ↓
Opportunité
   ↓
Demande / Devis
   ↓
Négociation
   ↓
Contrat
   ↓
Client
   ↓
Projet
```

## 10.2 Demande de devis

Le formulaire public comprend :

- nom ;
- prénom ;
- société ;
- fonction ;
- email ;
- téléphone ;
- localisation ;
- type de prestation ;
- nature du projet ;
- budget indicatif ;
- délai souhaité ;
- description ;
- pièces jointes ;
- consentement.

## 10.3 Statuts

```text
Nouveau
Qualifié
En étude
Devis préparé
Devis envoyé
Négociation
Gagné
Perdu
Archivé
```

---

# 11. AUTHENTIFICATION ET COMPTES

Fonctionnalités :

- connexion ;
- déconnexion ;
- récupération du mot de passe ;
- changement de mot de passe ;
- profil ;
- activation / désactivation ;
- gestion des sessions ;
- authentification renforcée lorsque activée.

---

# 12. RBAC ET AUTORISATIONS

La gestion des rôles et permissions utilise `spatie/laravel-permission`.

## 12.1 Convention

```text
module.action
```

Exemples :

```text
projects.view
projects.create
projects.update
projects.delete
projects.approve
projects.archive

tasks.view
tasks.create
tasks.update
tasks.assign
tasks.complete

reports.view
reports.create
reports.submit
reports.validate

documents.view
documents.upload
documents.update
documents.delete
documents.download
documents.validate

budgets.view
budgets.create
budgets.update
budgets.approve

expenses.view
expenses.create
expenses.update
expenses.validate

lots.view
lots.create
lots.update
lots.reserve
lots.sell
```

## 12.2 Principe

L’autorisation est la combinaison de :

```text
Permission
+
Rôle
+
Département / équipe
+
Affectation projet
+
Affectation chantier
+
Statut de l'objet
+
Confidentialité
```

Les règles contextuelles sont portées par les Policies Laravel.

---

# 13. PROJETS

## 13.1 Fiche projet

Données :

- référence métier unique ;
- intitulé ;
- description ;
- type ;
- département responsable ;
- client ;
- maître d’ouvrage ;
- maître d’œuvre ;
- chef de projet ;
- conducteur de travaux ;
- localisation ;
- latitude / longitude ;
- date de démarrage ;
- date contractuelle de fin ;
- date prévisionnelle de fin ;
- date réelle de fin ;
- montant contractuel ;
- budget initial ;
- budget révisé ;
- méthode d’avancement ;
- méthode de reconnaissance du CA ;
- statut ;
- priorité ;
- observations.

## 13.2 Statuts

```text
Brouillon
Étude
À démarrer
Préparation
En cours
Suspendu
Réception provisoire
Levée des réserves
Réception définitive
Clôture
Terminé
Abandonné
Archivé
```

« En retard » est un **état calculé / indicateur**, et non un état métier persistant obligatoire. Cela évite les incohérences entre statut et calendrier.

---

# 14. TEMPLATES DE PHASES

## 14.1 Objectif

Un template définit une structure réutilisable de phases applicable à un type de projet.

## 14.2 Tables

```text
phase_templates
phase_template_items
```

## 14.3 Exemple

```text
Template Bâtiment
├── Études              10 %
├── Terrassement        10 %
├── Fondations          15 %
├── Gros œuvre          25 %
├── Second œuvre        20 %
├── VRD                 10 %
└── Réception           10 %
```

## 14.4 Règles

Un template validé doit respecter :

```text
Σ poids = 100
0 ≤ poids ≤ 100
```

La borne individuelle peut être protégée par `CHECK` PostgreSQL.

La somme multi-lignes est contrôlée dans le service métier dans une transaction.

## 14.5 Versionnement

Un template peut avoir plusieurs versions :

```text
Bâtiment v1
Bâtiment v2
Bâtiment v3
```

## 14.6 Snapshot projet

Lors de la création d’un projet, les phases sont copiées du template vers `project_phases`.

Une modification ultérieure du template ne modifie jamais un projet existant.

---

# 15. LOTS DE TRAVAUX

Les lots techniques sont distincts des lots fonciers.

Nom recommandé : `work_packages`.

Exemples :

- Gros œuvre ;
- Électricité ;
- Plomberie ;
- Climatisation ;
- Peinture ;
- VRD.

Chaque lot peut contenir :

- responsable ;
- prestataire ;
- budget ;
- planning ;
- avancement ;
- documents ;
- dépenses.

---

# 16. TÂCHES, DÉPENDANCES ET JALONS

## 16.1 Tâches

Une tâche comprend :

- projet ;
- phase ;
- lot de travaux ;
- titre ;
- description ;
- responsable ;
- exécutants ;
- date de début ;
- date de fin ;
- durée ;
- priorité ;
- avancement ;
- statut ;
- dépendances ;
- commentaires ;
- documents.

## 16.2 Statuts

```text
À faire
Planifiée
En cours
Bloquée
En validation
Terminée
Annulée
```

## 16.3 Dépendances

Types minimaux :

- Finish-to-Start ;
- Start-to-Start ;
- Finish-to-Finish ;
- Start-to-Finish si réellement nécessaire.

Le MVP peut limiter l’interface à Finish-to-Start tout en conservant un modèle extensible.

## 16.4 Jalons

Un jalon contient :

- projet ;
- phase éventuelle ;
- responsable ;
- date prévue ;
- date réelle ;
- statut ;
- justificatifs.

---

# 17. PLANNING ET GANTT

Vues :

- liste ;
- calendrier ;
- Kanban ;
- Gantt.

Le Gantt doit permettre :

- affichage des phases ;
- tâches ;
- jalons ;
- dépendances ;
- dates ;
- progression ;
- identification des retards.

---

# 18. SUIVI DE L’AVANCEMENT

## 18.1 Avancement d’une tâche

```text
0 ≤ progress_task ≤ 100
```

## 18.2 Avancement de phase

```text
0 ≤ progress_phase ≤ 100
```

## 18.3 Avancement physique projet

Pour les phases pondérées :

```text
AP = Σ(wᵢ × pᵢ) / 100
```

avec :

```text
Σ wᵢ = 100
0 ≤ pᵢ ≤ 100
```

## 18.4 Modes

- manuel ;
- par tâches ;
- pondéré par phases.

Le mode actif est enregistré dans le projet.

## 18.5 Règle de non-rétroactivité

La méthode et les pondérations appliquées au projet sont celles du snapshot du projet, et non celles d’un template modifié ultérieurement.

---

# 19. CHANTIERS

Un projet peut comporter un ou plusieurs chantiers ou zones d’exécution.

Fiche :

- nom ;
- projet ;
- responsable ;
- adresse ;
- coordonnées ;
- horaires ;
- date de démarrage ;
- date de fin prévue ;
- statut ;
- observations.

---

# 20. JOURNAL ET RAPPORTS DE CHANTIER

## 20.1 Journal

Chaque entrée comporte :

- date ;
- auteur ;
- travaux réalisés ;
- travaux prévus ;
- conditions du jour ;
- main-d’œuvre ;
- matériels ;
- matériaux ;
- incidents ;
- contraintes ;
- observations ;
- photos ;
- documents.

## 20.2 Rapport quotidien

Workflow :

```text
Brouillon
 ↓
Soumis
 ↓
Contrôle conducteur
 ↓
Validé
 ↓
Publié / verrouillé
```

Après validation, toute correction doit être historisée.

## 20.3 Rapport hebdomadaire

Consolide :

- avancement ;
- travaux réalisés ;
- travaux prévus ;
- ressources ;
- consommations ;
- incidents ;
- coûts ;
- risques ;
- décisions ;
- actions.

---

# 21. POINTAGE ET RESSOURCES HUMAINES OPÉRATIONNELLES

Données minimales :

- employé ;
- équipe ;
- chantier ;
- date ;
- fonction ;
- présence ;
- heures normales ;
- heures supplémentaires ;
- activité.

Le module ne constitue pas un logiciel de paie.

---

# 22. MATÉRIELS ET ENGINS

Fiche matériel :

- référence ;
- désignation ;
- type ;
- numéro de série ;
- immatriculation ;
- propriétaire ;
- état ;
- localisation ;
- chantier ;
- responsable.

## 22.1 Maintenance

- préventive ;
- corrective ;
- compteur ;
- échéance ;
- coûts ;
- pièces ;
- prestataire ;
- facture.

Alertes :

- échéance proche ;
- maintenance en retard ;
- immobilisation.

---

# 23. MATÉRIAUX ET STOCKS

Fiche matériau :

- référence ;
- désignation ;
- catégorie ;
- unité ;
- stock ;
- seuil minimal ;
- fournisseur privilégié ;
- prix indicatif.

Mouvements :

```text
Entrée
Sortie
Transfert
Retour
Ajustement
```

Une consommation doit être rattachable à :

- projet ;
- chantier ;
- phase ;
- lot ;
- matériau ;
- quantité ;
- prix ;
- date ;
- auteur.

---

# 24. ACHATS

Workflow cible :

```text
Besoin
 ↓
Demande d’achat
 ↓
Validation
 ↓
Consultation fournisseur
 ↓
Bon de commande
 ↓
Réception
 ↓
Facture
 ↓
Paiement
```

Le MVP peut se limiter à :

- demandes ;
- fournisseurs ;
- commandes ;
- réceptions.

---

# 25. BUDGET ET CONTRÔLE DES COÛTS

## 25.1 Structure

```text
Projet
├── Phase
│   └── Work Package
│       ├── Poste
│       └── Catégorie
```

## 25.2 Versions budgétaires

Un projet doit conserver :

```text
Budget initial
Budget révisé
Versions intermédiaires
```

Une version budgétaire comprend :

- numéro de version ;
- date ;
- auteur ;
- justification ;
- montant total ;
- statut ;
- validation.

## 25.3 Statuts

```text
Brouillon
Soumis
Validé
Remplacé
Archivé
```

Une version validée ne doit pas être modifiée silencieusement.

---

# 26. DÉPENSES ET ENGAGEMENTS

Le système doit distinguer :

```text
Coût engagé
≠
Coût commandé
≠
Coût facturé
≠
Coût payé
```

Une dépense comprend :

- référence ;
- projet ;
- chantier ;
- phase ;
- lot ;
- catégorie ;
- fournisseur éventuel ;
- montant HT ;
- taxes ;
- TTC ;
- date ;
- justificatif ;
- auteur ;
- statut ;
- validation.

---

# 27. FACTURES ET PAIEMENTS

## 27.1 Facture

- numéro ;
- fournisseur ;
- projet ;
- lignes ;
- HT ;
- taxes ;
- TTC ;
- date ;
- échéance ;
- statut ;
- document.

## 27.2 Workflow

```text
Brouillon
 ↓
Reçue
 ↓
Contrôlée
 ↓
Approuvée
 ↓
Partiellement payée
 ↓
Payée
```

## 27.3 Paiement

Chaque paiement peut être partiel et doit être rattaché à une ou plusieurs factures selon les règles retenues.

---

# 28. SITUATIONS DE TRAVAUX

Une situation représente une demande de paiement liée à l’avancement.

Informations :

- période ;
- montant contractuel ;
- travaux réalisés ;
- montant précédent ;
- montant courant ;
- cumul ;
- avances ;
- retenues ;
- pénalités ;
- net à payer ;
- justificatifs.

Workflow :

```text
Brouillon
 ↓
Soumise
 ↓
Contrôlée
 ↓
Validée
 ↓
Facturée
 ↓
Payée
```

---

# 29. DÉCOMPTES

Le décompte consolide :

- montant initial ;
- avenants applicables ;
- travaux exécutés ;
- périodes précédentes ;
- période courante ;
- cumul ;
- avances ;
- retenues ;
- pénalités ;
- net à payer.

---

# 30. AVENANTS

## 30.1 Données

```text
contract_amendments
```

Champs :

- projet ;
- référence ;
- titre ;
- type ;
- motif ;
- montant HT ;
- `is_incremental` ;
- `applied_date` ;
- statut ;
- date d’approbation ;
- validateur ;
- document ;
- impact délai ;
- observations.

## 30.2 Avenant incrémental

Lorsque `is_incremental = true`, l’avenant modifie le montant par addition ou soustraction.

## 30.3 Budget révisé

Formule de référence :

```text
Budget révisé =
Budget initial
+ Σ montants des avenants incrémentaux approuvés et applicables
```

Un avenant devient applicable uniquement si :

```text
status = APPROVED
ET applied_date <= date d’analyse
```

## 30.4 Historisation

La valeur historique du budget à une date donnée doit rester reconstruisible.

---

# 31. QHSE

Le module couvre :

- risques ;
- incidents ;
- accidents ;
- observations ;
- non-conformités ;
- inspections ;
- contrôles ;
- actions correctives ;
- preuves documentaires.

## 31.1 Risque

- projet ;
- chantier éventuel ;
- description ;
- probabilité ;
- impact ;
- criticité ;
- responsable ;
- plan de mitigation ;
- échéance ;
- statut.

## 31.2 Incident

- date ;
- heure ;
- lieu ;
- projet ;
- chantier ;
- type ;
- gravité ;
- description ;
- personnes concernées ;
- équipement concerné ;
- photos ;
- actions ;
- responsable ;
- statut.

Workflow :

```text
Nouveau
 ↓
En analyse
 ↓
Action en cours
 ↓
Résolu
 ↓
Clôturé
```

## 31.3 Non-conformité

- type ;
- cause ;
- impact ;
- responsable ;
- action corrective ;
- échéance ;
- preuve ;
- statut.

---

# 32. RÉCEPTION ET RÉSERVES

## 32.1 Réception provisoire

- date ;
- participants ;
- réserves ;
- PV ;
- statut.

## 32.2 Réserve

- description ;
- responsable ;
- échéance ;
- preuve ;
- date de correction ;
- statut.

## 32.3 Réception définitive

- date ;
- PV ;
- observations.

Une clôture définitive est conditionnée par les prérequis métier définis dans le workflow.

---

# 33. GED

## 33.1 Types de documents

- contrats ;
- études ;
- plans ;
- devis ;
- marchés ;
- PV ;
- rapports ;
- factures ;
- situations ;
- certificats ;
- autorisations ;
- DOE ;
- photos.

## 33.2 Classement

```text
Projet
├── Contrat
├── Études
├── Plans
├── Travaux
├── QHSE
├── Finance
├── Réception
└── DOE
```

## 33.3 Versionnement

Un document peut avoir plusieurs versions.

```text
Document
├── v1
├── v2
└── v3
```

Chaque version conserve :

- auteur ;
- date ;
- numéro ;
- commentaire ;
- statut.

## 33.4 Confidentialité

Niveaux :

```text
Public
Interne
Restreint
Confidentiel
```

---

# 34. PHOTOS ET MÉDIAS

Une photo de chantier peut contenir :

- fichier ;
- auteur ;
- date ;
- projet ;
- chantier ;
- activité ;
- emplacement ;
- légende ;
- catégorie ;
- géolocalisation facultative.

Catégories :

- avant travaux ;
- pendant travaux ;
- après travaux ;
- incident ;
- qualité ;
- sécurité ;
- réception.

---

# 35. FONCIER ET LOTISSEMENTS

## 35.1 Structure

```text
Programme
  ↓
Zone
  ↓
Îlot / Bloc
  ↓
Lot foncier
  ↓
Réservation / Vente
```

## 35.2 Lot foncier

Informations :

- référence ;
- programme ;
- zone ;
- bloc/îlot ;
- superficie ;
- dimensions ;
- usage ;
- coordonnées ;
- prix ;
- statut.

Statuts :

```text
Disponible
Option
Réservé
Sous contrat
Vendu
Indisponible
Litige
```

## 35.3 Réservation

Une réservation critique doit être transactionnelle et protégée contre la double réservation.

## 35.4 Viabilisation

Suivi :

- voirie ;
- eau ;
- électricité ;
- assainissement ;
- drainage ;
- éclairage ;
- télécommunications ;
- irrigation si applicable.

---

# 36. PORTAIL CLIENT / MAÎTRE D’OUVRAGE

Le portail client est distinct des interfaces internes.

## 36.1 Accès

Le client ne voit que les projets qui lui sont associés.

## 36.2 Informations visibles

- état du projet ;
- avancement physique ;
- planning autorisé ;
- jalons ;
- rapports validés ;
- photos publiées ;
- documents autorisés ;
- réserves ;
- informations contractuelles explicitement publiées.

## 36.3 Actions

- consulter ;
- télécharger ;
- commenter ;
- déposer une observation ;
- valider un document lorsque le workflow l’autorise ;
- demander une information.

## 36.4 Interdictions par défaut

- marges ;
- coûts internes ;
- achats internes ;
- coûts fournisseurs ;
- coûts sous-traitants ;
- salaires ;
- notes internes ;
- documents confidentiels.

## 36.5 Publication client

Un rapport ou document interne doit être validé avant d’être visible du client.

Les actions juridiquement ou contractuellement engageantes doivent être distinguées d’un simple accusé de réception.

---

# 37. NOTIFICATIONS

Canaux :

- in-app ;
- email.

Événements :

- tâche assignée ;
- échéance ;
- retard ;
- dépassement budgétaire ;
- rapport soumis ;
- document soumis ;
- validation ;
- incident ;
- maintenance ;
- expiration ;
- réservation de lot.

Les préférences utilisateur sont configurables.

---

# 38. RECHERCHE GLOBALE

Recherche sur :

- projets ;
- chantiers ;
- tâches ;
- clients ;
- fournisseurs ;
- employés ;
- documents ;
- programmes ;
- lots ;
- rapports.

Le MVP utilise PostgreSQL ; un moteur spécialisé n’est envisagé que si les volumes réels le justifient.

---

# 39. REPORTING ET KPI

## 39.1 Avancement physique

```text
AP = Σ(wᵢ × pᵢ) / 100
```

## 39.2 Taux de consommation budgétaire

```text
TCB = Coût réel / Budget révisé × 100
```

Ce KPI ne doit pas être nommé « avancement financier ».

## 39.3 Taux d’engagement

```text
TE = Coût engagé / Budget révisé × 100
```

## 39.4 Taux de facturation

```text
TF = Montant facturé / Montant contractuel × 100
```

## 39.5 Taux d’encaissement

```text
TA = Montant encaissé / Montant facturé × 100
```

## 39.6 Écart budgétaire

```text
EB = Budget révisé - Coût réel
```

## 39.7 Marge de gestion

```text
M = CA reconnu - Coût réel projet
```

### CA reconnu

Selon le mode du projet :

- facturation à l’avancement : somme des situations validées ;
- forfait : montant contractuel selon les règles métier retenues ;
- mode mixte : règle explicitement configurée.

Le mode de reconnaissance du CA doit être stocké dans le projet.

## 39.8 Coût prévisionnel à terminaison

```text
EAC = Coût réel + Coût estimé restant
```

## 39.9 Écart à terminaison

```text
VAC = Budget révisé - EAC
```

## 39.10 Retard

Pour le projet :

```text
Retard = max(0, Date de référence - Date de fin prévue)
```

si le projet n’est pas terminé.

La même logique doit pouvoir être calculée pour les phases, tâches et jalons.

## 39.11 Alerte dérive

Exemple de règle par défaut :

```text
|AP - TCB| > seuil_configurable
```

Valeur initiale recommandée : 15 points.

## 39.12 KPI foncier

```text
TC = (Lots vendus + lots réservés)
     / Lots commercialisables × 100
```

Les lots non commercialisables sont exclus du dénominateur.

---

# 40. AUDIT ET TRAÇABILITÉ

Les opérations critiques doivent générer un événement d’audit.

Exemples :

```text
USER_CREATED
ROLE_ASSIGNED
PROJECT_CREATED
PROJECT_STATUS_CHANGED
BUDGET_APPROVED
BUDGET_VERSION_CREATED
EXPENSE_VALIDATED
DOCUMENT_UPLOADED
DOCUMENT_VALIDATED
REPORT_SUBMITTED
REPORT_VALIDATED
LOT_RESERVED
LOT_SOLD
PAYMENT_VALIDATED
```

Données minimales :

- utilisateur ;
- date/heure ;
- action ;
- objet ;
- identifiant ;
- anciennes valeurs lorsque pertinent ;
- nouvelles valeurs lorsque pertinent ;
- IP lorsque nécessaire.

---

# 41. MODE TERRAIN ET CONNECTIVITÉ INTERMITTENTE

## 41.1 MVP offline léger

Le chef de chantier doit pouvoir préparer localement :

- brouillon de rapport ;
- observations ;
- photos à transmettre ;
- pointage lorsque le scénario le permet.

## 41.2 Mécanisme

Le navigateur utilise `IndexedDB` pour les données structurées locales et les files de synchronisation.

Flux :

```text
Saisie terrain
   ↓
Stockage local
   ↓
File de synchronisation
   ↓
Connexion retrouvée
   ↓
Synchronisation
   ↓
Validation serveur
   ↓
Confirmation
```

## 41.3 Conflits

Un conflit doit être détecté et ne doit jamais entraîner un écrasement silencieux.

Informations de conflit :

- version locale ;
- version serveur ;
- utilisateur ;
- date ;
- objet ;
- résolution.

## 41.4 Offline complet

Le cache massif de documents, les synchronisations avancées et la résolution interactive de conflits pourront être renforcés dans une phase ultérieure.

---

# 42. ARCHITECTURE TECHNIQUE

## 42.1 Stack

| Couche | Technologie |
|---|---|
| Backend | Laravel 13+ |
| Langage | PHP compatible avec Laravel cible |
| Frontend | Livewire 4 + Blade |
| UI | Tailwind CSS + Flux UI |
| Base | PostgreSQL |
| Cache / Queue | Redis |
| Auth / RBAC | Laravel + Spatie Permission |
| Stockage | Local / S3 compatible |
| Web | Nginx |
| Runtime | PHP-FPM |

## 42.2 Architecture applicative

```text
Route / Livewire
       ↓
Form / Validation
       ↓
Action
       ↓
Service métier
       ↓
Policy / Autorisation
       ↓
Eloquent
       ↓
PostgreSQL
```

Les composants d’interface ne doivent pas concentrer toute la logique métier.

---

# 43. ARCHITECTURE LIVEWIRE SFC / MFC

## 43.1 Principe

Approche **SFC-first**.

### SFC pour

- CRUD simples ;
- listes ;
- filtres ;
- petites modales ;
- formulaires simples ;
- widgets simples.

### MFC pour

- dashboards complexes ;
- fiche projet ;
- Gantt ;
- Kanban complexe ;
- rapport chantier avancé ;
- GED ;
- carte foncière ;
- wizard multi-étapes.

Règle :

> SFC par défaut ; MFC dès qu’un composant devient structurellement complexe.

---

# 44. ACTIONS ET SERVICES MÉTIER

Les opérations critiques sont isolées dans des Actions.

Exemples :

```text
CreateProject
UpdateProject
StartProject
CompleteProject
CreateDailyReport
ValidateDailyReport
ApproveBudget
CreateBudgetVersion
CreateExpense
ValidateInvoice
ApplyContractAmendment
ReserveLandLot
SellLandLot
UploadDocument
ValidateDocument
PublishProject
PublishDocumentToClient
```

Services :

```text
ProjectProgressService
FinancialKpiService
BudgetService
DocumentService
NotificationService
LotReservationService
ProjectWorkflowService
SynchronizationService
```

---

# 45. MODÈLE POSTGRESQL

## 45.1 Utilisateurs et organisation

```text
users
employees
departments
teams
team_members
```

## 45.2 CRM

```text
customers
customer_contacts
leads
opportunities
quote_requests
```

## 45.3 Projets

```text
projects
project_members
phase_templates
phase_template_items
project_phases
work_packages
milestones
tasks
task_dependencies
task_comments
```

## 45.4 Chantiers

```text
sites
daily_reports
weekly_reports
work_logs
site_activities
site_incidents
```

## 45.5 Ressources

```text
equipment
equipment_assignments
equipment_maintenance
materials
material_movements
```

## 45.6 Achats

```text
suppliers
purchase_requests
purchase_orders
purchase_order_items
goods_receipts
```

## 45.7 Finance

```text
budgets
budget_items
budget_versions
expenses
invoices
invoice_items
payments
work_situations
progress_claims
contract_amendments
```

## 45.8 GED

```text
documents
document_versions
document_categories
media
```

## 45.9 QHSE

```text
risks
incidents
non_conformities
inspections
corrective_actions
```

## 45.10 Foncier

```text
programs
program_zones
blocks
land_lots
lot_reservations
lot_sales
land_work_packages
```

## 45.11 CMS

```text
pages
expertises
services
public_projects
posts
partners
testimonials
menus
menu_items
site_settings
```

## 45.12 Système

```text
notifications
audit_logs
```

---

# 46. RELATIONS PRINCIPALES

```text
Department
├── Employees
└── Teams
      └── TeamMembers

Employee
└── User (optionnel)

Project
├── Customer
├── ProjectMembers
├── Sites
├── ProjectPhases
├── WorkPackages
├── Milestones
├── Tasks
├── Budget
├── Expenses
├── Documents
├── DailyReports
├── Risks
└── Incidents

Program
└── ProgramZones
      └── Blocks
            └── LandLots
                  ├── Reservations
                  └── Sales
```

---

# 47. CONTRAINTES ET INTÉGRITÉ

## 47.1 Contraintes numériques

Exemples :

```sql
CHECK (progress >= 0 AND progress <= 100)
CHECK (weight >= 0 AND weight <= 100)
CHECK (amount >= 0)
```

## 47.2 Dates

```text
start_date <= end_date
```

lorsque les deux dates sont renseignées.

## 47.3 Unicité

Exemples :

- référence projet unique ;
- référence lot foncier unique dans son programme ;
- numéro de facture unique selon la règle définie ;
- référence de document contrôlée.

## 47.4 Intégrité relationnelle

Toutes les références critiques doivent utiliser des foreign keys appropriées.

---

# 48. WORKFLOWS ET MACHINES D’ÉTAT

Les statuts importants doivent être accompagnés de transitions contrôlées.

## 48.1 Projet

```text
Étude
 ↓
Offre / Devis
 ↓
Négociation
 ↓
Contrat
 ↓
Préparation
 ↓
Démarrage
 ↓
Exécution
 ↓
Réception provisoire
 ↓
Levée des réserves
 ↓
Réception définitive
 ↓
Clôture
 ↓
Archivage
```

Chaque transition définit :

- état source ;
- état cible ;
- rôle autorisé ;
- préconditions ;
- effets ;
- audit.

## 48.2 Rapport chantier

```text
Brouillon → Soumis → Contrôlé → Validé → Verrouillé
```

## 48.3 Budget

```text
Brouillon → Soumis → Validé → Remplacé
```

## 48.4 Lot foncier

```text
Disponible → Option → Réservé → Sous contrat → Vendu
```

Les transitions invalides doivent être refusées côté serveur.

---

# 49. SÉCURITÉ

Exigences :

- authentification sécurisée ;
- RBAC ;
- Policies ;
- contrôle du contexte projet ;
- validation serveur ;
- CSRF ;
- XSS ;
- SQL injection via Eloquent/requêtes paramétrées ;
- rate limiting ;
- sessions sécurisées ;
- contrôle des uploads ;
- limitation des extensions ;
- vérification MIME ;
- stockage protégé des fichiers privés ;
- journaux d’audit ;
- sauvegardes.

Les données confidentielles ne doivent jamais être exposées par simple connaissance d’une URL.

---

# 50. PERFORMANCE

Prévoir :

- pagination ;
- eager loading ;
- détection des N+1 ;
- index PostgreSQL ;
- cache Redis ;
- queues ;
- traitements asynchrones ;
- génération PDF différée ;
- compression et redimensionnement des images ;
- requêtes ciblées ;
- agrégations optimisées pour dashboards.

---

# 51. SAUVEGARDE ET CONTINUITÉ

Prévoir au minimum :

- sauvegarde PostgreSQL quotidienne ;
- sauvegarde des fichiers ;
- stockage hors serveur principal ;
- politique de rétention ;
- tests réguliers de restauration ;
- procédure de reprise.

Les sauvegardes doivent être surveillées et les échecs signalés.

---

# 52. TESTS

## 52.1 Tests unitaires

- calcul d’avancement ;
- calcul KPI ;
- application des avenants ;
- règles de budget ;
- transitions de statut ;
- réservation de lots ;
- règles d’autorisation.

## 52.2 Tests Feature

- CRUD ;
- workflows ;
- permissions ;
- notifications ;
- uploads ;
- portail client.

## 52.3 Tests E2E

Parcours critiques :

```text
Créer projet
→ planifier
→ créer chantier
→ rapport
→ validation
→ budget
→ dépense
→ reporting
```

et :

```text
Programme
→ lot disponible
→ réservation
→ contrat
→ vente
```

---

# 53. CI/CD ET DÉPLOIEMENT

## 53.1 Pipeline

```text
Commit
 ↓
Lint
 ↓
Tests
 ↓
Build
 ↓
Validation
 ↓
Staging
 ↓
Recette
 ↓
Production
```

## 53.2 Infrastructure cible

```text
Nginx
PHP-FPM
Laravel
PostgreSQL
Redis
Queue Worker
Scheduler
Storage
SSL
```

---

# 54. UX/UI ET ACCESSIBILITÉ

La plateforme doit :

- être responsive ;
- privilégier la lisibilité ;
- afficher clairement les états ;
- utiliser des actions explicites ;
- afficher des messages d’erreur exploitables ;
- proposer des empty states ;
- fournir des feedbacks d’enregistrement ;
- respecter les principes élémentaires d’accessibilité.

## 54.1 Priorités mobile terrain

```text
Rapport
Photo
Incident
Pointage
Tâche
Observation
Avancement
Document
```

---

# 55. SEO DU SITE VITRINE

Prévoir :

- URL canonique ;
- slug ;
- meta title ;
- meta description ;
- Open Graph ;
- sitemap XML ;
- robots.txt ;
- données structurées pertinentes ;
- textes alternatifs ;
- optimisation des images.

---

# 56. ENVIRONNEMENTS

Minimum :

```text
Development
Staging / Recette
Production
```

Les données de production ne doivent pas être copiées sans anonymisation dans les environnements non productifs.

---

# 57. ROADMAP ET MVP

## Phase 0 — Cadrage

- validation du CDC ;
- ateliers métier ;
- règles de gestion ;
- parcours ;
- modèle de données.

## Phase 1 — Socle

- authentification ;
- utilisateurs ;
- rôles ;
- permissions ;
- départements ;
- équipes ;
- audit.

## Phase 2 — Site vitrine / CMS

- pages ;
- expertises ;
- services ;
- projets publics ;
- programmes ;
- actualités ;
- contact ;
- devis.

## Phase 3 — Projets / Chantiers

- projets ;
- templates de phases ;
- phases ;
- work packages ;
- tâches ;
- jalons ;
- planning ;
- chantiers ;
- rapports ;
- GED.

## Phase 4 — Ressources / Achats

- employés ;
- équipes ;
- pointage ;
- matériel ;
- maintenance ;
- matériaux ;
- fournisseurs ;
- sous-traitants ;
- achats.

## Phase 5 — Finance

- budgets ;
- versions budgétaires ;
- dépenses ;
- factures ;
- paiements ;
- situations ;
- décomptes ;
- avenants.

## Phase 6 — QHSE / Foncier / Portail client

- risques ;
- incidents ;
- non-conformités ;
- réserves ;
- programmes ;
- lots ;
- portail client.

## Phase 7 — Reporting / Offline / Optimisation

- KPI ;
- dashboards ;
- exports ;
- offline léger ;
- performance ;
- sécurité ;
- recette.

---

# 58. BACKLOG INITIAL

| ID | Module | Fonctionnalité | Priorité |
|---|---|---|---|
| AUTH-001 | Auth | Connexion | Must |
| AUTH-002 | Auth | Reset mot de passe | Must |
| RBAC-001 | RBAC | Rôles | Must |
| RBAC-002 | RBAC | Permissions | Must |
| ORG-001 | Organisation | Départements | Must |
| ORG-002 | Organisation | Équipes | Must |
| PRJ-001 | Projet | CRUD projet | Must |
| PRJ-002 | Projet | Templates phases | Must |
| PRJ-003 | Projet | Phases projet | Must |
| PRJ-004 | Projet | Jalons | Must |
| TASK-001 | Tâches | CRUD | Must |
| TASK-002 | Tâches | Dépendances | Should |
| PLAN-001 | Planning | Gantt | Should |
| SITE-001 | Chantier | CRUD | Must |
| SITE-002 | Chantier | Journal | Must |
| SITE-003 | Chantier | Rapport quotidien | Must |
| HR-001 | Terrain | Pointage | Should |
| RES-001 | Matériel | CRUD | Should |
| MAT-001 | Matériaux | Stocks | Should |
| DOC-001 | GED | Upload | Must |
| DOC-002 | GED | Versionnement | Must |
| FIN-001 | Finance | Budget | Must |
| FIN-002 | Finance | Dépenses | Must |
| FIN-003 | Finance | Versions budget | Should |
| FIN-004 | Finance | Situations | Should |
| FIN-005 | Finance | Avenants | Should |
| CRM-001 | CRM | Clients | Must |
| CRM-002 | CRM | Prospects | Should |
| SUP-001 | Achats | Fournisseurs | Should |
| LAND-001 | Foncier | Programmes | Should |
| LAND-002 | Foncier | Lots | Should |
| LAND-003 | Foncier | Réservations | Should |
| QHSE-001 | QHSE | Incidents | Should |
| QHSE-002 | QHSE | Risques | Should |
| QHSE-003 | QHSE | Non-conformités | Could |
| CLIENT-001 | Portail | Consultation | Should |
| CLIENT-002 | Portail | Validation documentaire | Could |
| CMS-001 | CMS | Pages | Must |
| CMS-002 | CMS | Services | Must |
| CMS-003 | CMS | Projets publics | Must |
| CMS-004 | CMS | Actualités | Should |
| OFF-001 | Offline | Brouillons rapport | Should |
| RPT-001 | Reporting | Dashboard direction | Must |
| RPT-002 | Reporting | Dashboard projet | Must |
| AUD-001 | Audit | Journal | Must |

---

# 59. USER STORIES PRIORITAIRES

## US-PRJ-001 — Créer un projet

**En tant que** chef de projet,  
**je veux** créer un projet,  
**afin de** centraliser les informations et démarrer son suivi.

### Critères

- référence unique ;
- client renseigné ou explicitement absent ;
- responsable affecté ;
- statut initial valide ;
- audit généré.

## US-PRJ-002 — Appliquer un template

Le système doit copier les phases et pondérations du template dans le projet.

## US-SITE-001 — Saisir un rapport terrain

Le chef de chantier doit pouvoir saisir un rapport depuis mobile.

## US-FIN-001 — Créer une version budgétaire

Une version doit être enregistrée comme objet historique distinct.

## US-LOT-001 — Réserver un lot

La réservation doit être transactionnelle et empêcher une double réservation concurrente.

## US-CLIENT-001 — Consulter un projet

Le client ne voit que les données publiées qui lui sont associées.

---

# 60. CRITÈRES D’ACCEPTATION

## 60.1 Projet

Le système doit :

1. créer un projet ;
2. attribuer une référence unique ;
3. associer un client ;
4. affecter un responsable ;
5. appliquer un template ;
6. créer les phases ;
7. calculer l’avancement ;
8. associer un chantier ;
9. associer des documents ;
10. produire l’audit.

## 60.2 Rapport chantier

Le système doit permettre :

- création ;
- brouillon ;
- photos ;
- ressources ;
- incident ;
- soumission ;
- validation ;
- verrouillage.

## 60.3 Budget

Le système doit :

- créer un budget ;
- versionner ;
- valider ;
- associer des dépenses ;
- calculer TCB ;
- détecter une dérive selon seuil.

## 60.4 Avenant

Le système doit :

- enregistrer l’avenant ;
- distinguer incrémental / autre type ;
- contrôler le statut ;
- utiliser `applied_date` ;
- recalculer le budget applicable ;
- historiser l’opération.

## 60.5 Portail client

Le système doit garantir :

- accès limité aux projets associés ;
- aucune fuite de données internes ;
- visibilité uniquement des éléments publiés ;
- audit des validations client.

---

# 61. LIVRABLES

Le prestataire doit fournir :

## Logiciel

- code source ;
- migrations ;
- seeders ;
- factories ;
- tests ;
- configuration.

## Documentation

- documentation technique ;
- documentation installation ;
- documentation administration ;
- manuel utilisateur ;
- dictionnaire de données.

## Infrastructure

- configuration serveur ;
- SSL ;
- stockage ;
- queues ;
- scheduler ;
- sauvegardes.

## Recette

- scénarios de recette ;
- résultats ;
- anomalies ;
- PV de recette.

---

# 62. RISQUES ET MESURES DE MAÎTRISE

| Risque | Impact | Mesure |
|---|---|---|
| Périmètre trop large | Élevé | MVP strict |
| Mauvais modèle métier | Élevé | ateliers et validation du schéma |
| Adoption terrain faible | Élevé | UX mobile et offline léger |
| Données financières incohérentes | Élevé | règles + transactions |
| Double réservation | Élevé | verrouillage transactionnel |
| Modification de données validées | Élevé | versionnement + audit |
| Permissions insuffisantes | Élevé | RBAC + Policies + tests |
| Gros volume documentaire | Moyen | stockage objet et quotas |
| Planning complexe | Moyen | MVP Gantt progressif |
| Données historiques inexistantes | Moyen | stratégie d’import |

---

# 63. DEFINITION OF DONE

Une fonctionnalité est considérée comme terminée uniquement si :

- développement terminé ;
- règles métier implémentées ;
- validation serveur active ;
- permissions contrôlées ;
- Policy créée si nécessaire ;
- tests écrits ;
- tests passants ;
- responsive ;
- erreurs gérées ;
- audit ajouté lorsque nécessaire ;
- documentation mise à jour ;
- critères d’acceptation validés.

---

# 64. DICTIONNAIRE DES TERMES MÉTIER

**Projet :** opération contractuelle et organisationnelle à réaliser.

**Chantier :** unité physique d’exécution.

**Phase :** grande étape d’exécution.

**Work Package / Lot de travaux :** périmètre technique ou contractuel d’exécution.

**Jalon :** événement significatif du planning.

**Situation de travaux :** demande de paiement liée à l’avancement.

**Décompte :** synthèse des travaux exécutés et sommes dues.

**Avenant :** modification contractuelle affectant notamment montant ou délai.

**Déboursé :** coût supporté ou engagé pour exécuter les travaux.

**GED :** Gestion Électronique des Documents.

**QHSE :** Qualité, Hygiène, Sécurité, Environnement.

**VRD :** Voirie et Réseaux Divers.

**DOE :** Dossier des Ouvrages Exécutés.

**RBAC :** Role-Based Access Control.

---

# ANNEXE A — RÈGLE D’OR DU PRODUIT

La plateforme doit permettre à la direction de répondre rapidement à ces questions :

### Où en sommes-nous ?

Avancement physique, consommation budgétaire, facturation et encaissement.

### Où sommes-nous en retard ?

Tâches, phases, jalons et projets.

### Où perdons-nous de l’argent ?

Budget, engagements, coûts réels, EAC et VAC.

### Qu’est-ce qui bloque ?

Incidents, risques, dépendances, approvisionnements.

### Qui fait quoi ?

Responsables et affectations.

### Quels documents sont disponibles ?

GED et versions.

### Que s’est-il passé ?

Journal et rapports.

### Que reste-t-il à faire ?

Planning et tâches.

### Que reste-t-il à vendre ?

Lots fonciers commercialisables.

---

# ANNEXE B — POLICIES RECOMMANDÉES

```text
ProjectPolicy
SitePolicy
TaskPolicy
BudgetPolicy
BudgetVersionPolicy
ExpensePolicy
InvoicePolicy
DocumentPolicy
DailyReportPolicy
LotPolicy
CustomerPolicy
ProgramPolicy
WorkPackagePolicy
RiskPolicy
IncidentPolicy
```

---

# ANNEXE C — JOBS RECOMMANDÉS

```text
GenerateProjectReport
GeneratePdfDocument
SendProjectNotification
ProcessUploadedDocument
GenerateThumbnail
ExportProjectExcel
SendExpiryNotification
SynchronizeOfflinePayload
```

---

# ANNEXE D — ÉVÉNEMENTS RECOMMANDÉS

```text
ProjectCreated
ProjectStarted
ProjectCompleted
ProjectStatusChanged
TaskAssigned
TaskCompleted
DailyReportSubmitted
DailyReportValidated
BudgetApproved
BudgetVersionCreated
BudgetExceeded
DocumentUploaded
DocumentValidated
ContractAmendmentApproved
LotReserved
LotSold
InvoiceValidated
PaymentValidated
```

---

# ANNEXE E — EXEMPLES D’IDENTIFIANTS MÉTIER

```text
PRJ-2026-0015
CH-2026-0021
WP-2026-0010
LOT-B12-045
FAC-2026-0045
RPT-2026-0012
```

L’identifiant technique et la référence métier doivent rester distincts.

---

# ANNEXE F — STRUCTURE DE NAVIGATION DE L’APPLICATION

```text
Dashboard
│
├── CRM
│   ├── Prospects
│   ├── Opportunités
│   ├── Clients
│   └── Demandes de devis
│
├── Projets
│   ├── Tous les projets
│   ├── Phases
│   ├── Lots de travaux
│   ├── Tâches
│   ├── Jalons
│   └── Planning
│
├── Chantiers
│   ├── Chantiers
│   ├── Rapports
│   ├── Journal
│   ├── Pointage
│   └── Incidents
│
├── Ressources
│   ├── Employés
│   ├── Équipes
│   ├── Matériels
│   └── Matériaux
│
├── Achats
│   ├── Fournisseurs
│   ├── Demandes
│   ├── Commandes
│   └── Réceptions
│
├── Finance
│   ├── Budgets
│   ├── Dépenses
│   ├── Factures
│   ├── Situations
│   ├── Décomptes
│   ├── Avenants
│   └── Paiements
│
├── QHSE
│   ├── Risques
│   ├── Incidents
│   ├── Non-conformités
│   └── Inspections
│
├── GED
│
├── Foncier
│   ├── Programmes
│   ├── Zones
│   ├── Blocs / Îlots
│   ├── Lots
│   ├── Réservations
│   └── Ventes
│
├── Reporting
│
└── Administration
    ├── Utilisateurs
    ├── Rôles
    ├── Permissions
    ├── Départements
    ├── Équipes
    └── Paramètres
```

---

# ANNEXE G — PORTAIL CLIENT

```text
Accueil
│
├── Mes projets
├── Avancement
├── Planning
├── Rapports validés
├── Photos
├── Documents
├── Réserves
├── Observations
└── Notifications
```

---

# ANNEXE H — CHECKLIST AVANT DÉVELOPPEMENT

```text
[ ] CDC validé
[ ] Modèle PostgreSQL validé
[ ] Nommage des entités validé
[ ] Permissions validées
[ ] Workflows validés
[ ] KPI validés
[ ] Templates de phases validés
[ ] Modèle budgétaire validé
[ ] Modèle avenants validé
[ ] Portail client validé
[ ] Mode terrain validé
[ ] Wireframes validés
[ ] Backlog MVP validé
[ ] Environnement de développement prêt
```

---

# CONCLUSION

La V1.6 définit une plateforme BTP **strictement mono-entreprise**, sans agences, sans filiales et sans multi-tenant.

Le cœur du produit est constitué de la relation :

```text
Projet
  ↓
Chantier
  ↓
Planning
  ↓
Travaux
  ↓
Ressources
  ↓
Avancement
  ↓
Coûts
  ↓
Documents
  ↓
QHSE
  ↓
Reporting
```

Le site vitrine assure simultanément les fonctions de présentation, valorisation commerciale, génération de prospects et publication des projets et programmes autorisés.

La V1.6 formalise les éléments nécessaires à une implémentation fiable :

- modèle mono-entreprise définitif ;
- distinction `Employee` / `User` ;
- SFC-first avec MFC pour les composants complexes ;
- templates de phases versionnés ;
- séparation `work_packages` / `land_lots` ;
- workflows contrôlés ;
- KPI physiques et financiers distincts ;
- versions budgétaires ;
- avenants et budget révisé historisés ;
- portail client ;
- GED ;
- mode terrain avec synchronisation légère ;
- RBAC + Policies ;
- audit ;
- tests et critères d’acceptation.

Le document constitue le référentiel fonctionnel et technique de la phase de conception détaillée et du développement.

---

**Version :** 1.6  
**Statut :** Référence de conception  
**Architecture :** Laravel 13+ / Livewire 4 / PostgreSQL / Redis / Spatie Permission  
**Périmètre :** strictement mono-entreprise
