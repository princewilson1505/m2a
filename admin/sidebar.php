<?php
$current_page = basename($_SERVER['PHP_SELF']);

// Group pages by parent menu
$menu_groups = [
  'content' => ['manage_lesson.php', 'add_lesson.php', 'edit_lesson.php', 'view_lesson.php'],
  'quiz'    => ['manage_quiz.php', 'add_quiz.php', 'edit_quiz.php'],
  'user'    => ['manage_user.php', 'add_user.php', 'edit_user.php']
];
?>

<div class="d-flex sticky flex-column flex-shrink-0 p-3 bg-black text-white" style="width: 250px; height: 100vh;">
  <h4 class="text-light" style="font-family: 'Courier New', Courier, monospace; font-weight: bold;">
    <i class="bi bi-braces-asterisk"></i> M2a Admin
  </h4>
  <hr>

  <ul class="nav nav-pills flex-column mb-auto">

    <!-- Dashboard -->
    <li class="nav-item">
      <a href="admin.php"
         class="nav-link text-white <?php if ($current_page == 'admin.php') echo 'active border border-2 border-outline rounded'; ?>">
        <i class="bi bi-house-door me-2"></i> Dashboard
      </a>
    </li>

    <!-- Content Menu -->
    <li>
      <a class="nav-link text-white d-flex justify-content-between align-items-center <?php
        if (in_array($current_page, $menu_groups['content'])) echo 'active border border-2 border-outline rounded';
      ?>"
      data-bs-toggle="collapse" href="#contentMenu" role="button"
      aria-expanded="<?php echo in_array($current_page, $menu_groups['content']) ? 'true' : 'false'; ?>"
      aria-controls="contentMenu">
        <span><i class="bi bi-journal-code me-2"></i> Content</span>
        <i class="bi bi-caret-down-fill small"></i>
      </a>

      <div class="collapse <?php echo in_array($current_page, $menu_groups['content']) ? 'show' : ''; ?>" id="contentMenu">
        <ul class="nav flex-column ms-4 border-start ps-2">
          <li><a href="manage_lesson.php" class="nav-link text-white <?php if ($current_page == 'manage_lesson.php') echo 'active border border-2 border-primary rounded'; ?>">Manage Lessons</a></li>
          <li><a href="add_lesson.php" class="nav-link text-white <?php if ($current_page == 'add_lesson.php') echo 'active border border-2 border-primary rounded'; ?>">Add Lesson</a></li>
        </ul>
      </div>
    </li>

    <!-- Quiz Menu -->
    <li>
      <a class="nav-link text-white d-flex justify-content-between align-items-center <?php
        if (in_array($current_page, $menu_groups['quiz'])) echo 'active border border-2 border-outline rounded';
      ?>"
      data-bs-toggle="collapse" href="#quizMenu" role="button"
      aria-expanded="<?php echo in_array($current_page, $menu_groups['quiz']) ? 'true' : 'false'; ?>"
      aria-controls="quizMenu">
        <span><i class="bi bi-question-circle me-2"></i> Quizzes</span>
        <i class="bi bi-caret-down-fill small"></i>
      </a>

      <div class="collapse <?php echo in_array($current_page, $menu_groups['quiz']) ? 'show' : ''; ?>" id="quizMenu">
        <ul class="nav flex-column ms-4 border-start ps-2">
          <li><a href="manage_quiz.php" class="nav-link text-white <?php if ($current_page == 'manage_quiz.php') echo 'active border border-2 border-primary rounded'; ?>">Manage Quizzes</a></li>
          <li><a href="add_quiz.php" class="nav-link text-white <?php if ($current_page == 'add_quiz.php') echo 'active border border-2 border-primary rounded'; ?>">Add Quiz</a></li>
        </ul>
      </div>
    </li>

    <!-- Users Menu -->
    <li class="nav-item">
      <a href="manage_user.php"
         class="nav-link text-white <?php if ($current_page == 'manage_user.php') echo 'active border border-2 border-outline rounded'; ?>">
        <i class="bi bi-person-fill me-2"></i> User
      </a>
    </li>

    <li class="nav-item">
      <a href="messages.php"
         class="nav-link text-white <?php if ($current_page == 'messages.php') echo 'active border border-2 border-outline rounded'; ?>">
        <i class="bi bi-envelope-fill me-2"></i> Messages
      </a>
    </li>

  </ul>

  <hr class="border-secondary">
  <div>
    <a href="../logout.php" class="btn btn-outline-light w-100">
      <i class="bi bi-box-arrow-right"></i> Logout
    </a>
  </div>
</div>
