<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home | M2a</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="assets/icons/font/bootstrap-icons.min.css">
    <style>
      .learn-card {
        border-radius: 18px;
        overflow: hidden;
        transition: transform 0.35s ease, box-shadow 0.35s ease;
        position: relative;
      }
      .learn-card::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        background: radial-gradient(circle at top, rgba(255,255,255,0.25), transparent 60%);
        opacity: 0;
        transition: opacity 0.35s ease, transform 0.35s ease;
        pointer-events: none;
      }
      .learn-card:hover {
        transform: translateY(-8px) scale(1.015);
        box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2);
      }
      .learn-card:hover::after {
        opacity: 1;
        transform: scale(1.1);
      }
    </style>
</head>

<body>
    <?php include 'nav.php'; ?>
<main role="main" class="pt-5">
    <div data-bs-spy="scroll" data-bs-target="#navbar-example2" data-bs-root-margin="0px 0px -40%" data-bs-smooth-scroll="true" class="scrollspy-example rounded-2" tabindex="0">

      <div id="scrollspyHome">

        <header class="p-4 row g-0" style="background: #1800AD;
            background: linear-gradient(90deg, rgba(24, 0, 173, 1) 0%,
             rgba(21, 112, 255, 1) 50%, rgba(92, 225, 232, 1) 100%);">
            <div class="col-md-8 p-4 container">
            <div class="my-2" style="display:flex; justify-content:center;">
            
              <img src="assets/icon.png" alt="logo" height="120" width="120">
            
            </div>
            <h1 class="text-center text-light" style="text-shadow:0 0 1px #00f, 0 0 3px #00f, 0 0 7px #00f; font-size: 5rem;"><b>M2a</b></h1>
            <h4 class="text-center text-light">Programming Languages Learning Guide</h4>
            <p class="text-center text-light fs-6">A Learning Education Coding for Upcoming Programmers/IT Students</p>
            </div>
        </header>
        <hr class="text-primary">
        <div class="container my-4">
        <div class="row row-cols-1 row-cols-md-2 g-4">

          <div class="col">
            <div class="card learn-card border-2 border-primary shadow-lg">
              <div class="row g-0">
                <div class="col-md-4 ps-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="150" height="auto" fill="#F57C00" class="bi bi-code-slash" viewBox="0 0 16 16">
                  <path d="M10.478 1.647a.5.5 0 1 0-.956-.294l-4 13a.5.5 0 0 0 .956.294zM4.854 4.146a.5.5 0 0 1 0 .708L1.707 8l3.147 3.146a.5.5 0 0 1-.708.708l-3.5-3.5a.5.5 0 0 1 0-.708l3.5-3.5a.5.5 0 0 1 .708 0m6.292 0a.5.5 0 0 0 0 .708L14.293 8l-3.147 3.146a.5.5 0 0 0 .708.708l3.5-3.5a.5.5 0 0 0 0-.708l-3.5-3.5a.5.5 0 0 0-.708 0"/>
                  </svg>
                </div>
                <div class="col-md-8">
                  <div class="card-body">
                    <b>HyperText Markup Language (HTML)</b>
                    <small class="text-wrap mb-1">is the standard markup language for documents displayed in a web browser.</small>
                    <hr>
                    <a href="category.php?cat=HTML" class="btn btn-primary">Learn HTML</a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col">
            <div class="card learn-card border-2 border-primary shadow-lg">
              <div class="row g-0">
                <div class="col-md-4 ps-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="150" height="auto" fill="#5E35B1" class="bi bi-css" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M14 0a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V0zM4.59 7.498q-.908 0-1.455.508-.547.507-.547 1.484v3.106q0 .986.527 1.484t1.406.498q.576 0 1.016-.224.45-.225.703-.674.255-.45.254-1.114v-.185h-1.22v.176q0 .449-.186.683t-.527.235q-.372-.01-.557-.264-.186-.255-.186-.752V9.686q0-.547.166-.811.177-.264.577-.264.321 0 .517.225.195.224.195.693v.205h1.23V9.52q0-.674-.243-1.124a1.55 1.55 0 0 0-.664-.673q-.42-.225-1.006-.225m4.214-.01q-.586 0-1.006.244a1.67 1.67 0 0 0-.635.674 2.1 2.1 0 0 0-.225.996q0 .753.293 1.182.304.42.967.732l.469.215q.44.186.625.43.186.244.186.635 0 .478-.166.703-.157.224-.528.224-.36 0-.547-.244-.185-.243-.205-.752H6.87q.02.996.498 1.524.479.527 1.387.527t1.416-.518.508-1.484q0-.81-.332-1.289-.333-.479-1.045-.79l-.45-.196q-.39-.166-.556-.381-.165-.214-.166-.576 0-.4.166-.596.175-.195.508-.195.36 0 .508.234.156.234.175.703h1.123q-.03-.976-.498-1.484-.468-.518-1.308-.518m4.057 0q-.585 0-1.006.244a1.67 1.67 0 0 0-.634.674 2.1 2.1 0 0 0-.225.996q0 .753.293 1.182.303.42.967.732l.469.215q.438.186.625.43.185.244.185.635 0 .478-.166.703-.156.224-.527.224-.361.001-.547-.244-.186-.243-.205-.752h-1.162q.02.996.498 1.524.479.527 1.386.527.909 0 1.417-.518.507-.517.507-1.484 0-.81-.332-1.289t-1.045-.79l-.449-.196q-.39-.166-.556-.381-.166-.214-.166-.576 0-.4.165-.596.177-.195.508-.195.361 0 .508.234.156.234.176.703h1.123q-.03-.976-.498-1.484-.47-.518-1.309-.518"/>
                  </svg>
                </div>
                <div class="col-md-8">
                  <div class="card-body">
                    <b>Cascading Style Sheet (CSS)</b>
                    <small class="card-text">is a style sheet language used for specifying the presentation and styling of a document...</small>
                    <hr>
                    <a href="category.php?cat=CSS" class="btn btn-primary">Learn CSS</a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col">
            <div class="card learn-card border-2 border-primary shadow-lg">
              <div class="row g-0">
                <div class="col-md-4 ps-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="150" height="auto" fill="#FFC400" class="bi bi-javascript" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M14 0a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zM9.053 7.596v3.127l-.007 1.752q0 .498-.186.752t-.556.263q-.342 0-.528-.234-.185-.234-.185-.684v-.175H6.37v.185q0 .665.253 1.113.255.45.703.674.44.225 1.016.225.88 0 1.406-.498.527-.498.527-1.485l.007-1.752V7.596zm3.808-.108q-.585 0-1.006.244a1.67 1.67 0 0 0-.634.674 2.1 2.1 0 0 0-.225.996q0 .753.293 1.182.303.42.967.732l.469.215q.438.186.625.43.185.244.185.635 0 .478-.166.703-.156.224-.527.224-.361.001-.547-.244-.186-.243-.205-.752h-1.162q.02.996.498 1.524.479.527 1.386.527.909 0 1.417-.518.507-.517.507-1.484 0-.81-.332-1.289t-1.045-.79l-.449-.196q-.39-.166-.556-.381-.166-.214-.166-.576 0-.4.165-.596.177-.195.508-.195.361 0 .508.234.156.234.176.703h1.123q-.03-.976-.498-1.484-.47-.518-1.309-.518"/>
                  </svg>
                </div>
                <div class="col-md-8">
                  <div class="card-body">
                    <b>JavaScript (JS)</b>
                    <small class="text-wrap mb-1"> is a programming language and core technology of the web platform, alongside HTML and CSS. </small>
                    <hr>
                    <a href="category.php?cat=JavaScript" class="btn btn-primary">Learn JS</a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col">
            <div class="card learn-card border-2 border-primary shadow-lg">
              <div class="row g-0">
                <div class="col-md-4 ps-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="150" height="auto" fill="#01579B" viewBox="0 0 24 24">
                    <path d="M7.01 10.207h-.944l-.515 2.648h.838c.556 0 .97-.105 1.242-.314c.272-.21.455-.559.55-1.049c.092-.47.05-.802-.124-.995c-.175-.193-.523-.29-1.047-.29zM12 5.688C5.373 5.688 0 8.514 0 12s5.373 6.313 12 6.313S24 15.486 24 12c0-3.486-5.373-6.312-12-6.312zm-3.26 7.451c-.261.25-.575.438-.917.551c-.336.108-.765.164-1.285.164H5.357l-.327 1.681H3.652l1.23-6.326h2.65c.797 0 1.378.209 1.744.628c.366.418.476 1.002.33 1.752a2.836 2.836 0 0 1-.305.847c-.143.255-.33.49-.561.703zm4.024.715l.543-2.799c.063-.318.039-.536-.068-.651c-.107-.116-.336-.174-.687-.174H11.46l-.704 3.625H9.388l1.23-6.327h1.367l-.327 1.682h1.218c.767 0 1.295.134 1.586.401s.378.7.263 1.299l-.572 2.944h-1.389zm7.597-2.265a2.782 2.782 0 0 1-.305.847c-.143.255-.33.49-.561.703a2.44 2.44 0 0 1-.917.551c-.336.108-.765.164-1.286.164h-1.18l-.327 1.682h-1.378l1.23-6.326h2.649c.797 0 1.378.209 1.744.628c.366.417.477 1.001.331 1.751zm-2.595-1.382h-.943l-.516 2.648h.838c.557 0 .971-.105 1.242-.314c.272-.21.455-.559.551-1.049c.092-.47.049-.802-.125-.995s-.524-.29-1.047-.29z"/>
                  </svg>
                </div>
                <div class="col-md-8">
                  <div class="card-body">
                    <b>Hypertext Preprocessor (PHP)</b>
                    <small class="text-wrap mb-1">is a free and open-source component-based front-end software framework, and...</small>
                    <hr>
                    <a href="category.php?cat=PHP" class="btn btn-primary">Learn PHP</a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col">
            <div class="card learn-card border border-primary shadow-sm">
              <div class="row g-0">
                <div class="col-md-4 ps-2">
                  <img src="assets/img/Svelte_logo_by_gengns.svg.png" class="img-fluid rounded-start" width="150" height="auto" alt="...">
                </div>
                <div class="col-md-8">
                  <div class="card-body">
                    <b class+="position-relative">Svelte</b>
                    <small class="text-wrap mb-1">is the standard markup language for documents designed to be displayed in a web browser.</small>
                    <hr>
                    <a href="category.php?cat=Svelte" class="btn btn-primary">Learn Svelte</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      
      </div>
