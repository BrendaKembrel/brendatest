<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
   
<html xmlns="http://www.w3.org/1999/xhtml">
   
   <head>
		
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="css/ntlcurrent.css" />
		
		<title>DATABASE Collection Name</title>
		<meta name="description" content="DATABASE Collection Meta Description" />
		<meta name="keywords" content="DATABASE Collection Tags?" />
		
	</head>

	<body>
				 
	
		<div id="sitewrapper">
		
			<? include("includes/header.php"); ?>
			<? include("includes/menu.php"); ?>
			
			<div id="sitecontent">
			
				<div id="collectiondescriptiondiv">
				
					<h1 class="collectionheader">DB Name of Collection</h1>
					
					<img src="images/colorswatchesexample.jpg" alt="colour swatches." />
					
					<p class="collectiondescription">Lorem ipsum dolor sit amet, consectetur adipiscing elit. In pulvinar, turpis et sollicitudin tempus, leo nibh fringilla quam, porta imperdiet nisi dolor eget leo. Suspendisse ac nisi sit amet nisl imperdiet iaculis. Aenean vitae tellus est. Donec nec est augue. Aliquam est turpis, vestibulum a consequat ac, auctor a purus. Nunc sit amet purus vel turpis tristique ultricies. Nam mauris felis, adipiscing at eleifend in, ornare in eros. Maecenas dui lorem, scelerisque dapibus dapibus eget, hendrerit eget magna. Nullam est eros, sagittis id feugiat et, laoreet porta mauris. Quisque adipiscing lacinia libero eu tempus.</p>
				
				</div>
				<!--
				<div id="collectionmagazinediv">
				
					<h1 class="collectionheader">As Seen In...</h1>
					
					<a href="/"><img src="images/lookingglass.jpg" alt="Looking Glass" /></a> Cosmotopian Magazine
					<br /> <a href="/"><img src="images/lookingglass.jpg" alt="Looking Glass" /></a> Frou Frou Pou Pou Magazine
				
				</div>
				-->
				<br style="clear:both;" />
				
				
				<div class="collectionsectionheader">
				
					<h1 class="collectionsectionheadertext">Styles in [Collection Name] Collection by DB Brand</h1>
				
				</div>
				
				<!--

				This part here's a little messed up.  The first container needs to have a margin on the left of 28px
				to line up with the header properly and make the layout flow properly, while the rest of them have a
				margin of 26px.  That means that before entering the loop to display all of the items, we'll need to
				handle the first item of each line individually. It sort of sucks, but it just sort of has to be that
				way. I guess it's not so bad, though, since we need a clear:both at the end of every line anyway, which
				would break up the loop a bit.
						
				-->
				
				<div class="collectionitemcontainer" style="margin-left:28px;">
				
					<a href="/"><img src="images/placeholderimg.jpg" alt="DB Item Name" /></a><br />
					
					<div class="collectionbagoverlay"><img src="images/addtobag.png" /></div>
					
					<a href="/" class="collectionproductname">Product Name by Brand</a><br />
					<a href="/" class="collectionregprice">Reg: XX.XX USD</a><br />
					<a href="/" class="collectionsaleprice">Sale: XX.XX USD</a><br />
					<a href="/" class="collectionyousave">You Save: XX.XX USD (XX%)</a><br />
					<a href="/" class="collectionpromo">See Current Promotion</a>
					
				
				</div>
				
				<div class="collectionitemcontainer">
				
					<a href="/"><img src="images/placeholderimg.jpg" alt="DB Item Name" /></a><br />
					
					<div class="collectionbagoverlay"><img src="images/addtobag.png" /></div>
					
					<a href="/" class="collectionproductname">Product Name by Brand</a><br />
					<a href="/" class="collectionregprice">Reg: XX.XX USD</a><br />
					<a href="/" class="collectionsaleprice">Sale: XX.XX USD</a><br />
					<a href="/" class="collectionyousave">You Save: XX.XX USD (XX%)</a><br />
				
				</div>

				
				<br style="clear:both;"/>
			
				
				
				<!--<div class="collectionsectionheader">
				
					<h1 class="collectionsectionheadertext">Package Deals</h1>
				
				</div>
				
				<div class="collectionpackagedealtext">Buy all DBItemCount of these items and receive a discount of <span class="collectionredandbold">XX%</span>!</div>
				
				<div class="collectionpackagecontainer">
				
					<div class="collection3plusitemscontainer">
					
						<img src="images/placeholderforpackagedeals.jpg" alt="DB Item Name" />
						<img src="images/placeholderforpackagedeals.jpg" alt="DB Item Name" />
						<img src="images/placeholderforpackagedeals.jpg" alt="DB Item Name" />
					
					</div>
					
					<div class="collectionplusitemspackage">
					
						<span class="collectionredplus">+</span><br />
						<span class="sitefont16px">3 more items</span>
						
					</div>
			
				</div>
				
				<div class="collectionpackagepricecontainer">
				
					<p class="collectionpackageproductname">Total Regular Price</p>
					<p class="collectionpackagereg">XX.XX USD</p>
					<p class="collectionpackageproductnamered">Price in Package</p>
					<p class="collectionpackagesale">XX.XX USD</p>
					<p class="collectionpackageproductname">You Save</p>
					<p class="collectionpackagereg">XX.XX USD(XX%)</p>
					
					<div class="collectioncenter100atb"><img src="images/addtobag2.jpg" alt="Add to Bag"/></div>
					
				</div>
				
				<div class="collectionpackageinfocontainer">
				
					<p class="collectionpackageproductname">Product Name by Brand Lingerie</p>
					<p class="collectionpackagereg">Reg - XX.XX USD</p>
					<p class="collectionpackageproductname">Product Name by Brand Lingerie</p>
					<p class="collectionpackagereg">Reg - XX.XX USD</p>
					<p class="collectionpackageproductname">Product Name by Brand Lingerie</p>
					<p class="collectionpackagereg">Reg - XX.XX USD</p>
					<div class="collectionadditionalitems"><span class="sitefont16px">+ <span class="siteboldlink">3</span> items</span></div>
					<div class="collectionseedetails"><span class="sitefont16px">[<a href="/" class="sitebasiclink">see details</a>]</span></div>
			
				</div>
				
				<div style="width:100%;text-align:center;"><hr style="width:80%;clear:both;" /></div>
				
				<div class="collectionpackagedealtext">Buy all DBItemCount of these items and receive a discount of <span class="collectionredandbold">XX%</span>!</div>
				
				<div class="collectionpackagecontainer">
				
					<div class="collection3plusitemscontainer">
					
						<img src="images/placeholderforpackagedeals.jpg" alt="DB Item Name" />
						<img src="images/plus.jpg" alt="Plus" />
						<img src="images/placeholderforpackagedeals.jpg" alt="DB Item Name" />
						<img src="images/plus.jpg" alt="Plus" />
						<img src="images/placeholderforpackagedeals.jpg" alt="DB Item Name" />
					
					</div>
			
				</div>
				
				<div class="collectionpackagepricecontainer">
				
					<p class="collectionpackageproductname">Total Regular Price</p>
					<p class="collectionpackagereg">XX.XX USD</p>
					<p class="collectionpackageproductnamered">Price in Package</p>
					<p class="collectionpackagesale">XX.XX USD</p>
					<p class="collectionpackageproductname">You Save</p>
					<p class="collectionpackagereg">XX.XX USD(XX%)</p>
					
					<div class="collectioncenter100atb"><img src="images/addtobag2.jpg" alt="Add to Bag"/></div>
					
				</div>
				
				<div class="collectionpackageinfocontainer">
				
					<p class="collectionpackageproductname">Product Name by Brand Lingerie</p>
					<p class="collectionpackagereg">Reg - XX.XX USD</p>
					<p class="collectionpackageproductname">Product Name by Brand Lingerie</p>
					<p class="collectionpackagereg">Reg - XX.XX USD</p>
					<p class="collectionpackageproductname">Product Name by Brand Lingerie</p>
					<p class="collectionpackagereg">Reg - XX.XX USD</p>
					<div class="collectionseedetails"><span class="sitefont16px">[<a href="/" class="sitebasiclink">see details</a>]</span></div>
			
				</div>
				
				<div style="width:100%;text-align:center;"><hr style="width:80%;clear:both;" /></div>
				
				<div class="collectionpackagedealtext">Buy all DBItemCount of these items and receive a discount of <span class="collectionredandbold">XX%</span>!</div>
				
				<div class="collectionpackagecontainer">
				
					<div class="collection2minusitemscontainer">
					
						<img src="images/placeholderforpackagedeals.jpg" alt="DB Item Name" />
						<img src="images/plus.jpg" alt="Plus" />
						<img src="images/placeholderforpackagedeals.jpg" alt="DB Item Name" />
					
					</div>
			
				</div>
				
				<div class="collectionpackagepricecontainer">
				
					<p class="collectionpackageproductname">Total Regular Price</p>
					<p class="collectionpackagereg">XX.XX USD</p>
					<p class="collectionpackageproductnamered">Price in Package</p>
					<p class="collectionpackagesale">XX.XX USD</p>
					<p class="collectionpackageproductname">You Save</p>
					<p class="collectionpackagereg">XX.XX USD(XX%)</p>
					
					<div class="collectioncenter100atb"><img src="images/addtobag2.jpg" alt="Add to Bag"/></div>
					
				</div>
				
				<div class="collectionpackageinfocontainer">
				
					<p class="collectionpackageproductname">Product Name by Brand Lingerie</p>
					<p class="collectionpackagereg">Reg - XX.XX USD</p>
					<p class="collectionpackageproductname">Product Name by Brand Lingerie</p>
					<p class="collectionpackagereg">Reg - XX.XX USD</p>
					<div class="collectionseedetails2item"><span class="sitefont16px">[<a href="/" class="sitebasiclink">see details</a>]</span></div>
			
				</div>
				
				<div style="width:100%;text-align:center;"><hr style="width:80%;clear:both;" /></div>
				
				<div class="collectionpackagedealtext">Buy XX of this item and get a discount of <span class="collectionredandbold">XX%</span>!</div>
				
				<div class="collectionpackagecontainer">
				
					<div class="collection2minusitemscontainer">
					
						<img src="images/placeholderforpackagedeals.jpg" alt="DB Item Name" />
						<span class="sitefont24px">x3</span>
					
					</div>
			
				</div>
				
				<div class="collectionpackagepricecontainer">
				
					<p class="collectionpackageproductname">Total Regular Price</p>
					<p class="collectionpackagereg">XX.XX USD</p>
					<p class="collectionpackageproductnamered">Price in Package</p>
					<p class="collectionpackagesale">XX.XX USD</p>
					<p class="collectionpackageproductname">You Save</p>
					<p class="collectionpackagereg">XX.XX USD(XX%)</p>
					
					<div class="collectioncenter100atb"><img src="images/addtobag2.jpg" alt="Add to Bag"/></div>
					
				</div>
				
				<div class="collectionpackageinfocontainer">
				
					<p class="collectionpackageproductname">Product Name by Brand Lingerie</p>
					<p class="collectionpackagereg">Reg - XX.XX USD</p>
					<div class="collectionseedetails1item"><span class="sitefont16px">[<a href="/" class="sitebasiclink">see details</a>]</span></div>
			
				</div>
				
				<div style="width:100%;text-align:center;"><hr style="width:80%;clear:both;" /></div>
			
			</div>
			
			<div class="collectionsectionheader">
				
					<h1 class="collectionsectionheadertext">Related Articles and Blogs</h1>
				
			</div>
			
			<div class="collection3colleft">
			
				<a class="collectionblogtitle" href="/">Related Blog Title</a>
			
				<p class="collectionrelatedarticles">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sed tortor a odio gravida 
				elementum. Nunc neque enim, condimentum in interdum a, consectetur eu mauris. Maecenas lorem erat, hendrerit at congue a, bibendum 
				et augue. Duis nisl magna, pretium vitae semper at, condimentum non tortor. Nullam blandit, mi non convallis ullamcorper, nunc 
				massa facilisis nibh, in cursus libero est non tortor. Pellentesque tincidunt, mauris in tristique eleifend, elit libero dictum 
				felis, quis pellentesque odio ipsum ut velit. Integer at neque eu urna bibendum blandit interdum at dui.</p>
				
			</div>
			
			<div class="collection3colmiddle">
			
				<a class="collectionblogtitle" href="/">Related Blog Title</a>
			
				<p class="collectionrelatedarticles">Nullam enim neque, consectetur at pharetra eu, egestas ut ipsum. Nulla orci nisl, gravida 
				non pretium vel, semper ac lectus. Curabitur viverra, libero sit amet mattis pretium, est mi gravida magna, a pellentesque enim 
				nisl et massa. Pellentesque at fermentum mi. Duis vitae luctus urna. Aliquam neque nisl, eleifend id tincidunt non, blandit in 
				lacus. Pellentesque varius odio ullamcorper lectus aliquet eget blandit erat dapibus. Maecenas a tempor dolor. Aliquam vel lectus 
				at ligula accumsan euismod in sit amet eros.</p>
				
			</div>
			
			<div class="collection3colright">
			
				<a class="collectionblogtitle" href="/">Related Blog Title</a>
			
				<p class="collectionrelatedarticles">Integer non erat lacus, condimentum vulputate ipsum. Fusce volutpat dictum enim laoreet interdum. 
				Curabitur fringilla fermentum felis, eu vestibulum metus suscipit lobortis. Phasellus eros orci, suscipit id luctus at, porttitor quis 
				quam. Mauris sed nisl tortor, et auctor nisi. Aliquam justo lorem, sodales scelerisque vestibulum vitae, malesuada eget quam. Quisque 
				orci neque, tempor vel auctor consectetur, sollicitudin sed mi. Ut vulputate lorem at velit pretium at tristique lorem varius. Aenean 
				in dolor ac velit eleifend tincidunt.</p>
			
			</div>
			
			<br style="clear:both;" />
			-->
			
			<div class="collectionsectionheader">
				
				<h1 class="collectionsectionheadertext">Other Blush Collections</h1>
				
			</div>
			
			<div class="collection3colleft">
			
				<div class="sitecenter"><a class="collectionblogtitle" href="/">Collection Name</a></div>
			
				<p class="collectionrelatedarticles">Integer non erat lacus, condimentum vulputate ipsum. Fusce volutpat dictum enim laoreet interdum. 
				Curabitur fringilla fermentum felis, eu vestibulum metus suscipit lobortis. Phasellus eros orci, suscipit id luctus at, porttitor quis 
				quam.</p>
				
			</div>
			
			<div class="collection3colmiddle">
			
				<div class="sitecenter"><a class="collectionblogtitle" href="/">Collection Name</a></div>
			
				<p class="collectionrelatedarticles">Integer non erat lacus, condimentum vulputate ipsum. Fusce volutpat dictum enim laoreet interdum. 
				Curabitur fringilla fermentum felis, eu vestibulum metus suscipit lobortis. Phasellus eros orci, suscipit id luctus at, porttitor quis 
				quam.</p>
				
			</div>
			
			<div class="collection3colright">
			
				<div class="sitecenter"><a class="collectionblogtitle" href="/">Collection Name</a></div>
			
				<p class="collectionrelatedarticles">Integer non erat lacus, condimentum vulputate ipsum. Fusce volutpat dictum enim laoreet interdum. 
				Curabitur fringilla fermentum felis, eu vestibulum metus suscipit lobortis. Phasellus eros orci, suscipit id luctus at, porttitor quis 
				quam.</p>
				
			
			</div>
			
			<br style="clear:both;" />
			
			<div class="collection3colleft">
			
				<div class="sitecenter"><img src="images/placeholderforpackagedeals.jpg" alt="Collection Image" style="width:280px;" /></div>
			
			</div>
			
			<div class="collection3colmiddle">
			
				<div class="sitecenter"><img src="images/placeholderforpackagedeals.jpg" alt="Collection Image" style="width:280px;" /></div>
			
			</div>
			
			<div class="collection3colright">
			
				<div class="sitecenter"><img src="images/placeholderforpackagedeals.jpg" alt="Collection Image" style="width:280px;" /></div>
			
			</div>
			
			
			<br style="clear:both;" />
			
				
			<? include("includes/footer.php"); ?>
		
		</div>

	</body>
</html>