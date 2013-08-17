<? if ($params[0] == 'print') { ob_clean(); $print = true; include "auth.php"; } ?>
<? if ($params[0] == 'print'): ?>
<!doctype html>
<html>
<head>
 <title>Resume</title>

<style>
   body {
  font-size:16px;
  line-height:150%;
 }
#resume {
	margin:0px;
padding:1in;
	font-family:"Trebuchet MS", Helvetica, sans-serif;
}
#header {

	text-align:right;

}
h1, h2, h3 {

	font-variant: small-caps;
}

h3 {
  text-decoration:underline;
}

 </style>

</head>
<body>


<? endif; ?>
 <div id="resume">
  <div id='header'><h1>Zach Souser</h1><b><? if ($params[0] == 'print'): ?> (phone) 720.422.0119 (website) www.zsouser.com (email) zach.souser@gmail.com <? else: ?> <a href='/contact/'>Click Here to Contact Me</a> <? endif; ?> </b></div>
  <hr>
  <h3>Objective</h3>

  <p>To combine my passions for high-quality user experience and customer service with my capabilities as a programmer to design and develop useful software that will improve people's lives.</p> 
  <hr>
  <h3>Education</h3>
  <ul>
  	<li>Metropolitan State University of Denver<br>Expected Graduation Spring 2014<br>B.S. Computer Science, Minor Mathematics<br>3.06 GPA</li>
  	<li>Lehigh University<br>Pursued an Integrated Degree in Engineering, Arts and Sciences</li>
  </ul>
  <hr>
  <h3>Skills</h3>
  <b>Programming languages</b>
  <ul>
  	<li><b>Java</b></li>
  	<li><b>PHP (Zend Certified PHP 5.3 Engineer)</b></li>
  	<li><b>JavaScript</b></li>
  	<li><b>SQL</b></li>
  	<li><b>Ruby</b></li>
  </ul>

  <h3>Technologies used</h3>
  <ul>
  	<li>HTML5 / CSS3</li>
        <li>jQuery, AJAX, JSON and  XML</li>
        <li>MySQL, PostgreSQL, Oracle, and PHPMyAdmin</li>
  	<li>Linux/Unix Shell/SSH</li>
        <li>Git</li>
  </ul>
  <hr>
  <h3>Experience</h3> 
  <ul>
   <li><b><? if ($params[0] == 'print'): ?> Code Portfolio: www.github.com/zsouser<? else: ?> <a href='https://www.github.com/zsouser'>GitHub Portfolio</a><? endif; ?></b></li>

   <li><b>Barista, Starbucks Coffee Company - May 2011-Present</b><br>
   <ul>
   	<li>Work with a team of skilled professionals to make high quality products quickly</li>
   	<li>Provide legendary customer service to each customer</li>
   	<li>Multi-task between numerous store demands calmly and efficiently</li>
   	<li>Train and coach baristas on proper procedures and practices</li>
   </ul><br>
   </li>
   <li><b>Web Team, Lehigh University International Media Resource Center - August 2008-May 2010</b><br>
   <ul>
   	<li>Tested, debugged, refactored, and contributed to numerous on-campus organization websites</li>
   	<li>Set up and configured MySQL databases using both PHPMyAdmin and hand-coded SQL</li>
	<li>Worked on a remote server using a SSH connection</li>
	<li>Learned to work with Javascript and AJAX technologies</li>
  </ul>
  <hr>
  <h3>References available upon request</h3>
  </div>
  <? if ($params[0] == 'print'): ?>
</body>
</html>
<? endif; ?>
