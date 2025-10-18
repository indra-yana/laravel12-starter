<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $metadata['filename'] ?? 'Laporan' }}</title>
    <style>
        @page {
            margin: 45px 40px 80px 40px;
        }

        header {
            position: fixed;
            top: -65px;
            left: 0;
            right: 0;
            height: 60px;
            text-align: center;
            line-height: 1.4;
            font-size: 14px;
        }

        .header-first-page {
            text-align: center;
            font-size: 14px;
        }

        footer {
            position: fixed;
            bottom: -65px;
            left: 0;
            right: 0;
            height: 50px;
            font-size: 12px;
            border-top: 1px solid #aaa;
        }

        footer .page-number:after {
            content: counter(page);
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h1,
        h2,
        h3 {
            text-align: center;
            margin: 0;
        }

        .title {
            margin-top: 10px;
        }

        .main-table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        .main-table th {
            background: #e2e8f0;
            font-weight: bold;
            text-align: center;
        }

        .main-table td {
            font-size: 10px;
        }

        .text-center {
            text-align: center !important;
        }

        .text-left {
            text-align: left !important;
        }

        .text-right {
            text-align: right !important;
        }

        .page-break {
            page-break-after: always;
        }

        .green {
            background-color: #4caf50;
            color: #fff;
        }

        .red {
            background-color: #f44336;
            color: #fff;
        }

        p {
            margin: 0px;
            padding: 0px;
        }
    </style>
</head>

<body>
    {{-- HEADER --}}
    @include('layouts.report-header')

    {{-- KONTEN UTAMA --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('layouts.report-footer')
</body>

</html>