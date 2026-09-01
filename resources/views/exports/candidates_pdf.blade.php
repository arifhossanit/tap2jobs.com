<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Candidates</title>
    <style>
        body {
            color: #1f2937;
            font-family: DejaVu Sans, sans-serif;
            font-size: 8px;
        }

        h1 {
            font-size: 18px;
            margin: 0 0 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 4px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f3f4f6;
            font-weight: 700;
        }
    </style>
</head>
<body>
<h1>Candidates</h1>
@include('exports.candidates', ['candidates' => $candidates])
</body>
</html>
