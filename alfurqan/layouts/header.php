<?php require_once INCLUDE_PATH . '/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Al Furqan - Islamic Learning Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #1a73e8;
            --secondary-color: #5f6368;
            --background-color: #f8f9fa;
            --card-shadow: 0 1px 2px rgba(60,64,67,0.3), 0 1px 3px rgba(60,64,67,0.15);
        }

        body {
            background-color: var(--background-color);
            font-family: 'Roboto', sans-serif;
        }

        .sidebar {
            width: 280px;
            position: fixed;
            top: 0;
            bottom: 0;
            background: white;
            box-shadow: var(--card-shadow);
            z-index: 1000;
            padding: 1rem;
            transition: transform 0.3s ease;
            transform: translateX(0);
        }

        .main-content {
            margin-left: 280px;
            padding: 2rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                transform: translateX(-100%);
                z-index: 1000;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                margin-top: 3rem;
                padding: 1rem;
            }

            .toggle-sidebar {
                display: block;
            }
        }

        .nav-link {
            color: var(--secondary-color);
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: #e8f0fe;
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <button class="btn btn-black toggle-sidebar" onclick="toggleSidebar()" style="position: fixed; top: 20px; left: 20px; z-index: 1001; display: none;">
        <i class="fas fa-bars"></i>
    </button>

    <div class="sidebar">
        <nav>
            <br>
            <br>
            <br>
            <a href="index.php" class="nav-link <?= isActive('index.php') ?>">
                <i class="fas fa-th-large"></i> Overview
            </a>
            <a href="sermon.php" class="nav-link <?= isActive('sermon.php') ?>">
                <i class="fas fa-church"></i> Sermon
            </a>
            <a href="event.php" class="nav-link <?= isActive('event.php') ?>">
                <i class="fas fa-calendar-day"></i> Event
            </a>
            <a href="activity.php" class="nav-link <?= isActive('activity.php') ?>">
                <i class="fas fa-running"></i> Activity
            </a>
        </nav>
    </div>

    <div class="main-content">


