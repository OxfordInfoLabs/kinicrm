import { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'uk.oxil.kinicrm',
  appName: 'KiniCRM',
  webDir: 'dist',
  server: {
    androidScheme: 'https'
  }
};

export default config;
