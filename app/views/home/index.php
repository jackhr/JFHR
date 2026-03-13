<?php
$pageTitle = 'App Directory';
$bodyClass = 'apps-body';
include_once __DIR__ . '/../partials/header.php';
?>

<main class="app-directory">
    <header class="directory-head">
        <p class="directory-kicker">Personal Website Space</p>
        <h1>Application Directory</h1>
        <p>Launch one of the applications below.</p>
    </header>

    <section class="apps-grid" aria-label="Applications">
        <article class="app-panel">
            <h2>Medicine Log</h2>
            <p>Track medicine intake entries, ratings, and daily history.</p>
            <a class="continue-btn app-link" href="https://medicine.jackrainey.com/" rel="noopener noreferrer">Open Medicine App</a>
        </article>
        <article class="app-panel">
            <h2>Ash Surprises</h2>
            <p>30 Surpises, big, and small.</p>
            <a class="continue-btn app-link" href="https://ash-surprises.jackrainey.com/" rel="noopener noreferrer">Open Ash Surprises App</a>
        </article>
        <article class="app-panel">
            <h2>Task Manager</h2>
            <p>Manage tasks and track progress.</p>
            <a class="continue-btn app-link" href="https://task-manager.jackrainey.com/" rel="noopener noreferrer">Open Task Manager App</a>
        </article>
    </section>
</main>

</body>

</html>
