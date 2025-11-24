# 📱 Guide de Build APK Android avec Capacitor

## ✅ Configuration Capacitor terminée !

Votre application TodoList est maintenant prête à être compilée en APK Android avec **Capacitor**.

---

## 📦 Fichiers Capacitor créés

```
TodoApp-PHP-Native-V1/
├── capacitor.config.ts        # Configuration Capacitor
├── android/                   # Projet Android Studio
│   ├── app/
│   │   ├── src/main/
│   │   │   ├── assets/public/ # Assets web copiés
│   │   │   └── res/
│   │   │       └── mipmap-*/  # Icônes Android
│   │   └── build.gradle
│   ├── gradle/
│   └── build.gradle
├── public/index.html          # Point d'entrée web
└── copy-android-icons.sh      # Script de copie d'icônes
```

---

## 🚀 Option 1 : Build APK sans Android Studio (Ligne de commande)

### Prérequis
```bash
# 1. Installer Java JDK 17
sudo apt install openjdk-17-jdk

# 2. Installer Android SDK Command Line Tools
# Télécharger depuis: https://developer.android.com/studio#command-tools
# Extraire dans: ~/Android/cmdline-tools/latest/

# 3. Configurer les variables d'environnement
export ANDROID_HOME=$HOME/Android
export PATH=$PATH:$ANDROID_HOME/cmdline-tools/latest/bin
export PATH=$PATH:$ANDROID_HOME/platform-tools
export JAVA_HOME=/usr/lib/jvm/java-17-openjdk-amd64

# 4. Accepter les licences
sdkmanager --licenses

# 5. Installer les packages nécessaires
sdkmanager "platform-tools" "platforms;android-34" "build-tools;34.0.0"
```

### Build de l'APK
```bash
# 1. Synchroniser les assets web vers Android
npx cap sync android

# 2. Aller dans le dossier android
cd android

# 3. Build APK debug
./gradlew assembleDebug

# 4. L'APK est généré ici:
# android/app/build/outputs/apk/debug/app-debug.apk
```

### Build APK signé (Production)
```bash
# 1. Créer une clé de signature (une fois seulement)
keytool -genkey -v -keystore todolist.keystore \
  -alias todolist \
  -keyalg RSA \
  -keysize 2048 \
  -validity 10000

# 2. Créer android/key.properties
cat > android/key.properties << EOF
storePassword=VotreMotDePasse
keyPassword=VotreMotDePasse
keyAlias=todolist
storeFile=../todolist.keystore
EOF

# 3. Modifier android/app/build.gradle (voir section Configuration ci-dessous)

# 4. Build APK release
cd android
./gradlew assembleRelease

# 5. APK signé généré:
# android/app/build/outputs/apk/release/app-release.apk
```

---

## 🖥️ Option 2 : Build APK avec Android Studio (Recommandé)

### Installation
1. Télécharger **Android Studio** : https://developer.android.com/studio
2. Installer et lancer Android Studio
3. Installer les SDK nécessaires (Android 34)

### Ouvrir le projet
```bash
# Ouvrir le projet Android dans Android Studio
npx cap open android
```

