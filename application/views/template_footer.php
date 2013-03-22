    </div>
    <hr>
    <div class="footer">
        <p><a href="http://www.cash-productions.com"> © CASH Productions 2013 </a>

            <span class="pull-right">Rendered in <strong>{elapsed_time}</strong> seconds</span>
        </p>
    </div>
</div>
<!--
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="<?php echo base_url() ?>js/bootstrap.min.js"></script>
-->
	
    <script type="text/javascript">
		$(document).ready(function(){
			$('#alert-js').hide();
			$('#alert-js-close').click(function(e){
				e.preventDefault();
				$('#alert-js').hide();
			});
		});
    </script>
	
	<script type="text/javascript"> 
		var $buoop = {} 
		$buoop.ol = window.onload; 
		window.onload=function(){ 
		 try {if ($buoop.ol) $buoop.ol();}catch (e) {} 
		 var e = document.createElement("script"); 
		 e.setAttribute("type", "text/javascript"); 
		 e.setAttribute("src", "http://browser-update.org/update.js"); 
		 document.body.appendChild(e); 
		} 
	</script> 
</body>
</html>
