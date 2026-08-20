<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Throwable;

class OpenScadService
{
    private string $binaryPath;

    public function __construct(?string $binaryPath = null)
    {
        $this->binaryPath = $binaryPath ?? '/usr/bin/openscad';
    }

    /**
     * Verifica si el binario de OpenSCAD está instalado y accesible en el servidor.
     */
    public function estaDisponible(): bool
    {
        return file_exists($this->binaryPath) && is_executable($this->binaryPath);
    }

    /**
     * Compila código OpenSCAD a un archivo STL en disco.
     *
     * @param  string  $codigoScad  Contenido del script .scad
     * @param  string  $salidaStlPath  Ruta completa del archivo .stl a generar
     */
    public function compilarSCADaSTL(string $codigoScad, string $salidaStlPath): bool
    {
        if (! $this->estaDisponible()) {
            Log::warning('OpenSCAD Service: el binario de OpenSCAD no está disponible en '.$this->binaryPath);

            return false;
        }

        $tempScadPath = tempnam(sys_get_temp_dir(), 'scad_').'.scad';
        file_put_contents($tempScadPath, $codigoScad);

        try {
            $cmd = sprintf(
                '%s -o %s %s 2>&1',
                escapeshellcmd($this->binaryPath),
                escapeshellarg($salidaStlPath),
                escapeshellarg($tempScadPath)
            );

            exec($cmd, $output, $returnCode);

            if ($returnCode !== 0) {
                Log::error('OpenSCAD Service Error: '.implode("\n", $output));

                return false;
            }

            return file_exists($salidaStlPath) && filesize($salidaStlPath) > 0;
        } catch (Throwable $e) {
            Log::error('OpenSCAD Service Exception: '.$e->getMessage());

            return false;
        } finally {
            if (file_exists($tempScadPath)) {
                unlink($tempScadPath);
            }
        }
    }
}
