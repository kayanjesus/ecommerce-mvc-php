<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Exception;
use Illuminate\Support\Facades\Log;

class ImageService
{
    protected $imageManager;
    protected $quality;
    protected $maxWidth;
    protected $maxHeight;

    public function __construct()
    {
        // Usando Intervention Image com driver GD
        $this->imageManager = new ImageManager(new Driver());
        
        // Configurações (podem vir do .env)
        $this->quality = (int) env('WEBP_QUALITY', 80);
        $this->maxWidth = (int) env('IMAGE_MAX_WIDTH', 1920);
        $this->maxHeight = (int) env('IMAGE_MAX_HEIGHT', 1080);
    }

    /**
     * Converte uma imagem para WebP e salva no storage
     */
    public function convertToWebP(UploadedFile $file, string $path = 'produtos'): array
    {
        try {
            // Gerar nome único para o arquivo
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $slug = Str::slug($originalName);
            $uniqueId = uniqid();
            $timestamp = time();
            
            // Nome do arquivo WebP
            $webpFilename = "{$slug}-{$timestamp}-{$uniqueId}.webp";
            $webpPath = "{$path}/{$webpFilename}";
            
            // Carregar a imagem
            $image = $this->imageManager->read($file->getRealPath());
            
            // Redimensionar se necessário (mantendo proporção)
            if ($image->width() > $this->maxWidth || $image->height() > $this->maxHeight) {
                $image->scaleDown($this->maxWidth, $this->maxHeight);
            }
            
            // Converter para WebP e salvar
            $encoded = $image->toWebp($this->quality);
            
            // Salvar no storage público
            Storage::disk('public')->put($webpPath, $encoded);
            
            // Obter informações da imagem
            $webpSize = Storage::disk('public')->size($webpPath);
            $originalSize = $file->getSize();
            
            Log::info('Imagem convertida para WebP com sucesso', [
                'original' => $file->getClientOriginalName(),
                'original_size' => $this->formatBytes($originalSize),
                'webp' => $webpFilename,
                'webp_size' => $this->formatBytes($webpSize),
                'reduction' => round((1 - $webpSize / $originalSize) * 100, 1) . '%'
            ]);
            
            return [
                'success' => true,
                'webp_path' => 'storage/' . $webpPath,
                'filename' => $webpFilename,
                'size' => $webpSize,
                'original_size' => $originalSize
            ];
            
        } catch (Exception $e) {
            Log::error('Erro ao converter imagem para WebP', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Fallback: salvar a imagem original
            try {
                $fallbackPath = $file->store($path, 'public');
                
                return [
                    'success' => false,
                    'fallback_path' => 'storage/' . $fallbackPath,
                    'filename' => basename($fallbackPath),
                    'error' => $e->getMessage()
                ];
            } catch (Exception $fallbackError) {
                Log::error('Erro no fallback de imagem', [
                    'error' => $fallbackError->getMessage()
                ]);
                
                return [
                    'success' => false,
                    'error' => 'Falha completa no upload: ' . $e->getMessage()
                ];
            }
        }
    }

    /**
     * Converte múltiplas imagens para WebP
     */
    public function convertMultipleToWebP(array $files, string $path = 'produtos'): array
    {
        $results = [];
        
        foreach ($files as $index => $file) {
            if ($file instanceof UploadedFile) {
                $result = $this->convertToWebP($file, $path);
                $result['index'] = $index;
                $results[] = $result;
            }
        }
        
        return $results;
    }

    /**
     * Formata bytes para formato legível
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
    }
}