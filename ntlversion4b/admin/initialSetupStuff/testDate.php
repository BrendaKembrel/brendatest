<script src="https://www.google.com/jsapi?key=ABQIAAAA507RtvBLuuaSZbb8zMTvLxTHPN6PuYxRTcBd44EgPHj7yqQopRRRhgjlh2mhvCiGkNANQHeCtGHGTA" type="text/javascript"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script>
	google.load("jqueryui", "1.8.13");
	
	$(function() {
		$( "#datepicker" ).datepicker();
	});
	</script>



<div class="demo">

<p>Date: <input type="text" id="datepicker"></p>

</div><!-- End demo -->



<div class="demo-description" style="display: none; ">
<p>The datepicker is tied to a standard form input field.  Focus on the input (click, or use the tab key) to open an interactive calendar in a small overlay.  Choose a date, click elsewhere on the page (blur the input), or hit the Esc key to close. If a date is chosen, feedback is shown as the input's value.</p>
</div><!-- End demo-description -->