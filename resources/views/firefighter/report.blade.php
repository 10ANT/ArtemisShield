<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Incident Report - ID {{ $report->id }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            font-size: 12px;
            line-height: 1.6;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .report-meta {
            margin-bottom: 30px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .report-meta p {
            margin: 0 0 5px 0;
        }
        .report-meta strong {
            display: inline-block;
            width: 150px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section h2 {
            font-size: 18px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .transcript {
            font-style: italic;
            background-color: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #555;
        }
        .suggestions ul, .entities ul {
            list-style-type: none;
            padding-left: 0;
        }
        .suggestions li {
            background-color: #eaf6ff;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 8px;
            border-left: 4px solid #2980b9;
        }
        .entity-tag {
            display: inline-block;
            padding: 3px 8px;
            margin: 2px;
            border-radius: 4px;
            font-size: 11px;
            color: #fff;
        }
        .entity-location { background-color: #3498db; }
        .entity-resource { background-color: #1abc9c; }
        .entity-hazard { background-color: #f39c12; }
        .entity-other { background-color: #95a5a6; }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #777;
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 50px;
        }
    </style>
</head>
<body>
    <div class="footer">
        ArtemisShield - Confidential Incident Report - Generated on {{ now()->format('Y-m-d H:i:s') }}
    </div>

    <div class="container">
        <div class="header">
            <h1>Incident Field Report</h1>
        </div>

        <div class="report-meta">
            <p><strong>Report ID:</strong> {{ $report->id }}</p>
            <p><strong>Date & Time Logged:</strong> {{ $report->created_at->format('Y-m-d H:i:s T') }}</p>
            <p><strong>Reporting Officer:</strong> {{ $report->user->name ?? 'N/A' }} (ID: {{ $report->user_id }})</p>
        </div>

        <div class="section">
            <h2>Field Report Transcript</h2>
            <div class="transcript">
                <p>"{{ $report->transcript }}"</p>
            </div>
        </div>

        @if(!empty($report->key_entities))
            <div class="section">
                <h2>Key Entities Detected</h2>
                <div class="entities">
                    @foreach($report->key_entities as $entity)
                        @php
                            $category = strtolower($entity['category'] ?? 'other');
                            $class = 'entity-other';
                            if (str_contains($category, 'location')) $class = 'entity-location';
                            elseif (str_contains($category, 'resource') || str_contains($category, 'equipment')) $class = 'entity-resource';
                            elseif (str_contains($category, 'hazard') || str_contains($category, 'skill')) $class = 'entity-hazard';
                        @endphp
                        <span class="entity-tag {{ $class }}">{{ $entity['text'] }} ({{ $entity['category'] }})</span>
                    @endforeach
                </div>
            </div>
        @endif

        @if(!empty($report->ai_suggested_actions) && !empty($report->ai_suggested_actions['suggestions']))
            <div class="section">
                <h2>AI-Powered Suggested Actions</h2>
                <div class="suggestions">
                    <ul>
                        @foreach($report->ai_suggested_actions['suggestions'] as $suggestion)
                            <li>{{ $suggestion['suggestion'] }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>
</body>
</html>