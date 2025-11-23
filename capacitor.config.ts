import { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'com.todolist.mobile',
  appName: 'TodoList Mobile',
  webDir: 'public',
  server: {
    androidScheme: 'https',
    // Pour développement local, décommentez et ajustez l'URL:
    // url: 'http://10.0.2.2:8000',
    // cleartext: true
  },
  android: {
    buildOptions: {
      keystorePath: undefined,
      keystorePassword: undefined,
      keystoreAlias: undefined,
      keystoreAliasPassword: undefined,
      releaseType: 'APK'
    }
  }
};

export default config;
