<?php

namespace App\Helpers;

class ColorHelper
{
    /**
     * Verifica se uma cor HEX é escura
     * 
     * @param string $hex Código hexadecimal da cor (ex: #FF0000)
     * @return bool
     */
    public static function isDark($hex)
    {
        // Remove o # do início
        $hex = ltrim($hex, '#');
        
        // Converte hex de 3 caracteres para 6 (ex: #FFF -> #FFFFFF)
        if (strlen($hex) == 3) {
            $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
            $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
            $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        
        // Calcula luminância (fórmula padrão WCAG)
        $luminancia = (0.299 * $r + 0.587 * $g + 0.114 * $b);
        
        // Retorna true se for escura (luminância baixa)
        return $luminancia < 128;
    }
    
    /**
     * Retorna cor de texto apropriada (branco/preto) baseado no fundo
     * 
     * @param string $hex Código hexadecimal da cor de fundo
     * @return string
     */
    public static function getContrastColor($hex)
    {
        return self::isDark($hex) ? '#FFFFFF' : '#000000';
    }
}