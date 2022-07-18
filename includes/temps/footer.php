            </main>
            <!-- end main -->
        </div>
        <!-- main content end -->
        <!-- Core -->
        <script src="<?php echo $js; ?>jquery.min.js"></script>
        <script src="<?php echo $js; ?>bootstrap.min.js"></script>
        <script src="<?php echo $js; ?>Chart.min.js"></script>
        <script src="<?php echo $js; ?>Chart.extension.js"></script>
        <script>
            var salesMonths = [<?php getJsonUrl("salesMonthsAPI.json")?>];
            var ordersData  = [<?php getJsonUrl("ordersMonthsAPI.json")?>]
        </script>
        <script src="<?php echo $js; ?>main.js?v=<?php echo time(); ?>"></script>
        <script>
            <?php 
                if($display == "night"){
                    echo "setMode('night');";
                }else{
                    echo "setMode('light');";
                }
            ?>
        </script>
    </body>
</html>