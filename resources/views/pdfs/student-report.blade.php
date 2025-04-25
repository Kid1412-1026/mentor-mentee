<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Report - {{ $student->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            background-color: #f3f4f6;
            padding: 8px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
        .progress-chart {
            margin: 20px 0;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Student Academic Report</h1>
        <p>Generated on {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Personal Information</div>
        <table>
            <tr><th width="30%">Name</th><td>{{ $student->name }}</td></tr>
            <tr><th>Matric No</th><td>{{ $student->matric_no }}</td></tr>
            <tr><th>Program</th><td>{{ $student->program }}</td></tr>
            <tr><th>Email</th><td>{{ $student->email }}</td></tr>
            <tr><th>Intake</th><td>{{ $student->intake }}</td></tr>
            <tr><th>Phone</th><td>{{ $student->phone }}</td></tr>
            <tr><th>State</th><td>{{ $student->state }}</td></tr>
            <tr><th>Address</th><td>{{ $student->address }}</td></tr>
            <tr><th>Motto</th><td>{{ $student->motto }}</td></tr>
            <tr><th>Faculty</th><td>{{ $student->faculty }}</td></tr>
        </table>
    </div>

    <div class="page-break"></div>
    <style>
        @page {
            size: landscape;
        }
    </style>
    <div class="section">
        <div class="section-title">KPI Indexes</div>
        <table>
            <thead>
                <tr>
                    <th>Attributes</th>
                    <th>Semester 1/Year 1</th>
                    <th>Semester 2/Year 1</th>
                    <th>Semester 1/Year 2</th>
                    <th>Semester 2/Year 2</th>
                    <th>Semester 1/Year 3</th>
                    <th>Semester 2/Year 3</th>
                    <th>Semester 1/Year 4</th>
                    <th>Semester 2/Year 4</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>CGPA</td>
                    @for ($year = 1; $year <= 4; $year++)
                        @for ($sem = 1; $sem <= 2; $sem++)
                            <td>
                                @php
                                    $kpi = $student->kpiIndexes->where('year', $year)->where('sem', $sem)->first();
                                @endphp
                                {{ $kpi ? number_format($kpi->cgpa, 2) : '-' }}
                            </td>
                        @endfor
                    @endfor
                </tr>
                <tr>
                    <td>Faculty Activities</td>
                    @for ($year = 1; $year <= 4; $year++)
                        @for ($sem = 1; $sem <= 2; $sem++)
                            <td>
                                @php
                                    $kpi = $student->kpiIndexes->where('year', $year)->where('sem', $sem)->first();
                                @endphp
                                {{ $kpi ? $kpi->faculty_activity : '-' }}
                            </td>
                        @endfor
                    @endfor
                </tr>
                <tr>
                    <td>University Activities</td>
                    @for ($year = 1; $year <= 4; $year++)
                        @for ($sem = 1; $sem <= 2; $sem++)
                            <td>
                                @php
                                    $kpi = $student->kpiIndexes->where('year', $year)->where('sem', $sem)->first();
                                @endphp
                                {{ $kpi ? $kpi->university_activity : '-' }}
                            </td>
                        @endfor
                    @endfor
                </tr>
                <tr>
                    <td>National Activities</td>
                    @for ($year = 1; $year <= 4; $year++)
                        @for ($sem = 1; $sem <= 2; $sem++)
                            <td>
                                @php
                                    $kpi = $student->kpiIndexes->where('year', $year)->where('sem', $sem)->first();
                                @endphp
                                {{ $kpi ? $kpi->national_activity : '-' }}
                            </td>
                        @endfor
                    @endfor
                </tr>
                <tr>
                    <td>International Activities</td>
                    @for ($year = 1; $year <= 4; $year++)
                        @for ($sem = 1; $sem <= 2; $sem++)
                            <td>
                                @php
                                    $kpi = $student->kpiIndexes->where('year', $year)->where('sem', $sem)->first();
                                @endphp
                                {{ $kpi ? $kpi->international_activity : '-' }}
                            </td>
                        @endfor
                    @endfor
                </tr>
                <tr>
                    <td>Faculty Competitions</td>
                    @for ($year = 1; $year <= 4; $year++)
                        @for ($sem = 1; $sem <= 2; $sem++)
                            <td>
                                @php
                                    $kpi = $student->kpiIndexes->where('year', $year)->where('sem', $sem)->first();
                                @endphp
                                {{ $kpi ? $kpi->faculty_competition : '-' }}
                            </td>
                        @endfor
                    @endfor
                </tr>
                <tr>
                    <td>University Competitions</td>
                    @for ($year = 1; $year <= 4; $year++)
                        @for ($sem = 1; $sem <= 2; $sem++)
                            <td>
                                @php
                                    $kpi = $student->kpiIndexes->where('year', $year)->where('sem', $sem)->first();
                                @endphp
                                {{ $kpi ? $kpi->university_competition : '-' }}
                            </td>
                        @endfor
                    @endfor
                </tr>
                <tr>
                    <td>National Competitions</td>
                    @for ($year = 1; $year <= 4; $year++)
                        @for ($sem = 1; $sem <= 2; $sem++)
                            <td>
                                @php
                                    $kpi = $student->kpiIndexes->where('year', $year)->where('sem', $sem)->first();
                                @endphp
                                {{ $kpi ? $kpi->national_competition : '-' }}
                            </td>
                        @endfor
                    @endfor
                </tr>
                <tr>
                    <td>International Competitions</td>
                    @for ($year = 1; $year <= 4; $year++)
                        @for ($sem = 1; $sem <= 2; $sem++)
                            <td>
                                @php
                                    $kpi = $student->kpiIndexes->where('year', $year)->where('sem', $sem)->first();
                                @endphp
                                {{ $kpi ? $kpi->international_competition : '-' }}
                            </td>
                        @endfor
                    @endfor
                </tr>
                <tr>
                    <td>Leadership</td>
                    @for ($year = 1; $year <= 4; $year++)
                        @for ($sem = 1; $sem <= 2; $sem++)
                            <td>
                                @php
                                    $kpi = $student->kpiIndexes->where('year', $year)->where('sem', $sem)->first();
                                @endphp
                                {{ $kpi ? $kpi->leadership : '-' }}
                            </td>
                        @endfor
                    @endfor
                </tr>
                <tr>
                    <td>Graduate on Time</td>
                    @for ($year = 1; $year <= 4; $year++)
                        @for ($sem = 1; $sem <= 2; $sem++)
                            <td>
                                @php
                                    $kpi = $student->kpiIndexes->where('year', $year)->where('sem', $sem)->first();
                                @endphp
                                {{ $kpi ? $kpi->graduate_on_time : '-' }}
                            </td>
                        @endfor
                    @endfor
                </tr>
                <tr>
                    <td>Professional Certification</td>
                    @for ($year = 1; $year <= 4; $year++)
                        @for ($sem = 1; $sem <= 2; $sem++)
                            <td>
                                @php
                                    $kpi = $student->kpiIndexes->where('year', $year)->where('sem', $sem)->first();
                                @endphp
                                {{ $kpi ? $kpi->professional_certification : '-' }}
                            </td>
                        @endfor
                    @endfor
                </tr>
                <tr>
                    <td>Employability</td>
                    @for ($year = 1; $year <= 4; $year++)
                        @for ($sem = 1; $sem <= 2; $sem++)
                            <td>
                                @php
                                    $kpi = $student->kpiIndexes->where('year', $year)->where('sem', $sem)->first();
                                @endphp
                                {{ $kpi ? $kpi->employability : '-' }}
                            </td>
                        @endfor
                    @endfor
                </tr>
                <tr>
                    <td>Mobility Program</td>
                    @for ($year = 1; $year <= 4; $year++)
                        @for ($sem = 1; $sem <= 2; $sem++)
                            <td>
                                @php
                                    $kpi = $student->kpiIndexes->where('year', $year)->where('sem', $sem)->first();
                                @endphp
                                {{ $kpi ? $kpi->mobility_program : '-' }}
                            </td>
                        @endfor
                    @endfor
                </tr>
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>
    <div class="section">
        <div class="section-title">Activities</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Semester</th>
                    <th>Year</th>
                    <th>Remark</th>
                    <th>Uploads</th>
                </tr>
            </thead>
            <tbody>
                @foreach($student->activities as $index => $activity)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $activity->name }}</td>
                    <td>{{ ucfirst($activity->type) }}</td>
                    <td>{{ $activity->sem }}</td>
                    <td>{{ $activity->year }}</td>
                    <td>{{ $activity->remark }}</td>
                    <td>{{ $activity->uploads }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>
    <div class="section">
        <div class="section-title">Challenges</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Semester</th>
                    <th>Year</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
                @foreach($student->challenges as $index => $challenge)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $challenge->name }}</td>
                    <td>{{ ucfirst($challenge->type) }}</td>
                    <td>{{ $challenge->sem }}</td>
                    <td>{{ $challenge->year }}</td>
                    <td>{{ $challenge->remark }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>
    <div class="section">
        <div class="section-title">Courses and Enrolments</div>
        <table>
            <thead>
                <tr>
                    <th>Course Code</th>
                    <th>Course Name</th>
                    <th>Credit Hour</th>
                    <th>Section</th>
                    <th>Faculty</th>
                    <th>Semester</th>
                    <th>Year</th>
                    <th>Pointer</th>
                    <th>Grade</th>
                    <th>Rating</th>
                </tr>
            </thead>
            <tbody>
                @foreach($student->enrolments as $enrolment)
                <tr>
                    <td>{{ $enrolment->course->code }}</td>
                    <td>{{ $enrolment->course->name }}</td>
                    <td>{{ $enrolment->course->credit_hour }}</td>
                    <td>{{ $enrolment->course->section }}</td>
                    <td>{{ $enrolment->course->faculty }}</td>
                    <td>{{ $enrolment->sem }}</td>
                    <td>{{ $enrolment->year }}</td>
                    <td>{{ number_format($enrolment->pointer, 2) }}</td>
                    <td>{{ $enrolment->grade }}</td>
                    <td>{{ $enrolment->rating }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>





