		<!-- BEGIN MENUBAR-->
			<div id="menubar" class="menubar-inverse ">
				<div class="menubar-scroll-panel">

					<!-- BEGIN MAIN MENU -->
					<ul id="main-menu" class="gui-controls">

						<!-- BEGIN ADD CATEGORY -->
						<li>
							<a href="<?php echo base_url();?>admin_master/product_list" class="<?php if($active_class=='product') { echo 'active';}?>  menu_cls">
								<div class="gui-icon"><i class="fa fa-file-text"></i></div>
								<span class="title">Product</span>
							</a>
						</li><!--end /menu-li -->
						<!-- END ADD CATEGORY -->
						
							<!-- BEGIN ADD SUB CATEGORY -->
						<li>
							<a href="<?php echo base_url();?>admin_master/package_list"  class="<?php if($active_class=='package') { echo 'active';}?> menu_cls">
								<div class="gui-icon"><i class="fa fa-files-o"></i></div>
								<span class="title">Packages</span>
							</a>
						</li><!--end /menu-li -->
						<!-- END ADD SUB CATEGORY -->
                        <li>
							<a href="<?php echo base_url();?>admin_master/order_list"  class="<?php if($active_class=='order') { echo 'active';}?> menu_cls">
								<div class="gui-icon"><i class="fa fa-list"></i></div>
								<span class="title">Orders</span>
							</a>
						</li>
						



						<!--end /menu-li -->
						

						
						

						

						
						
						<!-- END ADD WORDS -->

					</ul><!--end .main-menu -->
					<!-- END MAIN MENU -->

					<div class="menubar-foot-panel">
						<small class="no-linebreak hidden-folded">
							<span class="opacity-75">Copyright &copy; 2017</span> <strong>SFI</strong>
						</small>
					</div>
				</div><!--end .menubar-scroll-panel-->
			</div><!--end #menubar-->
			<!-- END MENUBAR -->

		</div><!--end #base-->
		<!-- END BASE -->