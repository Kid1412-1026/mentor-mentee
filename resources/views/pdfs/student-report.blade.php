<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Student Report</h1>
        <p>Generated on {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Student Information</div>
        <table>
            <tr>
                <th>Name</th>
                <td>{{ $student->name }}</td>
            </tr>
            <tr>
                <th>Matric No</th>
                <td>{{ $student->matric_no }}</td>
            </tr>
            <tr>
                <th>Program</th>
                <td>{{ $student->program }}</td>
            </tr>
            <tr>
                <th>Intake</th>
                <td>{{ $student->intake }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Academic Performance</div>
        <table>
            <tr>
                <th>CGPA</th>
                <td>{{ $student->result?->cgpa ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Professional Certifications</th>
                <td>{{ $student->result?->professional_certification ?? 0 }}</td>
            </tr>
            <tr>
                <th>Leadership Achievements</th>
                <td>{{ $student->result?->leadership_competition ?? 0 }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Activities Summary</div>
        <table>
            <tr>
                <th>Type</th>
                <th>Count</th>
            </tr>
            <tr>
                <td>Faculty Level</td>
                <td>{{ $student->activities->where('type', 'faculty')->count() }}</td>
            </tr>
            <tr>
                <td>University Level</td>
                <td>{{ $student->activities->where('type', 'university')->count() }}</td>
            </tr>
            <tr>
                <td>National Level</td>
                <td>{{ $student->activities->where('type', 'national')->count() }}</td>
            </tr>
        </table>
    </div>
</body>
</html>