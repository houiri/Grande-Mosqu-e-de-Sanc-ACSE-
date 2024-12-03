#!/bin/bash

# Créer la structure de dossiers
mkdir -p alfurqan/{assets,data,includes,layouts,js}

# Créer les fichiers PHP principaux
touch alfurqan/{index,quran,reciters,arabic,memorization,qa,podcast,history,liked,favorites}.php

# Créer les fichiers de mise en page
touch alfurqan/layouts/{header,footer}.php

# Créer les fichiers d'inclusion
touch alfurqan/includes/{functions,config}.php

# Créer les fichiers de données JSON
touch alfurqan/data/{sermons,events,special_events,quran,reciters,progress}.json

# Créer le fichier JavaScript
touch alfurqan/js/main.js

# Créer les fichiers d'assets (vous devrez ajouter les vraies images plus tard)
touch alfurqan/assets/{logo.png,user-avatar.png,students.png,salah-lesson.jpg}

echo "Structure de fichiers créée avec succès!"