### Build dans Android Studio
1. Menu **Build** → **Build Bundle(s) / APK(s)** → **Build APK(s)**
2. L'APK sera dans : `android/app/build/outputs/apk/debug/`
3. Pour un APK signé (production) :
   - Menu **Build** → **Generate Signed Bundle / APK**
   - Suivre l'assistant pour créer/utiliser une clé de signature
   - Sélectionner **APK** (pas AAB pour l'instant)
   - Choisir **release**

---

## ⚙️ Configuration pour APK signé (Production)

### 1. Modifier `android/app/build.gradle`

Ajouter avant `android {` :

```gradle
def keystorePropertiesFile = rootProject.file("key.properties")
def keystoreProperties = new Properties()
if (keystorePropertiesFile.exists()) {
    keystoreProperties.load(new FileInputStream(keystorePropertiesFile))
}
```

Dans la section `android { }`, ajouter après `buildTypes` :

```gradle
signingConfigs {
    release {
        if (keystorePropertiesFile.exists()) {
            keyAlias keystoreProperties['keyAlias']
            keyPassword keystoreProperties['keyPassword']
            storeFile file(keystoreProperties['storeFile'])
            storePassword keystoreProperties['storePassword']
        }
    }
}

buildTypes {
    release {
        signingConfig signingConfigs.release
        minifyEnabled true
        proguardFiles getDefaultProguardFile('proguard-android.txt'), 'proguard-rules.pro'
    }
}
```

---

## 🔄 Workflow de développement

### 1. Modifier le code web
```bash
# Éditez vos fichiers dans public/, resources/, etc.
```

### 2. Synchroniser avec Android
```bash
npx cap sync android
```

### 3. Tester sur émulateur/appareil
```bash
npx cap run android
```

### 4. Build APK
```bash
cd android
./gradlew assembleDebug  # ou assembleRelease
```

---

## 📡 Mode Serveur (Pour développement avec API Laravel)

Si vous voulez que l'app charge le contenu depuis un serveur Laravel distant :

### 1. Modifier `capacitor.config.ts`
```typescript
server: {
  url: 'https://votre-domaine.com',  // Votre serveur Laravel
  cleartext: false  // true pour HTTP, false pour HTTPS
}
```

### 2. Rebuild et synchroniser
```bash
npx cap sync android
```

### 3. Pour développement local
```bash
# Démarrer le serveur Laravel
php artisan serve --host=0.0.0.0 --port=8000

# Dans capacitor.config.ts, utiliser:
# Android émulateur: http://10.0.2.2:8000
# Appareil physique: http://VOTRE_IP_LOCAL:8000
```

---

## 📱 Installer l'APK sur Android

### Via USB (ADB)
```bash
# Activer le mode développeur sur Android
# Activer le débogage USB

# Installer l'APK
adb install android/app/build/outputs/apk/debug/app-debug.apk
```

### Via fichier direct
1. Copier l'APK sur le téléphone
2. Ouvrir le fichier APK
3. Autoriser l'installation depuis des sources inconnues
4. Installer

---

## 🏪 Publier sur Google Play Store

### 1. Créer un compte développeur
- Inscription : https://play.google.com/console
- Frais uniques : 25 USD

### 2. Générer un AAB (Android App Bundle)
```bash
cd android
./gradlew bundleRelease

# AAB généré dans:
# android/app/build/outputs/bundle/release/app-release.aab
```

### 3. Uploader sur Google Play Console
1. Créer une nouvelle application
2. Remplir les informations (description, captures d'écran, etc.)
3. Uploader l'AAB dans "Production" ou "Test interne"
4. Soumettre pour révision

---

## 🔧 Dépannage

### Erreur "SDK not found"
```bash
export ANDROID_HOME=$HOME/Android
export PATH=$PATH:$ANDROID_HOME/cmdline-tools/latest/bin
```

### Erreur "JAVA_HOME not set"
```bash
export JAVA_HOME=/usr/lib/jvm/java-17-openjdk-amd64
```

### APK non signé ne s'installe pas
```bash
# Utiliser le mode debug ou signer l'APK
./gradlew assembleDebug
```

### L'app ne se connecte pas à l'API
- Vérifier que `server.url` dans `capacitor.config.ts` est correct
- Pour HTTPS, le certificat doit être valide
- Pour HTTP, définir `cleartext: true` dans la config

---

## 📊 Tailles d'APK

- **Debug APK** : ~15-20 MB (inclut les symboles de débogage)
- **Release APK** : ~8-12 MB (optimisé, minifié)
- **Release AAB** : ~6-8 MB (Google Play optimise davantage)

---

## ✨ Fonctionnalités supplémentaires

### Ajouter des plugins Capacitor

```bash
# Exemple: Appareil photo
npm install @capacitor/camera
npx cap sync android

# Dans votre code JS/TS:
import { Camera } from '@capacitor/camera';
const photo = await Camera.getPhoto({ ... });
```

### Plugins disponibles
- `@capacitor/camera` : Accès caméra
- `@capacitor/filesystem` : Fichiers
- `@capacitor/geolocation` : GPS
- `@capacitor/push-notifications` : Notifications push
- `@capacitor/share` : Partage
- Et bien d'autres...

---

## 🎯 Commandes utiles

```bash
# Synchroniser les changements web
npx cap sync android

# Ouvrir dans Android Studio
npx cap open android

# Lancer sur appareil/émulateur
npx cap run android

# Build debug
cd android && ./gradlew assembleDebug

# Build release
cd android && ./gradlew assembleRelease

# Nettoyer le build
cd android && ./gradlew clean

# Lister les appareils connectés
adb devices

# Installer l'APK
adb install chemin/vers/app.apk
```

---

## 📚 Ressources

- [Documentation Capacitor](https://capacitorjs.com/docs)
- [Guide Android Studio](https://developer.android.com/studio/intro)
- [Guide Google Play](https://play.google.com/console/about/guides/releasewithconfidence/)
- [Capacitor Plugins](https://capacitorjs.com/docs/plugins)

---

## ✅ Félicitations !

Vous pouvez maintenant :
- ✅ Builder un APK Android
- ✅ Installer sur n'importe quel appareil
- ✅ Publier sur le Play Store
- ✅ Ajouter des fonctionnalités natives

**Votre TodoList est maintenant une vraie app Android ! 🎉**
