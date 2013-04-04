    </div>
    <hr>
    <div class="footer">
        <p> <!-- <a href="http://www.cash-productions.com"> © CASH Productions 2013 </a> -->
            <a href="http://www.fiu.edu">Florida International University </a>

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
                e.stopPropagation();
                $('#alert-js').hide();
            });
        });
    </script>

    <script src="http://browser-update.org/update.js"></script> 
</body>
</html>
