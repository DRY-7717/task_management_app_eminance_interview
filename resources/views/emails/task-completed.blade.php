<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 32px;
            margin: 0;
            font-weight: 800;
        }

        .content h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .task-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .task-list li {
            margin-bottom: 10px;
        }

        .label {
            font-weight: bold;
        }

        .description-box {
            margin-top: 5px;
            padding-left: 20px;
            word-wrap: break-word;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>App Management Task</h1>
        </div>

        <div class="content">
            <h2>Task Completed</h2>

            <ul class="task-list">
                <li><span class="label">Title:</span> {{ $task->title }}</li>
                <li><span class="label">assignee:</span> {{ $changedBy->name }}</li>
                <li><span class="label">Status:</span> {{ $task->status->name }}</li>
                <li><span class="label">Finish date:</span> {{ $task->finish_date }}</li>
                <li>
                    <span class="label">Description:</span>
                    <div class="description-box">
                        {{ $task->description }}
                    </div>
                </li>
            </ul>
        </div>
    </div>
</body>

</html>
