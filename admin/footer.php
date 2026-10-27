<!-- footer start-->
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 footer-copyright">
                <p class="mb-0"><?php _e('Copyright') ?> © <?php _esc(date('Y'))  ?> <a href="https://bylancer.com" target="_blank">Bylancer</a>. <?php _e('All rights reserved.') ?> </p>
            </div>
            <div class="col-md-6">
                <p class="float-right mb-0"><?php _e('Hand-crafted &amp; made with') ?> <i class="icon-feather-heart"></i></p>
            </div>
        </div>
    </div>
</footer>

</div>
</div>
</div>
<script src="<?php echo ADMINURL; ?>assets/js/jquery.min.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/jquery-ui.min.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/popper.min.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/bootstrap.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/tippy.all.min.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/jquery.dataTables.min.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/jquery-slidePanel.min.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/select2.full.min.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/sidebar-menu.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/sweetalert.min.js"></script>
<?php if(isset($footer_content)) {
    echo $footer_content;
} ?>
<script src="<?php echo ADMINURL; ?>assets/js/jquery.form.js"></script>
<script src="<?php echo ADMINURL; ?>assets/js/admin-ajax.js?ver=<?php echo $config['version']; ?>"></script>
<script src="<?php echo ADMINURL; ?>assets/js/script.js?ver=<?php echo $config['version'];?>"></script>
</body>
</html>