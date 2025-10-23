<nav id="navbar-example2" class="navbar navbar-expand-lg fixed-top navbar-dark bg-black">
    <div class="container-fluid">
      <div class="navbar-brand h1 mb-0" style="font-family: 'Courier New', Courier, monospace; font-weight: bold;">
        <i class="bi bi-braces-asterisk"></i>M2a
        </div>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mx-auto mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="index.php#scrollspyHome">
              Home
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php#scrollspyContact">
              Contact
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Quizess
            </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="quizess/quiz_html.php">HTML</a></li>
            <li><a class="dropdown-item" href="quizess/quiz_css.php">CSS</a></li>
            <li><a class="dropdown-item" href="quizess/quiz_js.php">JavaScript</a></li>
            <li><a class="dropdown-item" href="quizess/quiz_php.php">PHP</a></li>
            <li><a class="dropdown-item" href="quizess/quiz_svelte.php">Svelte <span class="badge bg-success rounded-pill">New</span> </a></li>
          </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link " href="complier.php" role="button" aria-expanded="false">
              Compiler
            </a>
          </li>
        </ul>
        <ul class="navbar-nav mx-auto mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="category.php?cat=HTML">
              <img src="assets/img/html5-icon-13.jpg" alt="" width="24" height="24" class="d-inline-block align-text-top">
              HTML
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="category.php?cat=CSS">
              <img src="assets/img/css3.png" alt="" width="24" height="24" class="d-inline-block align-text-top">
              CSS
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="category.php?cat=JavaScript">
              <img src="assets/img/js.png" alt="" width="24" height="24" class="d-inline-block align-text-top">
              JS
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="category.php?cat=PHP">
              <img src="assets/img/php.png" alt="" width="24" height="24" class="d-inline-block align-text-top">
              PHP
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="category.php?cat=Svelte">
              <img src="assets/img/Svelte_logo_by_gengns.svg.png" alt="" width="24" height="24" class="d-inline-block align-text-top">
              Svelte
            </a>
          </li>
        </ul>
        <div>
          <link rel="stylesheet" href="css/theme.css">
      
            <?php include 'toggle-theme.html'; ?>

          <script src="js/theme.js"></script>
        </div>
      <a type="button" class="btn btn-outline-light py-1" href="logout.php"><small>Log out</small></a>


      </div>
    </div>
  </nav>