<hr class="text-primary">
      <div id="scrollspyContact" data-bs-theme="light" class="container">
        <div class="mt-3 py-5 ">

          <form action="contact.php" method="post" class="row g-3 p-4 border border-primary shadow-lg rounded-3 mb-3">
            <h3 class="mt-2">Contact Us</h3>
            <p class=" mb-2">If you have any questions or feedback, feel free to reach out to us!</p>
              <div class="col-md-6">
                <label for="firstname" class="form-label">First Name:</label>
                <input type="text" name="firstname" class="form-control" placeholder="First Name" required>
              </div>

              <div class="col-md-6">
                <label for="lastname" class="form-label">Last Name:</label>
                <input type="text" name="lastname" class="form-control" placeholder="Last Name" required>
              </div>

              <div class="col-md-8">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" placeholder="Email" required>
              </div>

              <div class="col-md-4">
                <label for="phone" class="form-label">Phone:</label>
                <input type="tel" name="phone" class="form-control" placeholder="+63 917 888 7777" required>
              </div>

              <div class="col-md-12">
                <label for="message" class="form-label">Comment/Message/Question/Feedback:</label>
                <textarea class="form-control" name="comment" rows="3" placeholder="Write your message here..." required></textarea>
              </div>

              <div class="col-md-12">
                <input type="submit" value="Send" class="btn btn-primary text-light mt-3">
              </div>

          </form>

        </div>

      </div>

        </div>

    </div> 
  </main>

  <div class="bg-black">
    <div class="container">
      <footer class="py-3">
      <p class="text-center text-white">&copy;Copyright. All rights recieved. Capstone Project 2025.</p>
      </footer> 
    </div>
</div>
  <script src="js/bootstrap.bundle.min.js"></script>
<?php if (isset($_SESSION['toast'])): ?>
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div class="toast text-bg-<?php echo $_SESSION['toast']['type']; ?> show align-items-center border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <?php echo $_SESSION['toast']['message']; ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<?php unset($_SESSION['toast']); ?>
<?php endif; ?>
</body>
</html>
