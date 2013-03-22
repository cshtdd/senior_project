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
    <!--[if lt IE 9]>
        <script type="text/javascript">
			$(document).ready(function(){
				$('form').html5form();
			});
        </script>
    <![endif]-->
</body>
</html>
