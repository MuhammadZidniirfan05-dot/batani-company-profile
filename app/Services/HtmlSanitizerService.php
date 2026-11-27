<?php

namespace App\Services;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class HtmlSanitizerService
{
    public const MAX_LENGTH = 250000;

    private HtmlSanitizer $sanitizer;

    public function __construct()
    {
        $config = (new HtmlSanitizerConfig())

            // Mengizinkan elemen HTML yang aman.
            ->allowSafeElements()

            // Membatasi protokol tautan.
            ->allowLinkSchemes([
                'http',
                'https',
                'mailto',
                'tel',
            ])

            // Membatasi protokol media.
            ->allowMediaSchemes([
                'http',
                'https',
            ])

            // Mengizinkan URL relatif pada website.
            ->allowRelativeLinks()
            ->allowRelativeMedias()

            // Menghapus elemen berbahaya.
            ->dropElement('script')
            ->dropElement('iframe')
            ->dropElement('object')
            ->dropElement('embed')
            ->dropElement('style')
            ->dropElement('form')

            // Pengamanan tautan.
            ->forceAttribute(
                'a',
                'rel',
                'noopener noreferrer'
            )

            // Batas panjang konten.
            ->withMaxInputLength(self::MAX_LENGTH);

        $this->sanitizer = new HtmlSanitizer($config);
    }

    /**
     * Membersihkan konten HTML.
     */
    public function sanitize(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        if (mb_strlen($html) > self::MAX_LENGTH) {
            throw new \LengthException(
                'Konten HTML melebihi batas panjang yang diizinkan.'
            );
        }

        return $this->sanitizer->sanitize($html);
    }

    /**
     * Mengubah HTML menjadi teks biasa.
     */
    public function plainText(?string $html): string
    {
        if (blank($html)) {
            return '';
        }

        $sanitized = $this->sanitize($html);

        return trim(
            html_entity_decode(
                strip_tags($sanitized ?? ''),
                ENT_QUOTES | ENT_HTML5,
                'UTF-8'
            )
        );
    }
}
