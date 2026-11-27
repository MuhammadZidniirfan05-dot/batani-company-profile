@php

    $messages = [

        403 => [
            'title' => 'Akses Ditolak',
            'description' => 'Anda tidak memiliki izin untuk mengakses halaman ini.',
        ],

        404 => [
            'title' => 'Halaman Tidak Ditemukan',
            'description' => 'Halaman atau konten yang Anda cari tidak tersedia.',
        ],

        419 => [
            'title' => 'Sesi Telah Berakhir',
            'description' => 'Silakan kembali ke halaman sebelumnya dan coba lagi.',
        ],

        429 => [
            'title' => 'Terlalu Banyak Permintaan',
            'description' => 'Silakan tunggu beberapa saat sebelum mencoba kembali.',
        ],

        500 => [
            'title' => 'Terjadi Kesalahan Server',
            'description' => 'Terjadi gangguan pada sistem. Silakan coba kembali nanti.',
        ],

        503 => [
            'title' => 'Layanan Tidak Tersedia',
            'description' => 'Layanan sedang tidak tersedia. Silakan coba kembali nanti.',
        ],

    ];

    $error = $messages[$status] ?? [
        'title' => 'Terjadi Kesalahan',
        'description' => 'Silakan coba kembali dalam beberapa saat.',
    ];

@endphp

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="robots"
        content="noindex, nofollow"
    >

    <title>
        {{ $status }} - {{ $error['title'] }} | BATANI
    </title>

    <style>

        body {
            margin: 0;
            padding: 24px;
            min-height: 100vh;
            box-sizing: border-box;
            display: grid;
            place-items: center;
            font-family: Arial, sans-serif;
            background: #f8fafc;
            color: #0f172a;
        }

        main {
            max-width: 600px;
            text-align: center;
        }

        .brand {
            font-size: 20px;
            font-weight: bold;
        }

        .status {
            font-size: 80px;
            margin: 20px 0;
        }

        h2 {
            font-size: 26px;
        }

        p {
            color: #64748b;
            line-height: 1.7;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 14px 24px;
            border-radius: 6px;
            background: #0f172a;
            color: white;
            text-decoration: none;
        }

    </style>

</head>

<body>

    <main>

        <div class="brand">
            BATANI
        </div>

        <h1 class="status">
            {{ $status }}
        </h1>

        <h2>
            {{ $error['title'] }}
        </h2>

        <p>
            {{ $error['description'] }}
        </p>

        <a href="/">
            Kembali ke Beranda
        </a>

    </main>

</body>

</html>
