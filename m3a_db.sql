-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2025 at 05:51 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `m3a_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_form`
--

CREATE TABLE `contact_form` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` int(11) NOT NULL,
  `comment` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_form`
--

INSERT INTO `contact_form` (`id`, `firstname`, `lastname`, `email`, `phone`, `comment`, `submitted_at`) VALUES
(0, 'Melken', 'Tumlos', 'tumolosmelken@gmail.com', 2147483647, 'hello', '2025-10-11 14:17:17'),
(0, 'Melken', 'Tumlos', 'tumolosmelken@gmail.com', 2147483647, 'fi', '2025-10-14 01:06:21');

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` enum('HTML','CSS','JavaScript','PHP','Svelte') NOT NULL,
  `content` text NOT NULL,
  `code_block` text DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `title`, `category`, `content`, `code_block`, `date_created`) VALUES
(1, 'Introduction', 'HTML', 'Hello', '<h1>hello</h1>', '2025-10-11 11:06:25'),
(3, 'Structures', 'HTML', '', NULL, '2025-10-12 03:41:05'),
(7, 'Text', 'HTML', '', NULL, '2025-10-13 15:44:16'),
(8, 'Color', 'CSS', '', NULL, '2025-10-14 01:12:05'),
(9, 'Introduction', 'PHP', '', NULL, '2025-10-17 05:13:40');

-- --------------------------------------------------------

--
-- Table structure for table `lesson_sections`
--

CREATE TABLE `lesson_sections` (
  `id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `heading` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `code_block` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lesson_sections`
--

INSERT INTO `lesson_sections` (`id`, `lesson_id`, `heading`, `content`, `code_block`) VALUES
(1, 1, 'What is HTML?', '<p>\r\n              <a href=\"#\" class=\"text-primary fw-bold\">Hypertext Markup Language (HTML)</a>\r\n              is the standard markup language for documents designed to be displayed in a web browser.\r\n              It defines the content and structure of web content. It is often assisted by technologies\r\n              such as <a href=\"#\" class=\"text-primary\">Cascading Style Sheets (CSS)</a> and scripting languages such as JavaScript.\r\n            </p>\r\n            \r\n            <p>\r\n              Web browsers receive HTML documents from a web server or from local storage and\r\n               render the documents into multimedia web pages. HTML describes the structure of a \r\n               web page semantically and originally included cues for its appearance.\r\n            </p>\r\n\r\n            <p>HTML elements are the building blocks of HTML pages. With HTML constructs, \r\n              images and other objects such as interactive forms may be embedded into the \r\n              rendered page. HTML provides a means to create structured documents by denoting \r\n              structural semantics for text such as headings, paragraphs, lists, links, quotes, \r\n              and other items. \r\n              \r\n            <p>HTML elements are delineated by tags, written using angle brackets. \r\n              Tags such as <code>&lt;img&gt;</code> and <code>&lt;input&gt;</code>\r\n               directly introduce content into the page.\r\n               Other tags such as <code>&lt;p&gt;</code> and <code>&lt;/p&gt;</code> \r\n              surround and provide information about document text and may include sub-element tags.\r\n               Browsers do not display the HTML tags, but use them to interpret the content of the page.\r\n            </p>\r\n\r\n            <p>HTML can embed programs written in a scripting language such as JavaScript,\r\n               which affects the behavior and content of web pages. The inclusion of CSS defines\r\n                the look and layout of content. The World Wide Web Consortium (W3C), former maintainer\r\n                 of the HTML and current maintainer of the CSS standards, has encouraged the use of \r\n                 CSS over explicit presentational HTML since 1997. A form of HTML, known as HTML5,\r\n                  is used to display video and audio, primarily using the <code>&lt;canvas&gt;</code> \r\n                  element, together with JavaScript.\r\n            </p>\r\n            <p><small>Source: <a href=\"https://en.wikipedia.org/wiki/HTML\">https://en.wikipedia.org/wiki/HTML</a></small></p>', ''),
(2, 3, 'HTML Describes the Structure of Pages', '<p>\r\n              To\r\n              describe the structure of a web page, we add code to the words we want\r\n              to appear on the page.\r\n            </p>\r\n            \r\n            <p>\r\n              You can see the HTML code for this page below. Don\'t worry about what\r\n              the code means yet. We start to look at it in more detail.\r\n            </p>\r\n<h5>Example</h5>', '<html>\r\n<body>\r\n <h1>This is the Main Heading</h1>\r\n <p>This text might be an introduction to the rest of \r\n the page. And if the page is a long one it might \r\n be split up into several sub-headings.<p>\r\n <h2>This is a Sub-Heading</h2>\r\n <p>Many long articles have sub-headings so to help \r\n you follow the structure of what is being written. \r\n There may even be sub-sub-headings (or lower-level \r\n headings).</p>\r\n <h2>Another Sub-Heading</h2>\r\n <p>Here you can see another sub-heading.</p>\r\n</body>\r\n</html>'),
(3, 3, '', 'The HTML code (in red) called HTML elements. Elements are usually \r\nmade up of two tags: an opening tag and a closing tag. (The closing tag \r\nhas an extra forward slash in it.) Each HTML element tells the browser \r\nsomething about the information that sits between its opening and \r\nclosing tags.', ''),
(9, 3, '<body>', 'You met the <code>&lt;body&gt;</code> element \r\nin the first example we created. \r\nEverything inside this element is \r\nshown inside the main browser \r\nwindow.', ''),
(10, 3, '<head>', 'Before the <code>&lt;body&gt;</code> element you \r\nwill often see a <code>&lt;head&gt;</code> element. \r\nThis contains information \r\nabout the page (rather than \r\ninformation that is shown within \r\nthe main part of the browser \r\nwindow that is highlighted in \r\nblue on the opposite page). \r\nYou will usually find a <code>&lt;title&gt;</code>\r\nelement inside the <code>&lt;head&gt;</code>\r\nelement.', ''),
(11, 3, '<title>', 'The contents of the <code>&lt;title&gt;</code>\r\nelement are either shown in the \r\ntop of the browser, above where \r\nyou usually type in the URL of \r\nthe page you want to visit, or \r\non the tab for that page (if your \r\nbrowser uses tabs to allow you \r\nto view multiple pages at the \r\nsame time).\r\n<h5>Example:</h5>', '<html>\r\n<head>\r\n <title>This is the Title of the Page</title>\r\n</head>\r\n<body>\r\n <h1>This is the Body of the Page</h1>'),
(12, 3, 'Summary', '<ul>\r\n    <li>\r\n        HTML pages are text documents.\r\n    </li>\r\n    <li>\r\n        HTML uses tags (characters that sit inside angled X\r\n        brackets) to give the information they surround special \r\n        meaning.\r\n    </li>\r\n    <li>\r\n        Tags are often referred to as elements.\r\n    </li>\r\n    <li>\r\n        Tags usually come in pairs. The opening tag denotes X\r\n        the start of a piece of content; the closing tag denotes \r\n        the end.\r\n    </li>\r\n    <li>\r\n        Opening tags can carry attributes, which tell us more X\r\n        about the content of that element.\r\n    </li>\r\n    <li>\r\n        Attributes require a name and a value.\r\n    </li>\r\n    <li>\r\n        To learn HTML you need to know what tags are X\r\n        available for you to use, what they do, and where they \r\n        can go.\r\n    </li>\r\n</ul>', ''),
(15, 7, 'Headings (<h1> <h2> <h3> <h4> <h5> <h6>)', '<p>\r\n    HTML has six \"levels\" of \r\n    headings:\r\n</p>\r\n\r\n\r\n<p>\r\n    <code>&lt;h1&gt;</code> is used for main headings\r\n    <code>&lt;h2&gt;</code> is used for subheadings\r\n    If there are further sections \r\n    under the subheadings then the \r\n    <code>&lt;h3&gt;</code> element is used, and so \r\n    on...\r\n</p>\r\n\r\n<p>\r\n    Browsers display the contents of \r\n    headings at different sizes. The \r\n    contents of an <code>&lt;h1&gt;</code> element is \r\n    the largest, and the contents of \r\n    an <code>&lt;h6&gt;</code> element is the smallest.\r\n    Users can also \r\n    adjust the size of text in their \r\n    browser. You will see how to \r\n    control the size of text, its color, \r\n    and the fonts used when we \r\n    come to look at CSS.\r\n</p>', '<h1>This is a Main Heading</h1>\r\n<h2>This is a Level 2 Heading</h2>\r\n<h3>This is a Level 3 Heading</h3>\r\n<h4>This is a Level 4 Heading</h4>\r\n<h5>This is a Level 5 Heading</h5>\r\n<h6>This is a Level 6 Heading</h6>'),
(16, 7, 'Paragraph (<p>)', '<p>\r\n    To create a paragraph, surround \r\n    the words that make up the \r\n    paragraph with an opening <code>&lt;p&gt;</code>\r\n    tag and closing <code>&lt;p&gt;</code> tag.\r\n</p>\r\n\r\n<p>\r\n    By default, a browser will show \r\n    each paragraph on a new line \r\n    with some space between it and \r\n    any subsequent paragraphs.\r\n</p>', '<p>A paragraph consists of one or more sentences \r\n that form a self-contained unit of discourse. The \r\n start of a paragraph is indicated by a new \r\n line.</p>\r\n<p>Text is easier to understand when it is split up \r\n into units of text. For example, a book may have \r\n chapters. Chapters can have subheadings. Under \r\n each heading there will be one or more \r\n paragraphs.</p>'),
(17, 7, 'Bold (<b>)', '<p>\r\n    By enclosing words in the tags \r\n    <code>&lt;b&gt;</code> and <code>&lt;b&gt;</code> we can make \r\n    characters appear bold.\r\n    The <code>&lt;b&gt;</code> element also represents \r\n    a section of text that would be \r\n    presented in a visually different \r\n    way (for example key words in a \r\n    paragraph) although the use of \r\n    the <code>&lt;b&gt;</code> element does not imply \r\n    any additional meaning.\r\n</p>', '<p>This is how we make a word appear <b>bold.</b>\r\n </p>\r\n<p>Inside a product description you might see some \r\n<b>key features</b> in bold.</p>'),
(18, 7, 'Italic (<i>)', '<p>\r\n    By enclosing words in the tags \r\n    <code>&lt;i&gt;</code> and <code>&lt;i&gt;</code> we can make \r\n    characters appear italic.\r\n</p>\r\n\r\n<p>\r\n    The <code>&lt;i&gt;</code> element also represents \r\n    a section of text that would be \r\n    said in a different way from \r\n    surrounding content — such as \r\n    technical terms, names of ships, \r\n    foreign words, thoughts, or other \r\n    terms that would usually be \r\n    italicized.\r\n</p>', '<p>This is how we make a word appear <i>italic</i>.\r\n </p>\r\n<p>It\'s a potato <i>Solanum teberosum</i>.</p>\r\n<p>Captain Cook sailed to Australia on the \r\n<i>Endeavour</i>.</p>'),
(19, 8, 'color ', 'hn', '<style>\r\nh1 {\r\ncolor: blue;\r\n}\r\n</style>\r\n<h1>Hello<h1>'),
(20, 8, 'Summary', '<ul>\r\n<li>HEllo</li>\r\n<li>HEllo</li>\r\n<li>HEllo</li>\r\n<li>HEllo</li>\r\n<li>HEllo</li>\r\n</ul>', ''),
(21, 9, '', '<div class=\"alert alert-success\">\r\n<h5>Objectives</h5>\r\nAfter this lesson, the student should have learned:\r\n<ul>\r\n<li>To know the basic PHP;</li>\r\n<li>To be familiar with the tools and requirements used in starting PHP; and</li>\r\n<li>To create program using XAMMP and WAMPP.</li>\r\n<ul>\r\n</div>', ''),
(22, 9, 'Hypertext Preprocessor (PHP)', '<b>PHP (recursive acronym for \"Personal Home Page or PHP\": Hypertext Preprocessor)</b>\r\n<p>\r\n              <a href=\"\"class=\"text-primary\">PHP</a> Is one of the most popular server side scripting languagers used for web development. It is originally\r\n              known as Personal Home Page created in 1991 by Rasmus Lerdorf but it was changed to Hypertext Preprocessor.\r\n              Now it has version 4.0.3 with numerous improvements and refinement over the original release\r\n            </p>\r\n <p>\r\n             PHP is free to use provided that the computer has the Web server that supports PHP. PHP files contain HTML tags \r\n             and scripts with extension name of the php and can be run in any browser. PHP files can run in differednt platforms\r\n             like <a href=\"\"class=\"text-primary\">Windows</a>, <a href=\"\"class=\"text-primary\">Linux</a>, <a href=\"\"class=\"text-primary\">Unix</a>, etc. and supports many different types of databases.\r\n            </p>\r\n <p>PHP is a server side scripting language that is embedded in HTML. it is used to manage dynamic content, databases,\r\n              session tracking, even buikd entire e-commerce sites. It is integrated with a number of popular databases, including <a href=\"\"class=\"text-primary\">MySQL</a>, <a href=\"\"class=\"text-primary\">PostgreSQL</a>, <a href=\"\"class=\"text-primary\">Oracle</a>,\r\n              <a href=\"\"class=\"text-primary\">Sybase</a>, <a href=\"\"class=\"text-primary\">Informix</a>, and <a href=\"\"class=\"text-primary\">Microsoft SQL Server</a>. \r\n<br>\r\n<p><small>Source: <a href=\"https://ils.csucarig.edu.ph/cgi-bin/koha/opac-detail.pl?biblionumber=16010#:~:text=PHP%20with%20MySQL%20%3A%20a%20web%20programming%20language,pages%20%3A%20illustrations%20%3B%2026%20cm.%20ISBN%3A%209786214060214.\">PHP with MySQL A Web Programming Language by Jake R. and Kevin M.</a></p>\r\n', ''),
(23, 9, '', ' <h5>Common uses of PHP:</h5>\r\n              <ul>\r\n                <li>\r\n                  <p> It performs system function, i.e. from fiels on the system it can creater, open, read, write, and close them.</p>\r\n                </li>\r\n                <li>\r\n                  <p>It can handle forms i,e gather data from files, save data to a file, thru email send, data, return data to the user.</p>\r\n                </li>\r\n                <li>\r\n                  <p>It can add, delete, modify elements within your database thru PHP.</p>\r\n                </li>\r\n                <li>\r\n                  <p>Access cookies variables and set cookies.</p>\r\n                </li>\r\n                <li>\r\n                  <p>It can restrict users to access some pages of your website.</p>\r\n                </li>\r\n                <li>\r\n                  <p>It can encrypt data.</p>\r\n                </li>\r\n              </ul>\r\n\r\n<p>PHP is embedded in HTML. (You could have a file that contains almost no HTML, but usually it\'s a mixture.). That means that in among your normal HTML (or XHTML if you\'re cutting edge) you\'ll have PHP statements like this:\r\n            </p>', ''),
(24, 9, 'Tools to Build PHP', '<ol>\r\n<li>\r\n<b>Programming Editor</b>\r\n<p>This is needed in creating the codes in PHP. Examples are <a>Notepad</a>, <a>Notepad++</a>, Dreamwaver.</p>\r\n</li>\r\n<li>\r\n<b>Intergrated Development Editor</b>\r\n<p>Features programming editor, debugging, previewing, testing, <a href=\"#\">FTP</a>, <a href=\"#\">Project Management</a>. Examples are <a href=\"#\">Dreamwaver</a>, <a href=\"#\">Zend</a>, <a href=\"#\">FrontPage</a>.</p>\r\n</li>\r\n</ol>', ''),
(25, 9, 'How to Start', '<ol>\r\n<li>Install apache (or ISS) in your computer. Make sure to disable ISS if you are using Apache to avoid conflict with Apache.</li>\r\n<li>Install PHP and MySQL</li>\r\n<li> Aside from Tthe steps mentioned aove, users can simply download Wamp(Windows Apache, MySQL, and PHP)\r\n             or XAMPP (for Windows platform) and LAMP (Linux, Apache, MySQL, and PHP, for LINUX platform.)</li>\r\n</ol>', ''),
(26, 9, 'Using XAMPP', ' <p>1. After installing XAMPP, it will be placed under My Computer folder. Double click the folderand select xampp-control. </p>\r\n\r\n\r\n\r\n             <p>2. Users should click Start button of Apache and MySQL to succesfully create PHP files.</p>\r\n\r\n\r\n\r\n             <p>3. Since Apache and MySQL are Already running, users can now start creating PHP files.</p>\r\n\r\n             <p>4. Users should create a folder inside My Computer>XAMPP> htdocs and place all PHP files inside the folder that users created.</p>', ''),
(27, 9, 'Saving PHP Files', '<p>1.Open drive c:/ </p>\r\n            <p>2.Open xampp folder</p>\r\n            <p>3.Open htdocs folder</p>\r\n            <p>4.Create folder inside htdocs folder (you can create your own folder name)</p>\r\n            <p>5.Put all your PHP files inside the folder that you created.</p>\r\n            <p>6.To run your program, open any browser and type. (Make sure that all service were turned on)</p>\r\n\r\n<u> Localhost/folder_name/php file_name.php</u>', ''),
(28, 9, 'Create PHP Program', '<p>1. PHP scripts are written like this:<? and ?> tags.(Users can use short tag of <? and ?> tags but make sure to turn on the short tag command).</p>\r\n              \r\n            <p><strong>for XAMPP:</strong> Open My Computer > Drive C:> XAMPP></p>\r\n\r\n            <p>2.Open Notepad or Notepad++</p>\r\n            <p>3.PHP codes can be embedded in HTML files and vice versa. But make sure to use the command to echo if HTML tags.</p>\r\n\r\n<h5>Example</h5>', '<html>\r\n<body>\r\n<h1>This basic PHP program</h1>\r\n<?php\r\necho \"Welcome to PHP\";\r\n?>\r\n</body>\r\n</html>'),
(29, 9, '', '<p> In the above example, PHP codes are written inside HTML tags. The command echp is used to display text in PHP files and be</p>\r\n            <h5>Example:</h5>', '<?php\r\necho\"<html>\";\r\necho\"<body>\";\r\necho\"<h1>This is basic PHP program </h1>\";\r\necho\"<br/> Welcome to PHP!!!\";\r\necho\"</body>\";\r\necho\"</html>\";\r\n'),
(30, 9, '', '<p> in the example above, HTML tags were written inside PHP codes and notice that every HTML tags ae enclosed with echo command. This is necessary to recognize HTML tags in PHP files.</p>\r\n<p>Example below is the same as example above the command echo which is also acceptable in using PHP.</p>\r\n            \r\n            <h5>Example:</h5>', '<?php\r\necho \"<html>\r\n<body>\r\n<h1>This is basic PHP program</h1>\r\n<br/> Welcome to PHP!!!\r\n</body>\r\n</html>\";\r\n?>'),
(31, 9, 'Semicolon', '<p>As you may or may not have noticed in the above example, there was a semicolon after the line of PHP code. The semicolon signifies the end of a PHP statement and should never be forgetten. For example, if we have repeated our \"Hello Student!\" code severa; times, then we would need to place a semicolon at the end of each statement. </p>\r\n\r\n            <h5>Example:</h5>', '<html>\r\n<head>\r\n<title>PHP comment</title>\r\n</head>\r\n<style>\r\nbody {\r\nbackground-color: green;\r\ncolor: white;\r\n</style>\r\n<body>\r\n<?php\r\necho \"Hello Student<br/>\";\r\necho \"How are you today?<br/>\";\r\necho \"Nice to see you.<br/>\";\r\necho \"Welcome to the PHP Programming<br/>\";\r\n?>\r\n</body>\r\n</html>');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_option` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `category_id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`) VALUES
(1, 1, 'What is HTML?', 'Hypertext Markup Language', 'Hyper Mark Link', 'Hyperlink Makeup Language', 'Half MAke List', 'A'),
(2, 1, 'what is <p>', 'paragraph', 'phase', 'plane', 'power', 'a');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_categories`
--

CREATE TABLE `quiz_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_categories`
--

INSERT INTO `quiz_categories` (`id`, `name`) VALUES
(1, 'HTML'),
(2, 'CSS'),
(3, 'JavaScript'),
(4, 'PHP'),
(5, 'Svelte');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '1234', 'admin'),
(2, 'user1', 'abcd', 'user'),
(5, 'user2', '123', 'user'),
(6, 'user3', '123', 'user'),
(7, 'princewilson15', '12345678', 'user'),
(8, 'user4', '123', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lesson_sections`
--
ALTER TABLE `lesson_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `quiz_categories`
--
ALTER TABLE `quiz_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `lesson_sections`
--
ALTER TABLE `lesson_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `quiz_categories`
--
ALTER TABLE `quiz_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `lesson_sections`
--
ALTER TABLE `lesson_sections`
  ADD CONSTRAINT `lesson_sections_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `quiz_categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
