<?php /** Pagination element — expects $currentPage, $totalPages */ ?>

<nav class="pagination">
    <?php if ($currentPage > 1): ?>
        <a href="?page=<?= $currentPage - 1 ?>">Previous</a>
    <?php endif; ?>

    <span>Page <?= $currentPage ?> of <?= $totalPages ?></span>

    <?php if ($currentPage < $totalPages): ?>
        <a href="?page=<?= $currentPage + 1 ?>">Next</a>
    <?php endif; ?>
</nav>
