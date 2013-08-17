<?php 
if (is_string($_POST['name']) && 
		is_string($_POST['email']) && 
		is_string($_POST['message'])) {
	if(mail("zach.souser@gmail.com", "Contacted By ".$_POST['name'], "Phone: ".$_POST['phone']."\n".$_POST['message'],"Reply-To: ".$_POST['email']))
		echo "<center>Thank you for your submission! I'll get back to you shortly</center>";
	else echo "ERROR";
}

else {
?>
Please leave your name, email, or a brief message. <br><br>
Optionally, leave a phone number I can reach you at.
<br><br>
<fieldset><legend>Contact Form</legend>
<form method='post'>
<ul>
<li>Name:<br><input name='name'></li>
<li>Email:<br><input name='email'></li>
<li>Phone:<br><input name='phone'></li>
<li>Message<br><textarea name='message'></textarea></li>
</ul>
<center><button id='contact-submit'>Submit</button></center>
</form>
</fieldset>
<?php } ?>