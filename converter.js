import sharp from 'sharp';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const pasta = path.join(__dirname, 'public/img');


fs.readdirSync(pasta).forEach(file => {
  if (file.endsWith('.jpg') || file.endsWith('.png')) {
    const inputPath = path.join(pasta, file);
    const outputPath = path.join(
      pasta,
      file.replace(/\.(jpg|png)$/, '.webp')
    );

    sharp(inputPath)
      .webp({ quality: 80 })
      .toFile(outputPath)
      .then(() => console.log(`Convertido: ${file}`))
      .catch(err => console.error(err));
  }
});