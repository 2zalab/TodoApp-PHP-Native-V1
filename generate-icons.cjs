const sharp = require('sharp');
const fs = require('fs');
const path = require('path');

const sizes = [72, 96, 128, 144, 152, 192, 384, 512];
const inputSvg = path.join(__dirname, 'public/icons/icon.svg');
const outputDir = path.join(__dirname, 'public/icons');

// S'assurer que le dossier existe
if (!fs.existsSync(outputDir)) {
  fs.mkdirSync(outputDir, { recursive: true });
}

console.log('Génération des icônes PNG...\n');

// Générer les icônes pour chaque taille
Promise.all(
  sizes.map(async (size) => {
    const outputPath = path.join(outputDir, `icon-${size}x${size}.png`);

    try {
      await sharp(inputSvg)
        .resize(size, size)
        .png()
        .toFile(outputPath);

      console.log(`✓ Généré: icon-${size}x${size}.png`);
    } catch (error) {
      console.error(`✗ Erreur pour icon-${size}x${size}.png:`, error.message);
    }
  })
).then(() => {
  console.log('\n✅ Toutes les icônes ont été générées avec succès !');
}).catch((error) => {
  console.error('\n❌ Erreur lors de la génération des icônes:', error);
});
