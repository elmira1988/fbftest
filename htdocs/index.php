<?php/* header("Location: http://fbftest.strbsu.ru/fbf-test/");*/?>
<?php include("templates/head.html"); ?>

<body>
	
    <div class="header">
    	
    	<div style="min-width:221px;width:25%;">
    		<img src="/assets/img/str_left.png" style="height:70px;">
    	</div>
    	
    	<div class="header_block" style="width:50%;min-width: 550px;">
    		<img src="/assets/img/bgu_1.png" style="max-height:70px;">
    		<span>Стерлитамакский филиал БашГУ</span>	
    	</div> 
    	
    	<div style="min-width:221px;width:25%;">
    		<img src="/assets/img/str_left.png" style="height:70px;float:right">
    	</div>
    </div>
	

<!--Multi Step Wizard Start-->
<div id="rms-wizard" class="rms-wizard">

    <div class="sf_bgu">

	<center>
	<div style="display: block;padding: 20px;">
		<a href="https://fbftest.strbsu.ru/fbf-test/" style="padding: 10px;">
			<button style="padding: 5px 10px;">перейти к тестированию</button>
		</a>
	</div>
    </center>

        <!--Wizard Container-->        
            <div class="rms-container">
               <!--Wizard Header-->
                <div class="rms-wizard-header">
                    <h2 class="title">
            		<span>Республиканская олимпиада по башкирскому языку</span><br>
            		<span style="color: #a8a8a8;font-size: 12pt;">регистрация</span>
            		</h2>
                </div>

                <!--Wizard Header Close--> 
                <div class="rms-form-wizard" "> 
                   
            	   <?php include("templates/agreement.html"); ?>   
                    <!--Wizard Step Navigation Start-->				 
                    <?php include("templates/wizard_step_navigation.html"); ?>   
                    <!--Wizard Navigation Close-->

                    <!--Wizard Content Section Start-->
                    <div class="rms-content-section none_visibility">
                        <form method="POST" action="" id="data_of_student">
                        <?php include("templates/wizard_step_1.html"); ?>  
            			
            			<?php include("templates/wizard_step_2.html"); ?>  
            			</form>
                        
                        <?php include("templates/wizard_step_3.html"); ?> 

                    </div>
                    <!--Wizard Content Section Close-->	

            		<div class="oferta"><?php include 'get_dogovor_oferta.html';?></div>
            		
            		
            		<!--Wizard Footer section Start-->
                    <div class="rms-footer-section" style="height:40px;">
                        <div class="button-section">

                            <div class="save_data" >Идет отправка данных, пожалуйста, подождите...</div>

            				<div class="col-xs-10" id="check_agreement">
            					<div class="block_inline" style="pointer-events: none;">
            						<input type="checkbox" class="checkradios custom" checked/>
            						<label class="for_checkbox" style="line-height: 22px;">
            						<p style="line-height: 15px;height: 20px;margin: 0px;position:absolute;">Нажимая кнопку "Далее" я принимаю условия договора публичной оферты оказания услуг по обеспечению участия в научном мероприятии</p>
            						</label>
            					</div>
            				</div>
							
                            <span class="next">
                                <p onclick="next_step(this)" class="btn">Далее
                                </p>
                            </span>
            				
                            <span class="prev" style="visibility:hidden;display:none;">
                                <p onclick="prev_step(this)" class="btn" style="margin:0px;">Назад
                                </p>
                            </span>
                        </div>
                    </div>
                    <!--Wizard Footer Close-->
            		
                </div>

            </div>
        
    </div><!--background-color-->
    <!--Wizard Container Close-->
    <?php include('functions.php');?>
    <div class="footer">
        ©  <?php echo get_setting()['year']?> Стерлитамакский филиал БашГУ, все права защищены
    </div>
</div>
<!--Multi Step Wizard Close-->
<?php include("templates/footer.html"); ?>
</body>

</html>