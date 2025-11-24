#!/bin/bash

# Copier les icônes dans les dossiers Android appropriés
cp public/icons/icon-72x72.png android/app/src/main/res/mipmap-hdpi/ic_launcher.png
cp public/icons/icon-72x72.png android/app/src/main/res/mipmap-hdpi/ic_launcher_round.png

cp public/icons/icon-96x96.png android/app/src/main/res/mipmap-xhdpi/ic_launcher.png
cp public/icons/icon-96x96.png android/app/src/main/res/mipmap-xhdpi/ic_launcher_round.png

cp public/icons/icon-144x144.png android/app/src/main/res/mipmap-xxhdpi/ic_launcher.png
cp public/icons/icon-144x144.png android/app/src/main/res/mipmap-xxhdpi/ic_launcher_round.png

cp public/icons/icon-192x192.png android/app/src/main/res/mipmap-xxxhdpi/ic_launcher.png
cp public/icons/icon-192x192.png android/app/src/main/res/mipmap-xxxhdpi/ic_launcher_round.png

# mdpi (48x48 - on utilise 72x72 redimensionné)
cp public/icons/icon-72x72.png android/app/src/main/res/mipmap-mdpi/ic_launcher.png
cp public/icons/icon-72x72.png android/app/src/main/res/mipmap-mdpi/ic_launcher_round.png

echo "✅ Icônes Android copiées avec succès!"
