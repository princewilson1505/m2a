<link rel="stylesheet" href="css/theme.css">
<?php
  // Ensure session is started and determine current user (if any)
  if (session_status() === PHP_SESSION_NONE) session_start();

  // Include DB connection if not already present so we can fetch user info when available
  if (!isset($conn) && file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
  }

  // Detect which category is active (e.g., category.php?cat=HTML)
  $currentCat = isset($_GET['cat']) ? $_GET['cat'] : '';

  $currentUser = null;
  if (isset($_SESSION['user_id']) && isset($conn)) {
    $uid = (int) $_SESSION['user_id'];
    $uStmt = $conn->prepare('SELECT id, username, nickname, profile_img, role FROM users WHERE id = ? LIMIT 1');
    if ($uStmt) {
      $uStmt->bind_param('i', $uid);
      $uStmt->execute();
      $currentUser = $uStmt->get_result()->fetch_assoc();
      $uStmt->close();
    }
  }
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
            <li><a class="dropdown-item" href="auto_quiz.php">Auto Quiz</a></li>
          </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link " href="complier.php" target="_blank" role="button" aria-expanded="false">
              Compiler
            </a>
          </li>
        </ul>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn btn-outline-white border-bottom text-white " data-bs-toggle="modal" data-bs-target="#exampleModal">
          <i class="bi bi-search"></i> Search...
        </button>

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


      <button class="btn btn-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
        <!-- Profile Picture -->
                <?php 
                  $profileImg = $currentUser['profile_img'] ? htmlspecialchars($currentUser['profile_img']) : 'https://via.placeholder.com/100?text=User';
                ?>
                <img src="<?= $profileImg ?>" class="rounded-circle border border-light" alt="Profile Picture" style="width: 24px; height: 24px; object-fit: cover;">
        <small><?= htmlspecialchars($currentUser['nickname'] ?: $currentUser['username']) ?></small>
      </button>

      </div>
    </div>
  </nav>

  <!-- Modal with dark mode support -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Search Lessons</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label for="searchInput" class="form-label">Enter search query:</label>
                  <input type="text" class="form-control" id="searchInput" placeholder="Search lessons, categories...">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="searchBtn">Search</button>
              </div>
            </div>
          </div>
        </div>

        <div class="offcanvas offcanvas-end bg-black text-light" tabindex="-1" id="offcanvasRight"  aria-labelledby="offcanvasRightLabel">
          <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasRightLabel">Profile</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body container">
            <div class="modal-body text-center">
              <?php if ($currentUser): ?>
                <!-- Profile Picture -->
                <?php 
                  $profileImg = $currentUser['profile_img'] ? htmlspecialchars($currentUser['profile_img']) : 'https://via.placeholder.com/100?text=User';
                ?>
                <img src="<?= $profileImg ?>" class="rounded-circle mb-3 border border-light" alt="Profile Picture" style="width: 100px; height: 100px; object-fit: cover;">
                
                <!-- Name and Role -->
                <h5 class="fw-bold mb-1"><?= htmlspecialchars($currentUser['nickname'] ?: $currentUser['username']) ?></h5>
                <p class="text-info small">@<?= htmlspecialchars($currentUser['username']) ?></p>
                <p class="text-light"><?= htmlspecialchars(ucfirst($currentUser['role'] ?? 'user')) ?></p>

                <!-- Account Info -->
                <div class="text-center mx-auto" style="max-width: 300px;">
                  <p><strong>User ID:</strong> <?= (int)$currentUser['id'] ?></p>
                </div>
                
                <!-- Edit Profile Button -->
                <a href="profile.php" class="btn container btn-outline-primary mt-2">Edit Profile</a>
              <?php else: ?>
                <img src="https://via.placeholder.com/100?text=User" class="rounded-circle mb-3 border" alt="Profile Picture">
                <h5 class="fw-bold mb-1">Guest</h5>
                <p class="text-muted">Not signed in</p>
                <div class="text-start mx-auto" style="max-width: 300px;">
                  <p><a href="login.php" class="btn btn-sm btn-primary">Sign in</a>
                  <a href="register.php" class="btn btn-sm btn-outline-light ms-2">Register</a></p>
                </div>
              <?php endif; ?>
            </div>

        <hr>
            
              <link rel="stylesheet" href="css/theme.css">

              <?php include 'toggle-theme.html'; ?>

              <script src="js/theme.js"></script>

            <?php if ($currentUser): ?>
              <a type="button" class="btn btn-outline-light container py-1" href="logout.php"><small>Log out</small></a>
            <?php endif; ?>

          </div>
        </div>

