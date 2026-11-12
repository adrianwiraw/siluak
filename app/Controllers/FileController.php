<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;

class FileController extends BaseController
{
    public function show(string $path)
    {
        $relativePath = str_replace('\\', '/', rawurldecode($path));
        if (! str_starts_with($relativePath, 'uploads/')
            || str_contains($relativePath, "\0")
            || preg_match('~(?:^|/)\.\.(?:/|$)~', $relativePath) === 1) {
            throw PageNotFoundException::forPageNotFound();
        }

        $uploadRoot = realpath(WRITEPATH . 'uploads');
        $fullPath = realpath(WRITEPATH . $relativePath);
        if ($uploadRoot === false || $fullPath === false
            || ! str_starts_with($fullPath, $uploadRoot . DIRECTORY_SEPARATOR)
            || ! is_file($fullPath)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $content = file_get_contents($fullPath);
        if ($content === false) {
            throw new \RuntimeException('Tidak dapat membaca file yang diminta.');
        }

        $mime = mime_content_type($fullPath) ?: 'application/octet-stream';
        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Disposition', str_starts_with($mime, 'image/') ? 'inline' : 'attachment')
            ->setBody($content);
    }
}
