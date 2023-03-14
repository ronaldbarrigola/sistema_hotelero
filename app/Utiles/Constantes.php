<?php
namespace App\Utiles;
class Constantes
{
    const ETAPA_POR_ENVIAR = 1; 
    const ETAPA_EN_REVISION = 2;
    const ETAPA_RECHAZADO = 3;
    const ETAPA_APROBADO = 4;
    const ETAPA_ANULADO = 5;
    const ETAPA_FINALIZADO = 6;
    const TAM_MAX_IMAGEN_KB=2048; // Tamaño limite de imagenes a subir al servidor

    public static function getEtapaPorEnviar(){
        return self::ETAPA_POR_ENVIAR;
    }

    public static function getEtapaEnRevision(){
        return self::ETAPA_EN_REVISION;
    }

    public static function getEtapaRechazado(){
        return self::ETAPA_RECHAZADO;
    }

    public static function getEtapaAprobado(){
        return self::ETAPA_APROBADO;
    }

    public static function getEtapaAnulado(){
        return self::ETAPA_ANULADO;
    }

    public static function getEtapaFinalizado(){
        return self::ETAPA_FINALIZADO;
    }

    
    public static function getTamMaxImagenKB(){
        return self::TAM_MAX_IMAGEN_KB;
    }
    
} 