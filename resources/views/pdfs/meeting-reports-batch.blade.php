<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Meeting Reports Batch</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .section-content {
            margin-bottom: 15px;
            font-size: 12px;
        }

        .metadata {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 5px;
            background-color: #f9f9f9;
        }

        .metadata-item {
            margin-bottom: 5px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .student-list {
            margin-top: 20px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    @foreach($meetings as $meeting)
        <div class="header">
            <h1>Meeting Report</h1>
            <p>Generated on {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>

        <div class="metadata">
            <table style="border: none; width: 100%;">
                <tr>
                    <td style="border: none; width: 70%;">
                        <div class="metadata-item">
                            <strong>Mentor:</strong> {{ auth()->user()->name }}
                        </div>
                        <div class="metadata-item">
                            <strong>Program:</strong> {{ \App\Models\Student::where('intake', $meeting->batch)->first()?->program ?? 'N/A' }}
                        </div>
                        <div class="metadata-item">
                            <strong>Semester:</strong> {{ $meeting->sem }}
                        </div>
                        <div class="metadata-item">
                            <strong>Year:</strong> {{ $meeting->year }}
                        </div>
                    </td>
                    <td style="border: none; width: 30%;">
                        <div class="metadata-item">
                            <strong>Batch:</strong> {{ $meeting->batch }}
                        </div>
                        <div class="metadata-item">
                            <strong>Session Date:</strong> {{ $meeting->session_date->format('d/m/Y') }}
                        </div>
                        <div class="metadata-item">
                            <strong>Method:</strong> {{ ucfirst($meeting->method) }}
                        </div>
                        <div class="metadata-item">
                            <strong>Duration:</strong> {{ $meeting->duration }} minutes
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="student-list">
            <div class="section-title">Attendees</div>
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Program</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\App\Models\Student::where('intake', $meeting->batch)->get() as $student)
                        <tr>
                            <td>{{ $student->matric_no }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->program }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Meeting Agenda</div>
            <div class="section-content">{{ $meeting->agenda }}</div>
        </div>

        <div class="section">
            <div class="section-title">Discussion on Academic Related Matters/Issues
                <div style="font-size: 10px; font-weight: normal; margin-top: 2px;">(i.e. – Academic Progress has been achieved by the mentee)</div>
            </div>
            <div class="section-content">{{ $meeting->discussion }}</div>
        </div>

        <div class="section">
            <div class="section-title">Actions: List any actions agreed from this meeting and who will carry them out</div>
            <div class="section-content">{{ $meeting->action }}</div>
        </div>

        @if($meeting->remarks)
            <div class="section">
                <div class="section-title">Do you have any concerns (E.g. wellbeing or safeguarding) or further comments?</div>
                <div class="section-content">{{ $meeting->remarks }}</div>
            </div>
        @endif

        <div class="footer">
            This is a computer-generated document. No signature is required.
        </div>

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>

