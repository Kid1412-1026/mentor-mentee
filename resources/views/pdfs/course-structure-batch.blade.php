<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Course Structure Report</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; }
        h2 { color: #333; margin-top: 30px; }
        .section-summary { margin-top: 10px; }
        .credit-total { font-weight: bold; }
    </style>
</head>
<body>
    <h1>Course Structure Report</h1>

    @foreach($courseStructures as $intake => $sectionRules)
        <h2>Intake: {{ $intake }}</h2>
        
        @foreach($sections as $section)
            @if(isset($sectionRules[$section]))
                <h3>{{ $section }}</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Credit Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalCredits = 0; @endphp
                        @foreach($sectionRules[$section] as $rule)
                            <tr>
                                <td>{{ $rule->course->code }}</td>
                                <td>{{ $rule->course->name }}</td>
                                <td>{{ $rule->course->credit_hour }}</td>
                            </tr>
                            @php $totalCredits += $rule->course->credit_hour; @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="credit-total">Total Credit Hours:</td>
                            <td>{{ $totalCredits }}</td>
                        </tr>
                    </tfoot>
                </table>
            @endif
        @endforeach

        <div class="section-summary">
            <h3>Summary of Credit Hours</h3>
            <table>
                <thead>
                    <tr>
                        <th>Section</th>
                        <th>Total Credit Hours</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotal = 0; @endphp
                    @foreach($sections as $section)
                        @if(isset($sectionRules[$section]))
                            @php
                                $sectionTotal = $sectionRules[$section]->sum('course.credit_hour');
                                $grandTotal += $sectionTotal;
                            @endphp
                            <tr>
                                <td>{{ $section }}</td>
                                <td>{{ $sectionTotal }}</td>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td class="credit-total">Grand Total</td>
                        <td class="credit-total">{{ $grandTotal }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endforeach
</body>
</html>