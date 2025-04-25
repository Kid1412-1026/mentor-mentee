<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Course Progress Report</title>
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
            font-size: 12px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
        .progress-summary {
            margin: 20px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Course Progress Report</h1>
        <p>Generated on {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Student Information</div>
        <table>
            <tr><th width="30%">Name</th><td>{{ $student->name }}</td></tr>
            <tr><th>Matric No</th><td>{{ $student->matric_no }}</td></tr>
            <tr><th>Program</th><td>{{ $student->program }}</td></tr>
            <tr><th>Intake</th><td>{{ $student->intake }}</td></tr>
            <tr><th>Faculty</th><td>{{ $student->faculty }}</td></tr>
        </table>
    </div>

    <div class="progress-summary">
        <h3>Progress Summary</h3>

        @php
            $totalQualityPoints = $enrolledCourses->sum(function($enrollment) {
                return $enrollment->course->credit_hour * $enrollment->pointer;
            });
            $totalCreditsWithGrade = $enrolledCourses->sum('course.credit_hour');
            $cgpa = $totalCreditsWithGrade > 0 ? number_format($totalQualityPoints / $totalCreditsWithGrade, 2) : '0.00';

            $semesterEnrollments = $enrolledCourses
                ->groupBy(function($enrollment) {
                    return $enrollment->year . '-' . $enrollment->sem;
                })
                ->sortBy(function($group, $key) {
                    list($year, $sem) = explode('-', $key);
                    return $year . str_pad($sem, 2, '0', STR_PAD_LEFT);
                });
        @endphp

        <h4 style="margin-top: 15px; margin-bottom: 10px;">GPA by Semester</h4>
        <table style="margin: 0 0 20px 0">
            <thead>
                <tr>
                    <th>Semester</th>
                    <th>GPA</th>
                </tr>
            </thead>
            <tbody>
                @foreach($semesterEnrollments as $key => $semesterGroup)
                    @php
                        list($year, $sem) = explode('-', $key);
                        $semesterQualityPoints = $semesterGroup->sum(function($enrollment) {
                            return $enrollment->course->credit_hour * $enrollment->pointer;
                        });
                        $semesterCredits = $semesterGroup->sum('course.credit_hour');
                        $semesterGPA = $semesterCredits > 0 ? number_format($semesterQualityPoints / $semesterCredits, 2) : '0.00';
                    @endphp
                    <tr>
                        <td>Semester {{ $sem }}/Year {{ $year }}</td>
                        <td>{{ $semesterGPA }}</td>
                    </tr>
                @endforeach
                <tr style="font-weight: bold; background-color: #f8f9fa;">
                    <td>Cumulative GPA</td>
                    <td>{{ $cgpa }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <div class="progress-summary">
        <h4 style="margin-top: 15px; margin-bottom: 10px;">Credits by Section</h4>
        <table style="margin: 0">
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
                        $sectionCredits = $enrolledCourses
                            ->where('course.section', $section)
                            ->sum('course.credit_hour');

                        $requiredCredits = $allCourses
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
                    $isTotalDeficit = $completedCredits < $totalCredits;
                @endphp
                <tr style="font-weight: bold; background-color: #f8f9fa;">
                    <td style="{{ $isTotalDeficit ? 'color: #EF4444;' : '' }}">Credits Remaining: {{ $totalCredits - $completedCredits }}</td>
                    <td colspan="2" style="{{ $isTotalDeficit ? 'color: #EF4444;' : '' }}">Credits Completed: {{ $completedCredits }}</td>
                </tr>
                <tr style="font-weight: bold; background-color: #f8f9fa;">
                    <td>Total Credits Required: {{ $totalCredits }}</td>
                    <td colspan="2" style="{{ $isTotalDeficit ? 'color: #EF4444;' : '' }}">Completion: {{ round(($completedCredits / $totalCredits) * 100, 1) }}%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Completed Courses</div>
        <table>
            <thead>
                <tr>
                    <th>Course Code</th>
                    <th>Course Name</th>
                    <th>Credit Hours</th>
                    <th>Grade</th>
                    <th>Rating</th>
                    <th>Semester</th>
                    <th>Year</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enrolledCourses as $enrollment)
                <tr>
                    <td>{{ $enrollment->course->code }}</td>
                    <td>{{ $enrollment->course->name }}</td>
                    <td>{{ $enrollment->course->credit_hour }}</td>
                    <td>{{ $enrollment->grade }}</td>
                    <td>{{ $enrollment->rating }}/5</td>
                    <td>{{ $enrollment->sem }}</td>
                    <td>{{ $enrollment->year }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <div class="section">
        <div class="section-title">Remaining Required Courses</div>
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
                @foreach($remainingCourses as $rule)
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
</body>
</html>













