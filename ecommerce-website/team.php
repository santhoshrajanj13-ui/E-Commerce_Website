<?php
/**
 * Team Roles Page
 * Module: Team Roles Page (Trisha - Project Manager)
 */
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/session.php';

$pageTitle = "Our Team - ShopEase";
include __DIR__ . '/includes/header.php';

$team = [
    [
        'name' => 'Trisha',
        'role' => 'Project Manager, GitHub Manager & UI/UX Designer',
        'tasks' => ['Project planning & task allocation', 'GitHub repository management', 'UI/UX design (wireframes, prototypes)', 'Documentation, report & PPT', 'Coordinating the team & final presentation']
    ],
    [
        'name' => 'Santhosh',
        'role' => 'Frontend Developer',
        'tasks' => ['Home page design', 'Navigation bar & footer', 'Responsive design (mobile & desktop)', 'Overall UI implementation']
    ],
    [
        'name' => 'Yudhith',
        'role' => 'Frontend Developer',
        'tasks' => ['Product listing page', 'Product details page', 'Search functionality', 'Category filtering']
    ],
    [
        'name' => 'Abhinav',
        'role' => 'Backend Developer',
        'tasks' => ['User registration', 'User login', 'Authentication & session management', 'Password encryption']
    ],
    [
        'name' => 'Priyan',
        'role' => 'Backend & Database Developer',
        'tasks' => ['MySQL database design', 'Shopping cart functionality', 'Order management', 'Update order status']
    ],
    [
        'name' => 'Sudaran',
        'role' => 'Testing & Integration Engineer',
        'tasks' => ['Payment interface (demo)', 'Testing all modules', 'Bug fixing', 'Integration & deployment']
    ],
];
?>

<div class="container">
  <h2 class="section-title">E-Commerce Website Project — Team Roles & Responsibilities</h2>
  <p style="text-align:center; color: var(--muted); margin-bottom: 20px;">6 Members</p>

  <div class="team-grid">
    <?php foreach ($team as $member): ?>
      <div class="team-card">
        <div class="avatar"><?php echo strtoupper(substr($member['name'], 0, 1)); ?></div>
        <h3><?php echo htmlspecialchars($member['name']); ?></h3>
        <div class="role"><?php echo htmlspecialchars($member['role']); ?></div>
        <ul>
          <?php foreach ($member['tasks'] as $task): ?>
            <li><?php echo htmlspecialchars($task); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endforeach; ?>
  </div>

  <h2 class="section-title">Team Structure Overview</h2>
  <table class="admin-table" style="margin-bottom: 40px;">
    <thead>
      <tr>
        <th>Project Management</th>
        <th>Frontend Development</th>
        <th>Backend Development</th>
        <th>Database Design</th>
        <th>Testing & Integration</th>
        <th>Documentation & PPT</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Trisha</td>
        <td>Santhosh, Yudhith</td>
        <td>Abhinav, Priyan</td>
        <td>Priyan</td>
        <td>Sudaran</td>
        <td>Trisha</td>
      </tr>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
