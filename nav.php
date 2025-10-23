<link rel="stylesheet" href="css/theme.css">
<?php
  // Detect which category is active (e.g., category.php?cat=HTML)
  $currentCat = isset($_GET['cat']) ? $_GET['cat'] : '';
?>
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
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#F57C00" class="bi bi-code-slash" viewBox="0 0 16 16">
                  <path d="M10.478 1.647a.5.5 0 1 0-.956-.294l-4 13a.5.5 0 0 0 .956.294zM4.854 4.146a.5.5 0 0 1 0 .708L1.707 8l3.147 3.146a.5.5 0 0 1-.708.708l-3.5-3.5a.5.5 0 0 1 0-.708l3.5-3.5a.5.5 0 0 1 .708 0m6.292 0a.5.5 0 0 0 0 .708L14.293 8l-3.147 3.146a.5.5 0 0 0 .708.708l3.5-3.5a.5.5 0 0 0 0-.708l-3.5-3.5a.5.5 0 0 0-.708 0"/>
                  </svg>
              HTML
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="category.php?cat=CSS">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#5E35B1" class="bi bi-css" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M14 0a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V0zM4.59 7.498q-.908 0-1.455.508-.547.507-.547 1.484v3.106q0 .986.527 1.484t1.406.498q.576 0 1.016-.224.45-.225.703-.674.255-.45.254-1.114v-.185h-1.22v.176q0 .449-.186.683t-.527.235q-.372-.01-.557-.264-.186-.255-.186-.752V9.686q0-.547.166-.811.177-.264.577-.264.321 0 .517.225.195.224.195.693v.205h1.23V9.52q0-.674-.243-1.124a1.55 1.55 0 0 0-.664-.673q-.42-.225-1.006-.225m4.214-.01q-.586 0-1.006.244a1.67 1.67 0 0 0-.635.674 2.1 2.1 0 0 0-.225.996q0 .753.293 1.182.304.42.967.732l.469.215q.44.186.625.43.186.244.186.635 0 .478-.166.703-.157.224-.528.224-.36 0-.547-.244-.185-.243-.205-.752H6.87q.02.996.498 1.524.479.527 1.387.527t1.416-.518.508-1.484q0-.81-.332-1.289-.333-.479-1.045-.79l-.45-.196q-.39-.166-.556-.381-.165-.214-.166-.576 0-.4.166-.596.175-.195.508-.195.36 0 .508.234.156.234.175.703h1.123q-.03-.976-.498-1.484-.468-.518-1.308-.518m4.057 0q-.585 0-1.006.244a1.67 1.67 0 0 0-.634.674 2.1 2.1 0 0 0-.225.996q0 .753.293 1.182.303.42.967.732l.469.215q.438.186.625.43.185.244.185.635 0 .478-.166.703-.156.224-.527.224-.361.001-.547-.244-.186-.243-.205-.752h-1.162q.02.996.498 1.524.479.527 1.386.527.909 0 1.417-.518.507-.517.507-1.484 0-.81-.332-1.289t-1.045-.79l-.449-.196q-.39-.166-.556-.381-.166-.214-.166-.576 0-.4.165-.596.177-.195.508-.195.361 0 .508.234.156.234.176.703h1.123q-.03-.976-.498-1.484-.47-.518-1.309-.518"/>
              </svg>
              CSS
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="category.php?cat=JavaScript">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#FFC400" class="bi bi-javascript" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M14 0a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zM9.053 7.596v3.127l-.007 1.752q0 .498-.186.752t-.556.263q-.342 0-.528-.234-.185-.234-.185-.684v-.175H6.37v.185q0 .665.253 1.113.255.45.703.674.44.225 1.016.225.88 0 1.406-.498.527-.498.527-1.485l.007-1.752V7.596zm3.808-.108q-.585 0-1.006.244a1.67 1.67 0 0 0-.634.674 2.1 2.1 0 0 0-.225.996q0 .753.293 1.182.303.42.967.732l.469.215q.438.186.625.43.185.244.185.635 0 .478-.166.703-.156.224-.527.224-.361.001-.547-.244-.186-.243-.205-.752h-1.162q.02.996.498 1.524.479.527 1.386.527.909 0 1.417-.518.507-.517.507-1.484 0-.81-.332-1.289t-1.045-.79l-.449-.196q-.39-.166-.556-.381-.166-.214-.166-.576 0-.4.165-.596.177-.195.508-.195.361 0 .508.234.156.234.176.703h1.123q-.03-.976-.498-1.484-.47-.518-1.309-.518"/>
              </svg>
              JS
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="category.php?cat=PHP">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#01579B" viewBox="0 0 24 24">
              <path d="M7.01 10.207h-.944l-.515 2.648h.838c.556 0 .97-.105 1.242-.314c.272-.21.455-.559.55-1.049c.092-.47.05-.802-.124-.995c-.175-.193-.523-.29-1.047-.29zM12 5.688C5.373 5.688 0 8.514 0 12s5.373 6.313 12 6.313S24 15.486 24 12c0-3.486-5.373-6.312-12-6.312zm-3.26 7.451c-.261.25-.575.438-.917.551c-.336.108-.765.164-1.285.164H5.357l-.327 1.681H3.652l1.23-6.326h2.65c.797 0 1.378.209 1.744.628c.366.418.476 1.002.33 1.752a2.836 2.836 0 0 1-.305.847c-.143.255-.33.49-.561.703zm4.024.715l.543-2.799c.063-.318.039-.536-.068-.651c-.107-.116-.336-.174-.687-.174H11.46l-.704 3.625H9.388l1.23-6.327h1.367l-.327 1.682h1.218c.767 0 1.295.134 1.586.401s.378.7.263 1.299l-.572 2.944h-1.389zm7.597-2.265a2.782 2.782 0 0 1-.305.847c-.143.255-.33.49-.561.703a2.44 2.44 0 0 1-.917.551c-.336.108-.765.164-1.286.164h-1.18l-.327 1.682h-1.378l1.23-6.326h2.649c.797 0 1.378.209 1.744.628c.366.417.477 1.001.331 1.751zm-2.595-1.382h-.943l-.516 2.648h.838c.557 0 .971-.105 1.242-.314c.272-.21.455-.559.551-1.049c.092-.47.049-.802-.125-.995s-.524-.29-1.047-.29z"/>
              </svg>
              PHP
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="category.php?cat=Svelte">
              <img src="assets/img/Svelte_logo_by_gengns.svg.png" alt="" width="24" height="auto" class="d-inline-block align-text-top">
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