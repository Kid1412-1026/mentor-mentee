<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Reports Batch</title>
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
        .student-section {
            margin-bottom: 40px;
            page-break-after: always;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Student Reports Batch</h1>
        <p>Generated on {{ now()->format('d/m/Y') }}</p>
        <p>Total Students: {{ $students->count() }}</p>
    </div>

    @foreach($students as $student)
    <div class="student-section">
        <h2>{{ $student->name }} ({{ $student->matric_no }})</h2>

        <div class="section">
            <div class="section-title">Personal Information</div>
            <table>
                <tr>
                    <th width="30%">Program</th>
                    <td>{{ $student->program }}</td>
                </tr>
                <tr>
                    <th>Intake</th>
                    <td>{{ $student->intake }}</td>
                </tr>
                <tr>
                    <th>Current CGPA</th>
                    <td>{{ $student->kpiIndexes->last()?->cgpa ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Activities Summary</div>
            <table>
                <tr>
                    <th>Total Activities</th>
                    <td>{{ $student->activities->count() }}</td>
                </tr>
                <tr>
                    <th>Faculty Level</th>
                    <td>{{ $student->activities->where('type', 'faculty')->count() }}</td>
                </tr>
                <tr>
                    <th>University Level</th>
                    <td>{{ $student->activities->where('type', 'university')->count() }}</td>
                </tr>
                <tr>
                    <th>National Level</th>
                    <td>{{ $student->activities->where('type', 'national')->count() }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Academic Progress</div>

            @php
                $enrolledCourseIds = $student->enrolments->pluck('course.id')->toArray();
                $totalQualityPoints = $student->enrolments->sum(function($enrollment) {
                    return $enrollment->course->credit_hour * $enrollment->pointer;
                });
                $totalCreditsWithGrade = $student->enrolments->sum('course.credit_hour');
                $cgpa = $totalCreditsWithGrade > 0 ? number_format($totalQualityPoints / $totalCreditsWithGrade, 2) : '0.00';
            @endphp

            <h4 style="margin-top: 15px; margin-bottom: 10px;">Completed Courses</h4>
            <table>
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Credit Hour</th>
                        <th>Grade</th>
                        <th>Rating</th>
                        <th>Semester/Year</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($student->enrolments as $enrolment)
                    <tr>
                        <td>{{ $enrolment->course->code }} - {{ $enrolment->course->name }}</td>
                        <td>{{ $enrolment->course->credit_hour }}</td>
                        <td>{{ $enrolment->grade }}</td>
                        <td>{{ $enrolment->rating }}/5</td>
                        <td>{{ $enrolment->sem }}/{{ $enrolment->year }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <h4 style="margin-top: 15px; margin-bottom: 10px;">Credits by Section</h4>
            <table>
                <thead>
                    <tr>
                        <th>Section</th>
                        <th>Credits Completed</th>
                        <th>Credits Required</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(['Faculty Core', 'Programme Core', 'Elective', 'University Core', 'Co-curriculum', 'Language', 'Industrial Training'] as $section)
                        @php
                            $sectionCredits = $student->enrolments
                                ->where('course.section', $section)
                                ->sum('course.credit_hour');

                            $requiredCredits = $student->courseStructure
                                ->where('course.section', $section)
                                ->sum('course.credit_hour');

                            $isDeficit = $sectionCredits < $requiredCredits;
                        @endphp
                        <tr>
                            <td>{{ $section }}</td>
                            <td style="{{ $isDeficit ? 'color: #EF4444;' : '' }}">{{ $sectionCredits }}</td>
                            <td>{{ $requiredCredits }}</td>
                        </tr>
                    @endforeach
                    @php
                        $totalCredits = $student->courseStructure->sum('course.credit_hour');
                        $completedCredits = $student->enrolments->sum('course.credit_hour');
                        $isTotalDeficit = $completedCredits < $totalCredits;
                    @endphp
                    <tr style="font-weight: bold; background-color: #f8f9fa;">
                        <td>Credits Remaining: {{ $totalCredits - $completedCredits }}</td>
                        <td colspan="2">Credits Completed: {{ $completedCredits }}</td>
                    </tr>
                    <tr style="font-weight: bold; background-color: #f8f9fa;">
                        <td>Total Credits Required: {{ $totalCredits }}</td>
                        <td colspan="2">Completion: {{ round(($completedCredits / $totalCredits) * 100, 1) }}%</td>
                    </tr>
                </tbody>
            </table>

            <h4 style="margin-top: 15px; margin-bottom: 10px;">Remaining Required Courses</h4>
            <table>
                <thead>
                    <tr>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Credit Hours</th>
                        <th>Section</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($student->courseStructure->filter(function($rule) use ($enrolledCourseIds) {
                        return !in_array($rule->course->id, $enrolledCourseIds);
                    }) as $rule)
                    <tr>
                        <td>{{ $rule->course->code }}</td>
                        <td>{{ $rule->course->name }}</td>
                        <td>{{ $rule->course->credit_hour }}</td>
                        <td>{{ $rule->course->section }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Challenges</div>
            <table>
                <thead>
                    <tr>
                        <th>Challenge</th>
                        <th>Type</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($student->challenges as $challenge)
                    <tr>
                        <td>{{ $challenge->name }}</td>
                        <td>{{ ucfirst($challenge->type) }}</td>
                        <td>{{ $challenge->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
</body>
</html>