<!-- Search functionality script -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const searchBtn = document.getElementById('searchBtn');
    const searchInput = document.getElementById('searchInput');
    const exampleModal = document.getElementById('exampleModal');

    if (searchBtn && searchInput) {
      // Handle Search button click
      searchBtn.addEventListener('click', function() {
        // Always get the current input element (may be replaced when showing no-results)
        const currInput = document.getElementById('searchInput');
        const query = currInput ? currInput.value.trim() : '';

        if (query === '') {
          // show in-modal message instead of alert
          const modalBody = exampleModal.querySelector('.modal-body');
          modalBody.innerHTML = '<p class="text-muted">Please enter a search query.</p>';
          // re-add the input field
          modalBody.insertAdjacentHTML('beforeend', `\n            <div class="mb-3">\n              <label for="searchInput" class="form-label">Enter search query:</label>\n              <input type="text" class="form-control" id="searchInput" placeholder="Search lessons, quizzes...">\n            </div>\n          `);
          const newInput = document.getElementById('searchInput');
          if (newInput) newInput.focus();
          return;
        }

        // Fetch and display results in modal (AJAX)
        if (currInput) currInput.disabled = true;
        searchBtn.disabled = true;
        searchBtn.innerHTML = 'Searching...';

        // Use absolute path so fetch works from pages in subfolders (admin/...)
        fetch('/m2a/search.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'q=' + encodeURIComponent(query) + '&format=json'
        })
        .then(async response => {
          const txt = await response.text();
          let data = null;
          try {
            data = JSON.parse(txt || '{}');
          } catch (e) {
            // Not JSON — show raw text as error
            throw new Error(txt || 'Non-JSON response from server');
          }
          if (!response.ok) {
            const err = data.error || 'Search request failed';
            throw new Error(err);
          }
          if (data.success && data.results) {
            displaySearchResults(data.results);
          } else {
            displayNoResults();
          }
        })
        .catch(error => {
          console.error('Search error:', error);
          displayError(error.message || 'Error performing search');
        })
        .finally(() => {
          const currInput = document.getElementById('searchInput');
          if (currInput) currInput.disabled = false;
          searchBtn.disabled = false;
          searchBtn.innerHTML = 'Search';
        });
      });

      // Allow Enter key to trigger search
      searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          searchBtn.click();
        }
      });
    }

    // Display search results in the modal body
    function displaySearchResults(results) {
      const modalBody = exampleModal.querySelector('.modal-body');
      
      if (results.length === 0) {
        displayNoResults();
        return;
      }

      let html = '<h6>Results:</h6><div class="list-group">';
      results.forEach(result => {
        const frag = result.section_id ? '#section-' + result.section_id : '';
        html += `
          <a href="lesson.php?id=${result.id}${frag}" class="list-group-item list-group-item-action" data-lesson-id="${result.id}" data-section-id="${result.section_id || ''}">
            <div class="d-flex w-100 justify-content-between">
              <h6 class="mb-1">${escapeHtml(result.title)}</h6>
              <small class="text-muted">${escapeHtml(result.created_at)}</small>
            </div>
            <p class="mb-1 text-muted small">${escapeHtml(result.snippet || 'No description')}</p>
          </a>
        `;
      });
      html += '</div>';
      html += '<div class="mt-3"><button type="button" class="btn btn-sm btn-outline-secondary" id="clearSearchBtn">Clear & Search Again</button></div>';
      
      modalBody.innerHTML = html;

      // Attach clear button handler
      document.getElementById('clearSearchBtn').addEventListener('click', clearSearch);

      // Intercept clicks on results when we're already on the target lesson page so we can close the modal and smoothly scroll
      const anchors = modalBody.querySelectorAll('.list-group-item-action');
      anchors.forEach(a => {
        a.addEventListener('click', function (e) {
          try {
            const lessonId = String(this.dataset.lessonId || '');
            const sectionId = this.dataset.sectionId ? String(this.dataset.sectionId) : '';
            // If current page is a lesson and id matches, prevent navigation and scroll inside the page
            const curPath = window.location.pathname || '';
            const curParams = new URLSearchParams(window.location.search || '');
            const curLessonId = curParams.get('id');

            if (curPath.endsWith('lesson.php') && curLessonId === lessonId && sectionId) {
              e.preventDefault();
              // hide modal
              const bsModal = bootstrap.Modal.getInstance(exampleModal) || new bootstrap.Modal(exampleModal);
              bsModal.hide();

              // scroll the lesson content area to the target element, accounting for navbar height
              setTimeout(() => {
                const targetId = 'section-' + sectionId;
                const el = document.getElementById(targetId);
                const navbar = document.querySelector('nav.navbar');
                const navbarHeight = navbar ? navbar.offsetHeight : 60;
                const container = document.querySelector('.col-lg-8.offset-lg-1') || document.querySelector('.content-area');
                
                if (el && container) {
                  const top = el.getBoundingClientRect().top - container.getBoundingClientRect().top + container.scrollTop - navbarHeight - 20;
                  container.scrollTo({ top: Math.max(0, top), behavior: 'smooth' });
                } else if (el) {
                  el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
              }, 150);
            }
          } catch (err) {
            // let default behavior happen on error
            console.error(err);
          }
        });
      });
    }

    // Display no results message
    function displayNoResults() {
      const modalBody = exampleModal.querySelector('.modal-body');
      modalBody.innerHTML = `
        <p class="mb-3">No results found for your search.</p>
        <div class="mb-3">
          <label for="searchInput" class="form-label">Try another search:</label>
          <input type="text" class="form-control" id="searchInput" placeholder="Search lessons, categories...">
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="clearSearchBtn">Clear & Search Again</button>
      `;
      
      // Re-attach event listeners
      const newSearchInput = document.getElementById('searchInput');
      newSearchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          searchBtn.click();
        }
      });
      document.getElementById('clearSearchBtn').addEventListener('click', clearSearch);
    }

    // Display error message
    function displayError(message) {
      const modalBody = exampleModal.querySelector('.modal-body');
      modalBody.innerHTML = `
        <div class="alert alert-danger mb-3">
          <strong>Error:</strong> ${escapeHtml(message)}
        </div>
        <div class="mb-3">
          <label for="searchInput" class="form-label">Try your search again:</label>
          <input type="text" class="form-control" id="searchInput" placeholder="Search lessons, categories...">
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="clearSearchBtn">Clear & Search Again</button>
      `;
      
      // Re-attach event listeners
      const newSearchInput = document.getElementById('searchInput');
      newSearchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          searchBtn.click();
        }
      });
      document.getElementById('clearSearchBtn').addEventListener('click', clearSearch);
    }

    // Clear search and show input again
    function clearSearch() {
      const modalBody = exampleModal.querySelector('.modal-body');
      modalBody.innerHTML = `
        <div class="mb-3">
          <label for="searchInput" class="form-label">Enter search query:</label>
          <input type="text" class="form-control" id="searchInput" placeholder="Search lessons, categories...">
        </div>
      `;
      
      // Re-attach event listeners to new input
      const newSearchInput = document.getElementById('searchInput');
      newSearchInput.focus();
      newSearchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          searchBtn.click();
        }
      });
    }

    // Helper to escape HTML
    function escapeHtml(text) {
      const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
      };
      return text.replace(/[&<>"']/g, m => map[m]);
    }
  });
</script>