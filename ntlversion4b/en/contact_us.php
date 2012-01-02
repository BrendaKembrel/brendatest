<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
   
<html xmlns="http://www.w3.org/1999/xhtml">
   
   <head>
		
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="css/ntlcurrent.css" />
		
		<title>Contact Us | Contact the Bra Doctor | Personal Lingerie Fitting | Now That's Lingerie</title>
		<meta name="description" content="Contact Now That's Lingerie's Bra Doctor for personalized fitting help, and speak with our customer support for questions and concerns" />
		<meta name="keywords" content="" />
		
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<script type="text/javascript" src="scripts/tylerfunctions.js"></script>
		
		<script type="text/javascript">
		
			$(document).ready(function()
				{
					$("#shipping").hide();
					$("#returns").hide();
					$("#privacy").hide();
					$("#jobs").hide();
					$("#firstnamefail").hide();
					$("#emailfail").hide();
					$("#subjectfail").hide();
					$("#questionfail").hide();
					$("#messagefail").hide();
				});
				
			function showhideshipping(){ $("#shipping").toggle(); }
			function showhidereturns(){ $("#returns").toggle(); }
			function showhideprivacy(){ $("#privacy").toggle(); }
			function showhidejobs(){ $("#jobs").toggle(); }
				
		
		</script>
		
		<script type="text/javascript">
		
			function validateForm()
			{
				var x=document.forms["contactform"]["name"].value
				var y=document.forms["contactform"]["email"].value
				var z=document.forms["contactform"]["subject"].value
				var a=document.forms["contactform"]["question"].value
				var b=document.forms["contactform"]["message"].value
				
				var firstnamecheck = 0;
				var emailcheck = 0;
				var subjectcheck = 0;
				var questioncheck = 0;
				var messagecheck = 0;
				
				if (a=="na") {
					$("#questionfail").show();
					questioncheck = 0;
				}
					
				else {
					$("#questionfail").hide();
					questioncheck = 1;
				}
				
				  
				if (x==null || x=="") {
					$("#firstnamefail").show();
					firstnamecheck = 0;
				}
					
				else {
					$("#firstnamefail").hide();
					firstnamecheck = 1;
				}
				
				  
				if (y==null || y=="") {
					$("#emailfail").show();
					emailcheck = 0;
				}
				
				else  {
					$("#emailfail").hide();
					emailcheck = 1;
				}
				
				var atpos=y.indexOf("@");
				var dotpos=y.lastIndexOf(".");	

				if (atpos<1 || dotpos<atpos+2 || dotpos+2>=y.length)
				{
					$("#emailfail").show();
					emailcheck = 0;
				}
				else {
					emailcheck = 1;
				}
				  
				if (z==null || z=="") {
					$("#subjectfail").show();
					subjectcheck = 0;
				}
				
				else  {
					$("#subjectfail").hide();
					subjectcheck = 1;
				}
				
				  
				if (b=="Write out your question in full here.  Please be specific and clear in your request so that we can provide you with the best service possible. If your message concerns return or exchange, please provide your order number. If your message is about a specific item on our website, please include the item number and brand.") {
					$("#messagefail").show();
					messagecheck = 0;
				}
				
				else  {
					$("#messagefail").hide();
					messagecheck = 1;
				}
				
				
				
				if (emailcheck == 0 || firstnamecheck == 0 || subjectcheck == 0 || questioncheck == 0) {
					return false
				}
			}
		
		</script>
		
		</script>

	<?php
	
		if ($_GET["sent"] == 1)
		{
			$name = $_GET["name"];
			$email = $_GET["email"];
			$subject = $_GET["subject"];
			$question = $_GET["question"];
			$message = $_GET["message"];
			
			$to      = 'celine@nowthatslingerie.com';
			$subject = $question . " - " . $subject;
			$message = $subject . "\n\n" . $message;
			$headers = "From:" . $email . "\r\n";

			mail($to, $subject, $message, $headers);
			
			$to2      = $email;
			$subject2 = "Your email to Now That's Lingerie has been received";
			$messagepreface = "Hello " . $name . ",\n\n Your email to Now That's Lingerie.com has been received. You should receive a response within 48 business hours. \n\n Here is a copy of the message that was received: \n\n";
			$messagefooter = "\n\n\n Please do not reply to this email.  All messages should be directed to celine@nowthatslingerie.com";
			$message2 = $messagepreface . "\n" . $message . $messagefooter;
			$headers2 = "From: Now That's Lingerie Support <donotreply@nowthatslingerie.com>";

			mail($to2, $subject2, $message2, $headers2);
		}
	
	?>

		
	</head>

	<body>
				 
	
		<div id="sitewrapper">
		
			<? include("includes/header.php"); ?>
			<? include("includes/menu.php"); ?>
			
			<div id="sitecontent">
			
				<div id="siterefinementcol">
				
					<div id="siteournewsletter">
					
						<p class="sitesectionheadertext">Our Newsletter</p>
					
					</div>
					
					<div id="siteournewslettercontainer">
					
					<form action="/" style="margin:0 0 5px 0;">
					
						<input name="email" type="text" value="Email Address" class="sitetextinput" style="margin:4px 0 2px 0;"  ONFOCUS="clearDefault(this)" onblur="restoreDefault(this)"/>
						<input name="name" type="text" value="First Name" class="sitetextinput" style="margin:2px 0 4px 0;"  ONFOCUS="clearDefault(this)" onblur="restoreDefault(this)" />
					
						<div class="siteemailchoices">
						
							<p style="font-size:14px;margin:2px 0 3px 4px">I'd like to receive...</p>
							
							<input name="site" type="checkbox" value="1" checked />
							<label style="font-size:10px;" for="site">Site Updates and Promotions</label>
							
							<br />
							
							<input name="blog" type="checkbox" value="1" checked />
							<label style="font-size:10px;" for="blog">Blog Updates</label>
						
						</div>
						
					<div style="margin-top:5px;width:100%;text-align:center;"><input type="image" src="images/signuptoday.png" /></div>
						
					</form>
					
					</div>
					
					
					<div class="sitesectionheader">
					
					<p class="sitesectionheadertext">Search by...</p>
					
					</div>
					
					<div class="brandleftcolcontent2">
					
					<form action="/">
					
						<select style="font-size:14px;width:200px;margin:3px 0 0 0;">
						
							  <option style="font-size:14px;">What are you looking for?</option>
							  <option style="font-size:14px;">Option 1</option>
							  <option style="font-size:14px;">Option 2</option>
							  
						</select>
						
						<select style="font-size:14px;width:200px;margin:3px 0 0 0;">
						
							  <option style="font-size:14px;">Now let's refine that...</option>
							  <option style="font-size:14px;">Option 1</option>
							  <option style="font-size:14px;">Option 2</option>
							  
						</select>
						
						<select style="font-size:14px;width:200px;margin:3px 0 0 0;">
						
							  <option style="font-size:14px;">What size do you wear?</option>
							  <option style="font-size:14px;">Option 1</option>
							  <option style="font-size:14px;">Option 2</option>
							  
						</select>
						
						<select style="font-size:14px;width:200px;margin:3px 0 0 0;">
						
							  <option style="font-size:14px;">What brand do you like?</option>
							  <option style="font-size:14px;">Option 1</option>
							  <option style="font-size:14px;">Option 2</option>
							  
						</select>
						
						<select style="font-size:14px;width:200px;margin:3px 0 3px 0;">
						
							  <option style="font-size:14px;">What color do you like?</option>
							  <option style="font-size:14px;">Option 1</option>
							  <option style="font-size:14px;">Option 2</option>
							  
						</select>
					
					</form>
					
					</div>

				</div>
				
				<div id="siterightcol">
				
					<div style="text-align:center;"><h1 class="sitepagehead">Contact Us</h1></div>
					
					<p style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;">Need personalized bra fitting advice from our Bra Doctor? Do you have questions, comments or feedback for us? We're happy to hear from you!</p>
					
					<p style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;">If your question relates to any of the following subjects, please follow the links below to try and find your answer prior to contacting us. If your question is not answered by the information provided, please let us know and we'd be happy to help you.</p>
					
					<p style="text-align:left;margin:5px 0;font-size:14px;line-height:140%;cursor:pointer;" class="siteboldlink" onclick="showhideshipping()" >Shipping and Handling</p>
					
					<div style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;" id="shipping">
					
					<p style="text-align:left;margin:9px 0;font-size:14px;line-height:140%;font-weight:bold;">How much does shipping cost?</p>
					<p style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;width:500px;">Shipping is a flat rate of 8.99 USD (or CAD).  There are some exceptions to this, as stated below (see "Do you ship internationally?" and "Do you offer a faster shipping service" for details.)</p> 
					
					<p style="text-align:left;margin:9px 0;font-size:14px;line-height:140%;font-weight:bold;">Is my shipping fee refundable?</p>
					<p style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;width:500px;">Shipping and handling costs are non-refundable and are charged when you make the original purchase.</p> 
					
					<p style="text-align:left;margin:9px 0;font-size:14px;line-height:140%;font-weight:bold;">How long does it take for an order to ship?</p>
					<p style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;width:500px;">Shipping can take anywhere from 4-15 business days excluding weekends and holidays.  When we ship your order, you will receive an email to let you know what has been sent.</p> 
					
					<p style="text-align:left;margin:9px 0;font-size:14px;line-height:140%;font-weight:bold;">Do you ship internationally?</p>
					<p style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;width:500px;">Yes, we do - we ship worldwide. Please note that for orders outside of the United States and Canada, you may be subject to additional shipping charges for any orders exceeding 3/4 of a pound.  If this occurs, we'll email you to work out the difference prior to shipping your order.</p> 
					
					<p style="text-align:left;margin:9px 0;font-size:14px;line-height:140%;font-weight:bold;">Do you offer a faster shipping service?</p>
					<p style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;width:500px;">If you would like a faster service, you can email us on this page. We would send you an email link for the additional shipping fees.  Please note that a faster shipping service may not always be possible, and that if you want to guarantee that it is available for the items you'd like to order you should contact us prior to placing your order.</p> 
					</div>
					
					<p style="text-align:left;margin:5px 0;font-size:14px;line-height:140%;cursor:pointer;" class="siteboldlink" onclick="showhidereturns()" >Returns and Exchanges</p>
					
					<div style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;" id="returns">
					
						<p style="text-align:left;margin:9px 0;font-size:14px;line-height:140%;font-weight:bold;">Do you accept returns and exchanges?</p>
						<p style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;width:500px;">At Now That's Lingerie.com, your satisfaction is important to us. We take great pride in providing you with high quality and value. If you are dissatisfied with a purchase, we will gladly exchange or refund the purchase price provided it conforms to our returns policy.</p>
						
						<p style="text-align:left;margin:9px 0;font-size:14px;line-height:140%;font-weight:bold;">How long do I have to request a return or exchange?</p>
						
						<p style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;width:500px;">If over thirty (30) days have passed since we have shipped your order, you can exchange or return your purchase for up to and no longer than 60 days from the purchase date.</p>
						<p style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;width:500px;">If more than (30) days have passed since the original purchase was shipped and you would either like to exchange or have any of the items in your order refunded, we reserve the right to accept or refuse them. If we accept, we will deduct from your account 20% or $10, whichever of the two is greater. Returns after 60 days will result in an online credit. </p>
							
						<p style="text-align:left;margin:9px 0;font-size:14px;line-height:140%;font-weight:bold;">How do I request a return or exchange?</p>
						
						<p style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;width:500px;">Use the contact form below to contact us to initiate the returns process. Please be sure to select "return" or "exchange" as your reason for contacting us to ensure the fastest possible service.  We will email you with an authorization number and further instructions once we've processed your request.  Please note that we are not responsible for any shipping costs incurred in the exchanging or returning of an item.</p>	
						<p style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;width:500px;">We cannot be responsible for returned merchandise until it arrives at our location, therefore we recommend that you send packages with insurance and an authorization number, as we will provide when you contact us. If there is no authorization number, the return will not be processed and therefore will not be accepted. The return address must be clearly indicated. If you decide to use Fed Ex or UPS to make your return, please make sure that you use the service level that contains brokerage fees. Do not use the standard service. You can use United States Postal Service.</p>
						
						<p style="text-align:left;margin:9px 0;font-size:14px;line-height:140%;font-weight:bold;">Are there any items that are non-refundable?</p>
						<p style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;width:500px;">It is not possible for us to accept returns or make exchanges on the following types of items:</p>
					
							<ul style="margin:0 0 0 20px;width:480px;line-height:145%;font-size:12px;">
							
								<li>Panties, bodysuits, teddies, or anything with an underwear bottom</li>
								<li>Lingerie accessories such as bra straps, bra back extenders, bra back converters, nipple covers, bra pads, breast enhancers and adhesive tape</li>
								<li>Any products that are discontinued, on clearance, or on sale</li>
								<li>Orders of three (3) or more of the same style number, same color and same size</li>
								<li>Orders of three (3) or more different sizes of the same exact style number (ex: ordering 36D, 36DD and 38D in the same style)</li>
								<li>Items that have marks or remnants of odor as a result of being tried on while wearing deodorant or perfume.</li>
							
							</ul>
							
						<p style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;width:500px;">We understand bra fitting can be complex, and advise you to contact our Bra Doctor to get more specific sizing advice and solutions for the styles that interest you, prior to placing your order.</p> 
					
					
					</div>
					
					<p style="text-align:left;margin:5px 0;font-size:14px;line-height:140%;cursor:pointer;" class="siteboldlink" onclick="showhideprivacy()" >Privacy and Security</p>
					
					<div style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;" id="privacy">
					
					<p style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;width:500px;">Now That's Lingerie.com believes in providing a safe and secure shopping experience for all our customers.</p>

					<p style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;width:500px;">The only consumer data we collect are provided from statistics referring to tracking, country of origin, which search engine was used, keywords and which pages were consulted. If you have any questions pertaining to this, please feel free to contact us.</p>

					<p style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;width:500px;">Once you have selected an item and are ready to purchase, when you select Add to Cart your payment and order details are transferred by secure connection to PayPal our payment gateway. This means that the information is encrypted and cannot be read by anyone other than Paypal. You can see that the pages are secure because the address in the address bar changes from being http:// to https//. You will also see a padlock symbol or key in the bottom bar of your browser.</p>

					<p style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;width:500px;">Information Security PayPal is committed to handling customer information with high standards of information security. Your credit card and bank account information are stored only in encrypted form on computers that are not connected to the Internet. PayPal maintains physical, electronic and procedural safeguards that comply with federal regulations to guard your nonpublic information. PayPal tests their security systems regularly and also contract with outside companies to audit their security systems and processes.</p>
					
					</div>
					
					<p style="text-align:left;margin:5px 0;font-size:14px;line-height:140%;cursor:pointer;" class="siteboldlink" onclick="showhidejobs()" >Job Opportunities</p>
					
					<div style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;" id="jobs">
					
					<p style="text-align:left;margin:9px 0;font-size:14px;line-height:140%;font-weight:bold;">Want to get paid to write a fashion column about lingerie and fashion?</p>
					<p style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;width:500px;">Although Now That's Lingerie has been operating for almost a decade, our growing Bra Doctor blog is still in a transitional stage. We're looking for fresh ideas and perspectives on fashion, lingerie, bra fitting and lifestyle to help our readers and customers navigate through the sea of online information to get the most relevant, informative and updated news and articles. For sample articles, refer to <a href="http://www.nowthatslingerie.com/bradoctor/blog/" class="siteboldlink">the Bra Doctor Blog</a>.</p>
					
					<p style="text-align:left;margin:9px 0;font-size:14px;line-height:140%;font-weight:bold;">Requirements</p>
					<p style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;width:500px;">Send your most recent CV, along with a brief (spell-checked) writing sample (250-500 words) about a fashion-related topic of your choice. You can also list other topics you may be interested in writing about, such as health, lifestyle, dating, etc., in ADDITION to fashion.</p>
					<p style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;width:500px;">E-mail above items and/or any questions to lisa@nowthatslingerie.com. Attachments should be in .doc, .wps, .rtf, .pdf file formats only. </p>
						
					<p style="text-align:left;margin:9px 0;font-size:14px;line-height:140%;font-weight:bold;">What we're looking for</p>
					
						<ul style="margin:0 0 0 20px;width:480px;line-height:145%;font-size:12px;">
						
							<li>Excellent written skills in English</li>
							<li>Good at sticking to deadlines</li>
							<li>Personal interest in fashion</li>
							<li>Knowledgeable about fashion</li>

						</ul>
						
					<p style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;width:500px;">No previous blogging experience is necessary, but if you already do blog or have your own website, please include the URLs in your CV.</p>
					
					<p style="text-align:left;margin:9px 0;font-size:14px;line-height:140%;font-weight:bold;">Compensation</p>
					<p style="text-align:left;margin:5px 0;font-size:12px;line-height:140%;width:500px;">To be discussed. Varies depending on length and frequency of blog posts. Freelance bloggers should include quotes previously charged, if applicable.</p>
					
					<p style="text-align:left;margin:9px 0;font-size:14px;line-height:140%;font-weight:bold;">Other Benefits</p>
					
						<ul style="margin:0 0 0 20px;width:480px;line-height:145%;font-size:12px;">
						
							<li>Flexible scheduling</li>
							<li>Work from home</li>
							<li>Employee discounts on lingerie and accessories</li>
							<li>Creative freedom</li>
							<li>Gaining experience in blogging and writing</li>
							<li>Reciprocal publicity for your own personal blogs and/or websites (to discuss)</li>

						</ul>
					</div>
					
					<p style="text-align:left;margin:9px 0;font-size:16px;line-height:140%;font-weight:bold;">If you still have questions, contact us:</p>
					
					
					<form  action="contact_us.php#messagesent" id="contactform" name="contactform" onsubmit="return validateForm()">
					
					<div style="margin:3px 5px 3px 5px;width:110px;float:left;text-align:right;height:24px;">
					
						<p style="font-size:14px;margin:9px 0 0 0;">Name: </p>
						
					</div>
					
					<div style="margin:3px 0;float:left;">
					
						<input type="text" name="name" style="width:200px;font-size:14px;font-weight:bold;padding:5px;font-family:verdana,sans-serif;background-color:#F4DDF2;border:1px solid #692260;"/>
						<p id="firstnamefail" style="margin:4px 0 0 0;font-family:verdana,sans-serif;color:red;font-style:italic;font-size:12px;">Please input your name!</p>
						
					</div>
					
					<br style="clear:both;" />
					
					<div style="margin:3px 5px 3px 5px;width:110px;float:left;text-align:right;height:24px;">
					
						<p style="font-size:14px;margin:9px 0 0 0;">Email Address:</p>
						
					</div>
					
					<div style="margin:3px 0;float:left;">
					
						<input type="text" name="email" style="width:200px;font-size:14px;font-weight:bold;padding:5px;font-family:verdana,sans-serif;background-color:#F4DDF2;border:1px solid #692260;"/>
						<p id="emailfail" style="margin:4px 0 0 0;font-family:verdana,sans-serif;color:red;font-style:italic;font-size:12px;">Please input a valid email address!</p>
						
					</div>
					
					<br style="clear:both;" />
					
					<div style="margin:3px 5px 3px 5px;width:110px;float:left;text-align:right;height:24px;">
					
						<p style="font-size:14px;margin:9px 0 0 0;">Subject:</p>
						
					</div>
					
					<div style="margin:3px 0;float:left;">
					
						<input type="text" name="subject" style="width:200px;font-size:14px;font-weight:bold;padding:5px;font-family:verdana,sans-serif;background-color:#F4DDF2;border:1px solid #692260;"/>
						<p id="subjectfail" style="margin:4px 0 0 0;font-family:verdana,sans-serif;color:red;font-style:italic;font-size:12px;">Don't forget your subject!</p>
						
					</div>
					
					<br style="clear:both;" />
					
					<div style="width:330px;text-align:center;">
					
						<select name="question" class="productselectyoursize">
						
							<option value="na">What does your question concern?</option>
							<option value="Fitting Advice">Fitting and Sizing Advice</option>
							<option value="Fashion Advice">Fashion Advice</option>
							<option value="Other Advice">Other Advice</option>
							<option value="Shipping and Handling">Shipping and Handling</option>
							<option value="Returns and Exchanges">Returns and Exchanges</option>
							<option value="Privacy and Security">Privacy and Security</option>
							<option value="Jobs at NTL">Jobs at Now That's Lingerie</option>
							<option value="Where's my Stuff?">Where's my stuff?</option>
							<option value="Other Topics">Other</option>
							
						</select>
						
						<p id="questionfail" style="margin:4px 0 0 0;font-family:verdana,sans-serif;color:red;font-style:italic;font-size:12px;">Please select an option.</p>
						
					
					</div>
					
					<textarea name="message" ONFOCUS="clearDefault(this)" onblur="restoreDefault(this)" style="margin:10px 0;width:320px;border:1px solid #692260;height:200px;padding:5px;font-size:12px;line-height:140%;font-family:verdana;background-color:#F4DDF2;">Write out your question in full here.  Please be specific and clear in your request so that we can provide you with the best service possible. If your message concerns return or exchange, please provide your order number. If your message is about a specific item on our website, please include the item number and brand.</textarea>
						
					<input type="hidden" name="sent" value="1" />
					
					<div style="width:330px;text-align:center;">
					
						<p id="messagefail" style="margin:0 0 10px 0;font-family:verdana,sans-serif;color:red;font-style:italic;font-size:12px;">If you're gonna send a message, send a message; don't send nothing!</p>
						<input type="image" src="images/contactus.jpg" onmouseover="this.src='images/contactus2.jpg'" onmouseout="this.src='images/contactus.jpg'"/>
						
					</div>
					
					<?php 
					
						if ($_GET["sent"] == 1)
							{
							
							echo "<div id='messagesent' style='width:330px;text-align:center;margin:5px 0;'>
					
								<p style='font-weight:bold;color:green;font-family:verdana;font-size:16px;'>Message Sent! You should receive a response within 48 business hours.</p>
					
							</div>";
							
							}
							
					?>
					
					</form>
				</div>
			
			</div>
			
			<br style="clear:both;" />
			
			<? include("includes/footer.php"); ?>
		
		</div>

	</body>
</html>