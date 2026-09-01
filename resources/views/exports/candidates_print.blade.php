<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Candidates</title>
    <style>
        body {
            color: #1f2937;
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 24px;
        }

        h1 {
            font-size: 22px;
            margin: 0 0 16px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 7px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f3f4f6;
        }

        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>
<body>
<h1>Candidates</h1>
@include('exports.candidates', ['candidates' => $candidates])
<script>
    window.addEventListener('load', function () {
        window.print();
    });
</script>
</body>
</html>
